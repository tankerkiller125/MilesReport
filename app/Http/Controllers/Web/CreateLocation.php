<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;

class CreateLocation extends Controller
{
    public function createLocation(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'address' => 'required|string'
            ]);
            Location::create(['name' => $request->input('name'), 'address' => $request->input('address')]);
            return redirect()->back()->withErrors(['success' => 'Created location successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Could not create location']);
        }
    }
}
