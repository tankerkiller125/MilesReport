<?php

namespace App\Http\Controllers\Web;

use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateLocation extends Controller
{
    public function getLocation(Location $location)
    {
        return view('locations.update', ['location' => $location]);
    }

    public function updateLocation(Request $request, Location $location)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
        ]);
        $location->name = $request->input('name');
        $location->address = $request->input('address');
        $location->save();
        \Cache::forget('locations.list');

        return redirect('/locations')->withErrors(['success' => 'Location updated successfully']);
    }
}
