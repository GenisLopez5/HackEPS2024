<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataExporterController extends Controller
{
    public function export()
    {
        $parkings = \App\Models\Parking::all();
        $filename = "parkings.csv";
        $handle = fopen($filename, 'w+');
        // 

        return response()->download($filename, 'parkings.csv', $headers);
    }
}
