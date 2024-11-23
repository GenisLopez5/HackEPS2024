import matplotlib.pyplot as plt
import pandas as pd

def plot_occupation(occupation, month, day, current_hour = 0):
    results = pd.DataFrame({
        'hour': range(24),
        'predicted_occupation': occupation
    })

    # Filter the DataFrame to include only hours from current_hour onward
    filtered_results = results[results['hour'] >= current_hour]

    plt.figure(figsize=(10, 6))

    # Create the histogram as a bar chart
    plt.bar(results['hour'], results['predicted_occupation'], width=0.8, color='skyblue', edgecolor='black')

    # Set the title and labels
    plt.title(f"Predicted Parking Occupancy for {month}/{day}")
    plt.xlabel('Hour of Day')
    plt.ylabel('Predicted Occupancy (%)')

    # Set grid, y-axis limits, and x-axis ticks to include all hours from 0 to 23
    plt.grid(axis='y', linestyle='--', alpha=0.7)
    plt.ylim(0, 100)
    plt.xticks(range(24))  # Ensures all hours are displayed on the x-axis

    # Display the plot
    plt.show()