<?php

use App\Http\Controllers\CotxesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




// alias of /

Route::get('/parking/map', [CotxesController::class, 'mapIndex'])->name('map.index');

Route::redirect('/', '/parking');
Route::get('/parking', [CotxesController::class, 'index'])->name('parkings');

Route::get('/recalc', [CotxesController::class, 'createHardcodedParking']);

Route::get('/parking/{pkid}', [CotxesController::class, 'getOneParking'])->name('parking');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
