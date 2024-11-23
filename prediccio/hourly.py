# Usage: python3 hourly.py <occupation>

import pandas as pd
import sys
from datetime import datetime
from predict import predict

# Get current timestamp
current_time = datetime.now()

# Create data for new row
new_data = {
    'month': current_time.month,
    'day': current_time.day,
    'hour': current_time.hour,
    'occupation': float(sys.argv[1])
}

daily_database = pd.read_csv('daily_data.csv')

# Append new row
daily_database = pd.concat([daily_database, pd.DataFrame([new_data])], ignore_index=True)

# Save back to CSV
daily_database.to_csv('daily_data.csv', index=False)