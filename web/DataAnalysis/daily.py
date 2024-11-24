import pandas as pd
from predict import train, predict
from datetime import datetime, timedelta
import sys

def calc_daily(parkingID):

    """
    Gets executed every day at 00:01
    Adds yesterday's csv to historical csv
    Sets today's csv to empty
    Creates csv files with predictions for today and tomorrow

    """

    historic_data_path = './DataAnalysis/CSVs/' + parkingID + '_historic_data.csv'
    daily_data_path = './DataAnalysis/CSVs/' + parkingID + '_daily_data.csv'
    today_predictions_data_path = './DataAnalysis/CSVs/' + parkingID + '_today_predictions.csv'
    tomorrow_predictions_data_path = './DataAnalysis/CSVs/' + parkingID + '_tomorrow_predictions.csv'

    # Load CSVs from current directory
    try:
        historic_data = pd.read_csv(historic_data_path)
    except FileNotFoundError:
        print('Initializing parking ' + parkingID + ' historic data')
        historic_data = pd.DataFrame(columns=['month', 'day', 'hour', 'occupation'])

    #do the same as with historic data
    try:
        daily_data = pd.read_csv(daily_data_path)
    except FileNotFoundError:
        print('Initializing parking ' + parkingID + ' daily data')
        daily_data = pd.DataFrame(columns=['month', 'day', 'hour', 'occupation'])

    # Append daily data to historic
    updated_historic = pd.concat([historic_data, daily_data], ignore_index=True)
    updated_historic.to_csv(historic_data_path, index=False)

    # Clear daily CSV by creating empty DataFrame with same columns
    empty_df = pd.DataFrame(columns=daily_data.columns)
    empty_df.to_csv(daily_data_path, index=False)

    # Get time
    current_time = datetime.now()
    month_today = current_time.month
    day_today = current_time.day

    # Train new model
    model = None
    try:
        model = train(updated_historic)
    except ValueError:
        print('Not enough data to train model')
        print('Initializing today predictions to 0')
        #create today predictions csv and save it to today_predictions_data_path
        today_for_plotting = pd.DataFrame({
            'month': [month_today] * 24,
            'day': [day_today] * 24,
            'hour': range(24),
            'predicted_occupation': [0] * 24
        })
        today_for_plotting.to_csv(today_predictions_data_path, index=False)
        print('Exiting')
        return




    today_predictions = predict(model, month_today, day_today)

    # Save today's predictions to CSV
    today_for_plotting = pd.DataFrame({
        'month': [month_today] * 24,
        'day': [day_today] * 24,
        'hour': range(24),
        'predicted_occupation': today_predictions
    })
    today_for_plotting.to_csv(today_predictions_data_path, index=False)

    # Generate predictions for tomorrow
    tomorrow_time = current_time + timedelta(days=1)
    month_tomorrow = current_time.month
    day_tomorrow = current_time.day
    tomorrow_predictions = predict(model, month_tomorrow, day_tomorrow)

    # Save tomorrow's predictions to CSV
    tomorrow_for_plotting = pd.DataFrame({
        'month': [month_tomorrow] * 24,
        'day': [day_tomorrow] * 24,
        'hour': range(24),
        'predicted_occupation': tomorrow_predictions
    })
    tomorrow_for_plotting.to_csv(tomorrow_predictions_data_path, index=False)