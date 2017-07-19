<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Location;

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
