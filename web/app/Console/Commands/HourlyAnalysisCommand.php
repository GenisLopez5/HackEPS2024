<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HourlyAnalysisCommand extends Command
{
    // Command signature
    protected $signature = 'call:hourly-analysis';

    // Description of the command
    protected $description = 'Call a local Python script with parameters every hour';

    // Execute the command
    public function handle()
    {
        // Assume you have a collection of parkings to loop through
        $parkings = Parking::all(); // Or however you retrieve your list of parkings

        // Loop through each parking
        foreach ($parkings as $parking) {
            // Set parameters based on parking data
            $parameter1 = $parking->id;
            $parameter2 = $parking->occupied / $parking->capacity;

            // Construct the shell command to call the Python script
            $pythonScript = base_path('DataAnalysis/hourly.py');
            $command = "python3 {$pythonScript} {$parameter1} {$parameter2}";

            // Execute the command
            $output = shell_exec($command);

            // Optionally, display the output in the console
            $this->info("Python script executed for Parking ID: $parameter1. Output: " . $output);
        }
    }
}