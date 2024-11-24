"""
Usage: python3 hourly.py <parkingID> <occupation>

Gets executed each hour at __:02
Adds a new row to daily csv
Updates daily predictions with observed data
"""
import pandas as pd
import sys
from datetime import datetime

daily_data_path = sys.argv[1] + '_daily_data.csv'
today_predictions_data_path = sys.argv[1] + '_today_predictions.csv'

# Get current timestamp
current_time = datetime.now()

# Create data for new row
new_data = {
    'month': current_time.month,
    'day': current_time.day,
    'hour': current_time.hour,
    'occupation': float(sys.argv[2])
}

# Append new row
try:
    daily_database = pd.read_csv(daily_data_path)
except FileNotFoundError:
    print('daily database does not exist')

daily_database = pd.concat([daily_database, pd.DataFrame([new_data])], ignore_index=True)
daily_database.to_csv(daily_data_path, index=False)

# Update today's predictions
today_predictions = pd.read_csv(today_predictions_data_path)
matching_hour_mask = today_predictions['hour'] == current_time.hour
today_predictions.loc[matching_hour_mask, 'predicted_occupation'] = new_data['occupation']
today_predictions.to_csv(today_predictions_data_path, index=False)