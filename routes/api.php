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
    Route::get('/', function (Request $request) {
        return response()->json(\App\Entry::whereUserId(Auth::user()->id)->paginate(($request->has('perPage') ? $request->get('perPage') : 15)));
    }); // Fetch Entries
    Route::post('/')->middleware('scope:create-entry'); // Creates Entry
    Route::post('/{entry}')->middleware('scope:update-entry'); // Updates Entry
    Route::delete('/{entry}')->middleware('scope:delete-entry'); // Delete Entry
});

Route::group(['prefix' => '/locations', 'middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        $locations = \Cache::remember('locations.list', 60, function () {
            return \App\Location::all();
        });
        return response()->json($locations);
    }); // Get locations
    Route::post('/')->middleware('scope:create-location'); // Create Location
    Route::post('/{location}')->middleware('scope:update-location'); // Update Location
    Route::delete('/{location}')->middleware('scope:delete-location'); // Delete Location
});

Route::get('/distance', function(Request $request) {
    if($request->has('from') && $request->has('to')) {
        try {
            $origin = \App\Location::whereId($request->get('from'))->first();
            $destination = \App\Location::whereId($request->get('to'))->first();
            $httpClient = new HttpClient();
            $response = $httpClient->get('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $origin->address . '&destinations=' . $destination->address . '&key=' . config('services.google.key'));
            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 400) {
                // Return an error if we can not access google
                return abort(500, 'We could not get the distance information from google. Did you put your google API key in the config? And did you enable the Distance Matrix API?');
            }
            // This is the raw data from Google converted to json
            $rawData = (object)\GuzzleHttp\json_decode($response->getBody());

            // This is the raw miles (meters to miles)
            $cleanDistance = $rawData->rows[0]->elements[0]->distance->value / 1609.344;
            // This is the seconds converted to minutes
            $cleanTime = $rawData->rows[0]->elements[0]->duration->value / 60;
            // Return the distance value
            return response()->json(['code' => 200, 'message' => 'Distance returned', 'data' => ['distance' => (float)substr($cleanDistance, 0, 4), 'time' => (integer)$cleanTime]]);
        } catch (Exception $e) {
            return response()->json([code => 500, 'message' => $e->getMessage()], 500);
        }
    } else {
        return response()->json(['code' => 400, 'message' => 'This endpoint needs a "to" parameter and a "from" parameter'], 500);
    }
})->middleware('auth:api');