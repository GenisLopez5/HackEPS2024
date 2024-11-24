<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *     title="Parking API",
 *     version="1.0.0"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/parking",
 *     summary="Get all parking slots",
 *     tags={"Parking"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all parking slots"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No parking slots found"
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/parking/{id}",
 *     summary="Get specific parking slot",
 *     tags={"Parking"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         description="Parking slot ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking slot details"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing parameters"
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/parking",
 *     summary="Create new parking slot",
 *     tags={"Parking"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","address","city","state","zip","lat","lng","capacity","occupied"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="address", type="string"),
 *             @OA\Property(property="city", type="string"),
 *             @OA\Property(property="state", type="string"),
 *             @OA\Property(property="zip", type="string"),
 *             @OA\Property(property="lat", type="number", format="float"),
 *             @OA\Property(property="lng", type="number", format="float"),
 *             @OA\Property(property="capacity", type="integer"),
 *             @OA\Property(property="occupied", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Parking slot created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing parameters"
 *     )
 * )
 * 
 * @OA\Put(
 *     path="/api/parking/{id}",
 *     summary="Update parking slot",
 *     tags={"Parking"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Parking slot ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","address","city","state","zip","lat","lng","capacity","occupied"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="address", type="string"),
 *             @OA\Property(property="city", type="string"),
 *             @OA\Property(property="state", type="string"),
 *             @OA\Property(property="zip", type="string"),
 *             @OA\Property(property="lat", type="number", format="float"),
 *             @OA\Property(property="lng", type="number", format="float"),
 *             @OA\Property(property="capacity", type="integer"),
 *             @OA\Property(property="occupied", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking slot updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing parameters"
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/parking/{id}",
 *     summary="Delete parking slot",
 *     tags={"Parking"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Parking slot ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Parking slot deleted successfully"
 *     )
 * )
 */

use Illuminate\Http\Request;
use \App\Models\Parking;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //  Return all the parking slots
        if(Parking::all()->isEmpty()){
            return response()->json(['error' => 'No parking slots found'], 404);
        }
        return Parking::all();
    }

    public function oneParking(Request $request)
    {
        //  Return the parking slot with the given id
        // check that the id parameter is there, if not, 400
        if (!$request->has('id')) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }
        return Parking::find($request->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create a new parking slot
        // //   'name',
        // 'address',
        // 'city',
        // 'state',
        // 'zip',
        // 'lat',
        // 'lng',
        // 'capacity',
        // 'occupied',
        // check that all parameters are there, if not, 400
        if (!$request->has('name') || !$request->has('address') || !$request->has('city') || !$request->has('state') || !$request->has('zip') || !$request->has('lat') || !$request->has('lng') || !$request->has('capacity') || !$request->has('occupied')) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }
        $parking = new Parking();
        $parking->name = $request->name;
        $parking->address = $request->address;
        $parking->city = $request->city;
        $parking->state = $request->state;
        $parking->zip = $request->zip;
        $parking->lat = $request->lat;
        $parking->lng = $request->lng;
        $parking->capacity = $request->capacity;
        $parking->occupied = $request->occupied;
        $parking->save();

        return response()->json($parking, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update the parking slot with the given id
        // check that the id parameter is there, if not, 400
        if (!$request->has('id')) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }
        
        $parking = Parking::find($request->id);
        $parking->name = $request->name;
        $parking->address = $request->address;
        $parking->city = $request->city;
        $parking->state = $request->state;
        $parking->zip = $request->zip;
        $parking->lat = $request->lat;
        $parking->lng = $request->lng;
        $parking->capacity = $request->capacity;
        $parking->occupied = $request->occupied;
        $parking->save();

        return response()->json($parking, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete the parking slot with the given id
        $parking = Parking::find($id);
        $parking->delete();

        return response()->json(null, 204);
    }
}
