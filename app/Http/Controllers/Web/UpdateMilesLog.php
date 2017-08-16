<?php

namespace App\Http\Controllers\Web;

use App\Entry;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client as HttpClient;

class UpdateMilesLog extends Controller
{
    /**
     * Get entry data and return view.
     *
     * @param Entry $entry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getUpdate(Entry $entry)
    {
        if (\Auth::user()->id = $entry->user_id) {
            return view('entries.update', ['entry' => $entry, 'locations' => Location::all()]);
        } else {
            return redirect()->back()->withErrors(['error' => 'You do not have access to that']);
        }
    }

    /**
     * Update database entries.
     *
     * @param Request $request
     * @param Entry $entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Entry $entry)
    {
        if (\Auth::user()->id = $entry->user_id) {
            try {
                $this->validate($request, [
                    'from' => ['required', 'exists:locations,id'],
                    'to' => ['required', 'different:from', 'exists:locations,id'],
                    'mpg' => ['nullable', 'integer'],
                    'created_at' => ['required', 'date_format:"Y-m-d H:i:s"'],
                ]);
                $origin = Location::whereId($request->input('from'))->first(['id', 'address']);
                $destination = Location::whereId($request->input('to'))->first(['id', 'address']);
                $distance = $this->getDistance($origin, $destination);
                $entry->from = $origin->id;
                $entry->to = $destination->id;
                $entry->distance = $distance->distance;
                $entry->time = $distance->time;
                $entry->mpg = $request->input('mpg');
                $entry->created_at = $request->input('created_at');
                $entry->save();

                return redirect()->back()->withErrors(['success' => 'Log entry successfully updated']);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Log entry failed to update']);
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'You do not have access to do that']);
        }
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
