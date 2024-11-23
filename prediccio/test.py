# Usage: python3 hourly.py <occupation>

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

print(new_data)