<?php

namespace App\Http\Controllers;

use App\Entry;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            if(!\Cache::has('entries.user.' . $user->id . '.page.' . \Request::get('page'))) {
                $entries = Entry::whereUserId($user->id)->paginate(30);
                \Cache::tags(['entries.user.' . $user->id])->put('entries.user.' . $user->id . '.page.' . \Request::get('page'), $entries);
            } else {
                $entries = \Cache::get('entries.user.' . $user->id . '.page.' . \Request::get('page'));
            }
            return view('home', ['entries' => $entries]);
        } else {
            return view('welcome');
        }
    }
}
