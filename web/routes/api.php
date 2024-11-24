<?php

use App\Http\Controllers\CotxesController;
use App\Http\Controllers\ParkingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/cotxes/enter', [CotxesController::class, 'enter']);
Route::post('/cotxes/exit', [CotxesController::class, 'exit']);


Route::get('/parking', [ParkingController::class, 'index']);
Route::post('/parking', [ParkingController::class, 'store']);
Route::get('/parking/{id}', [ParkingController::class, 'oneParking']);
Route::put('/parking/{id}', [ParkingController::class, 'update']);
Route::delete('/parking/{id}', [ParkingController::class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
