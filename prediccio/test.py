import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import matplotlib.pyplot as plt

from predict import train, predict
from plots import plot_occupation

def generate_parking_data(num_days=30):
    """
    Generate synthetic parking data with realistic patterns:
    - Working days: High occupancy 9-17h
    - Weekends: Medium occupancy 10-20h
    - Lower occupancy at nights
    - Some random variation
    """
    data = []
    start_date = datetime(2024, 1, 1)
    
    for day in range(num_days):
        current_date = start_date + timedelta(days=day)
        is_weekend = current_date.weekday() >= 5
        
        for hour in range(24):
            # Base occupancy patterns
            if is_weekend:
                # Weekend pattern
                if 10 <= hour <= 20:  # Shopping/leisure hours
                    base_occupancy = 65
                elif 21 <= hour <= 23:  # Evening
                    base_occupancy = 45
                else:  # Night/early morning
                    base_occupancy = 20
            else:
                # Weekday pattern
                if 9 <= hour <= 17:  # Working hours
                    base_occupancy = 85
                elif 7 <= hour <= 8:  # Arrival time
                    base_occupancy = 70
                elif 18 <= hour <= 20:  # After work
                    base_occupancy = 50
                elif 21 <= hour <= 23:  # Evening
                    base_occupancy = 30
                else:  # Night/early morning
                    base_occupancy = 15
            
            # Add random variation (±15%)
            noise = np.random.normal(0, 5)
            occupancy = base_occupancy + noise
            
            # Ensure occupancy is between 0 and 100
            occupancy = np.clip(occupancy, 0, 100)
            
            data.append({
                'month': current_date.month,
                'day': current_date.day,
                'hour': hour,
                'occupation': occupancy
            })
    
    return pd.DataFrame(data)

# Create sample data
print("Generating synthetic data...")
df = generate_parking_data(num_days=30)

# Train the model
model = train(df)

# Make predictions for a specific day
month = 3
day = 15
predictions = predict(model, month, day)

plot_occupation(predictions, month, day)