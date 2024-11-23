import pandas as pd
from predict import train
import joblib

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

# Save the model to disk
joblib.dump(model, 'daily_model.joblib')