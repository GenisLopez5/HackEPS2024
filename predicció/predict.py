import pandas as pd
from sklearn.ensemble import RandomForestRegressor


def train(historical_path):
    
    # Prepare the training dataset
    Train = pd.read_csv(historical_path, low_memory=False)
    y_train = Train['occupation']
    x_train = Train[['month', 'day', 'hour']]

    # Train the model
    model = RandomForestRegressor(n_estimators=100)

    return model

def predict(model, month, day):
    
    # Create dataframe with all hours of the day
    hours = range(24)
    data = {
        'month': [month] * 24,
        'day': [day] * 24,
        'hour': list(hours)
    }
    x_predict = pd.DataFrame(data)

    # Make predictions
    y_predict = model.predict(x_predict)
    
    return y_predict