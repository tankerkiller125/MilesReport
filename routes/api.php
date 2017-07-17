<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/entries', 'middleware' => 'auth:api'], function () {
    Route::get('/'); // Fetch Entries
    Route::post('/'); // Creates Entry
    Route::post('/{id}'); // Updates Entry
    Route::delete('/{id}'); // Delete Entry
});

Route::group(['prefix' => '/locations', 'middleware' => 'auth:api'], function () {
    Route::get('/'); // Get locations
    Route::post('/'); // Create Location
    Route::post('/{location}'); // Update Location
    Route::delete('/{location}'); // Delete Location
});