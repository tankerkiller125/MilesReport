<?php

namespace App\Http\Controllers\Web;

use App\Location;
use App\Http\Controllers\Controller;

class ListLocations extends Controller
{
    public function listLocations()
    {
        $locations = \Cache::remember('locations.list', 60, function () {
            return Location::all();
        });

        return view('locations.list', ['locations' => $locations]);
    }
}
