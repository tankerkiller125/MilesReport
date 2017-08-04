<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('/create', 'Web\CreateMilesLog@showCreate')->middleware('auth');
Route::post('/create', 'Web\CreateMilesLog@logMiles')->name('create')->middleware('auth');

Route::get('/update/{entry}', 'Web\UpdateMilesLog@getUpdate')->middleware('auth');
Route::post('/update/{entry}', 'Web\UpdateMilesLog@update')->name('update')->middleware('auth');

Route::get('/delete/{entry}', 'Web\DeleteMilesLog@getDelete')->middleware('auth');
Route::post('/delete/{entry}', 'Web\DeleteMilesLog@delete')->name('delete')->middleware('auth');

Route::get('/locations', 'Web\ListLocations@listLocations')->middleware('auth');
Route::get('/locations/create', function () {
    return view('locations.create');
})->middleware('auth');
Route::post('/locations/create', 'Web\CreateLocation@createLocation')->name('create-location')->middleware('auth');
Route::get('/locations/update/{location}', 'Web\UpdateLocation@getLocation')->middleware('auth');
Route::post('/locations/update/{location}', 'Web\UpdateLocation@updateLocation')->name('update-location')->middleware('auth');

Auth::routes();

Route::get('/settings', function () {
    return view('user.settings', ['user' => Auth::getUser()]);
})->middleware('auth');
Route::post('/settings')->name('settings')->middleware('auth');
