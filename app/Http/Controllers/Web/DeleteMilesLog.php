<?php

namespace App\Http\Controllers\Web;

use App\Entry;
use App\Http\Controllers\Controller;

class DeleteMilesLog extends Controller
{
    public function getDelete(Entry $entry)
    {
        return view('entries.delete', ['entry' => $entry]);
    }

    public function delete(Entry $entry)
    {
        if (\Auth::user()->id = $entry->user_id) {
            $entry->delete();
            return redirect()->withErrors(['success' => 'Entry deleted successfully']);
        } else {
            return redirect()->back()->withErrors(['error' => 'You do not have access to do that']);
        }
    }
}
