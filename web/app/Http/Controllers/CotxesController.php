<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class CotxesController extends Controller
{

    public function index(){
        $parkings = Parking::all();
        // create a new value that is % of occupied
        foreach ($parkings as $parking) {
            $parking->occupied_percentage = $parking->occupied / $parking->capacity * 100;
        }
        // order them by occupied rate min to max
        $parkings = $parkings->sortBy('occupied_percentage');
        return view('parkings', compact('parkings'));
    }
 
    public function getOneParking($pkid){
        $parking = Parking::find($pkid);
        // if parking is not found, return 404
        if (!$parking) {
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }
        return view('parking', compact('parking'));
    }
    // increment parking occupied if there is space
    
    public function enter(Request $request){
        $parkingId = $request->input('parking_id');
        $parking = Parking::find($parkingId);
        if(!$parking){
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }
        if ($parking->occupied < $parking->capacity) {
            $parking->occupied++;
            $parking->save();
            return response()->json(['success' => true, 'message' => 'Car entered successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Parking is full']);
        }
    }

    public function exit(Request $request){
        $parkingId = $request->input('parking_id');
        $parking = Parking::find($parkingId);
        if(!$parking){
            return response()->json(['success' => false, 'message' => 'Parking not found'], 404);
        }
        if ($parking->occupied > 0) {
            $parking->occupied--;
            $parking->save();
            return response()->json(['success' => true, 'message' => 'Car exited successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Parking is empty']);
        }
    }

    // public function createHarcodedParking(){
    //     $parking = new Parking();
    //     $parking->name = 'Free Public Parking UdL';
    //     $parking->address = 'Av. de I\'Estudi General, 4';
    //     $parking->city = 'Lleida';
    //     $parking->state = 'Catalunya';
    //     $parking->zip = '25001';
    //     $parking->capacity = 10;
    //     $parking->occupied = 0;
    //     $parking->lat = 41.60685567656295;
    //     $parking->lng = 0.6256165340329318;
    //     $parking->save();
    // }


}