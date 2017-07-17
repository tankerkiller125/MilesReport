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
    Route::post('/')->middleware('scope:create-entry'); // Creates Entry
    Route::post('/{entry}')->middleware('scope:update-entry'); // Updates Entry
    Route::delete('/{entry}')->middleware('scope:delete-entry'); // Delete Entry
});

Route::group(['prefix' => '/locations', 'middleware' => 'auth:api'], function () {
    Route::get('/'); // Get locations
    Route::post('/')->middleware('scope:create-location'); // Create Location
    Route::post('/{location}')->middleware('scope:update-location'); // Update Location
    Route::delete('/{location}')->middleware('scope:delete-location'); // Delete Location
});