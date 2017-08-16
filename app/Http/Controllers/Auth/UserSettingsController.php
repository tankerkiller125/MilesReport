<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserSettingsController extends Controller
{
    public function getUser()
    {
        return view('user.settings', ['user' => \Auth::getUser()]);
    }

    public function updateUser(Request $request)
    {
        $this->validate($request, [
            'username' => 'string,nullable',
            'report_schedule' => 'integer,nullable',
            'old_password' => 'nullable',
            'new_password' => '',
        ]);
        $user = \Auth::getUser();
        if ($request->input('username')) {
            $user->name = $request->input('username');
        }
        if ($request->input('report_schedule')) {
            $user->report_schedule = $request->input('report_schedule');
        }
        $user->save();
        if ($request->input('old_password') && $request->input('new_password')) {
            $this->updatePassword(\Auth::getUser(), $request->input('old_user'), $request->input('new_password'));
        }
    }

    private function updatePassword(User $user, $oldPassword, $newPassword)
    {
        if (\Hash::check($oldPassword, $user->password)) {
            $user->password = \Hash::make($newPassword);
            $user->save();

            return true;
        } else {
            return false;
        }
    }
}
