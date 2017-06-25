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

Route::get('/update/{id}', 'Web\UpdateMilesLog@getUpdate');
Route::post('/update/{id}', 'Web\UpdateMilesLog@update');

Route::get('/test', function() {
    dd(\App\Entry::whereId(1)->first()->fromLocation->name);
});

Auth::routes();
