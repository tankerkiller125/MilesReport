<?php

namespace App\Http\Controllers\Web;

use App\Entry;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;

class UpdateMilesLog extends Controller
{
    /**
     * Get entry data and return view
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
     * Update database entries
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
                    'created_at' => ['required', 'date_format:"Y-m-d H:i:s"']
                ]);
                $entry->from = $request->input('from');
                $entry->to = $request->input('to');
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
}
