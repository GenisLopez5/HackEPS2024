# Gets executed every day at 00:01

import pandas as pd
from predict import train, predict
from datetime import datetime, timedelta

# Load CSVs from current directory
historic_data = pd.read_csv('historic_data.csv')
daily_data = pd.read_csv('daily_data.csv')

# Append daily data to historic
updated_historic = pd.concat([historic_data, daily_data], ignore_index=True)
updated_historic.to_csv('historic_data.csv', index=False)

# Clear daily CSV by creating empty DataFrame with same columns
empty_df = pd.DataFrame(columns=daily_data.columns)
empty_df.to_csv('daily_data.csv', index=False)

# Train new model
model = train(updated_historic)

# Generate predictions for today
current_time = datetime.now()
month_today = current_time.month,
day_today = current_time.day,
today_predictions = predict(model, month_today, day_today)

# Save today's predictions to CSV
today_for_plotting = pd.DataFrame({
    'month': [month_today] * 24,
    'day': [day_today] * 24,
    'hour': range(24),
    'predicted_occupation': today_predictions
})
today_for_plotting.to_csv('today_predictions.csv', index=False)

# Generate predictions for tomorrow
tomorrow_time = current_time + timedelta(days=1)
month_tomorrow = current_time.month,
day_tomorrow = current_time.day,
tomorrow_predictions = predict(model, month_tomorrow, day_tomorrow)

# Save tomorrow's predictions to CSV
tomorrow_for_plotting = pd.DataFrame({
    'month': [month_tomorrow] * 24,
    'day': [day_tomorrow] * 24,
    'hour': range(24),
    'predicted_occupation': tomorrow_predictions
})
tomorrow_for_plotting.to_csv('tomorrow_predictions.csv', index=False)