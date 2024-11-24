# Usage: python3 hourly.py <occupation>
# Gets executed each hour at __:02
# Adds a new row to daily csv
# Updates daily predictions with observed data

import pandas as pd
import sys
from datetime import datetime

# Get current timestamp
current_time = datetime.now()

# Create data for new row
new_data = {
    'month': current_time.month,
    'day': current_time.day,
    'hour': current_time.hour,
    'occupation': float(sys.argv[1])
}

# Append new row
daily_database = pd.read_csv('daily_data.csv')
daily_database = pd.concat([daily_database, pd.DataFrame([new_data])], ignore_index=True)
daily_database.to_csv('daily_data.csv', index=False)

# Update today's predictions
today_predictions = pd.read_csv('today_predictions.csv')
matching_hour_mask = today_predictions['hour'] == current_time.hour
today_predictions.loc[matching_hour_mask, 'predicted_occupation'] = new_data['occupation']
today_predictions.to_csv('today_predictions.csv', index=False)