<?php

namespace App\Http\Controllers;

use App\Entry;

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
            $entries = \Cache::remember('entries.user.'.$user->id.'.page.'.\Request::get('page'), 1, function () use ($user) {
                return Entry::whereUserId($user->id)->orderBy('created_at', 'asc')->paginate(30);
            });

            return view('home', ['entries' => $entries]);
        } else {
            return view('welcome');
        }
    }
}
