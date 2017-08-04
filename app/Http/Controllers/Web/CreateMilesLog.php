<?php

namespace App\Http\Controllers\Web;

use App\Entry;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\RedirectResponse;

class CreateMilesLog extends Controller
{
    public function showCreate()
    {
        $locations = Location::all(['id', 'name']);

        return view('entries.create', ['locations' => $locations]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logMiles(Request $request)
    {
        $this->validate($request, [
            'from' => ['required'/*'exists:locations,id'*/],
            'to' => ['required', 'different:from', 'exists:locations,id'],
            'mpg' => 'nullable|integer',
        ]);
        $distance = $this->getDistance(Location::whereId(\Request::input('from'))->first(), Location::whereId(\Request::input('to'))->first());
        Entry::create([
            'user_id' => \Auth::user()->id,
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'distance' => $distance->distance,
            'time' => $distance->time,
            'mpg' => $request->input('mpg'),
        ]);
        \Cache::forget('entries.user.'.\Auth::user()->id.'.page.'.\Request::get('page'));

        return redirect()->back()->withErrors(['success' => 'Entry Added']);
    }

    /**
     * Get distance and time from Google Distance Matrix API.
     *
     * @param Location $origin
     * @param Location $destination
     * @return object
     */
    public function getDistance(Location $origin, Location $destination)
    {
        $httpClient = new HttpClient();
        $response = $httpClient->get('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins='.$origin->address.'&destinations='.$destination->address.'&key='.config('services.google.key'));
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 400) {
            // Return an error if we can not access google
            return abort(500, 'We could not get the distance information from google. Did you put your google API key in the config? And did you enable the Distance Matrix API?');
        }
        // This is the raw data from Google converted to json
        $rawData = (object) \GuzzleHttp\json_decode($response->getBody());

        // This is the raw miles (meters to miles)
        $cleanDistance = $rawData->rows[0]->elements[0]->distance->value / 1609.344;
        // This is the seconds converted to minutes
        $cleanTime = $rawData->rows[0]->elements[0]->duration->value / 60;
        // Return the distance value
        return (object) ['distance' => (float) substr($cleanDistance, 0, 4), 'time' => (int) $cleanTime];
    }
}
