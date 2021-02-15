<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }

    public function login()
    {
        // Generate a fake local user.
        if (config('app.env') == 'local') {
            $user = User::firstOrNew(['email' => 'test@example.com']);
            $user->name = 'Emma Fake';
            $user->picture_url = 'https://randomuser.me/api/portraits/thumb/women/75.jpg';
            $user->save();
            Auth::login($user);
            return redirect()->route('home');
        }
        // Use actual google auth.
        return Socialite::driver('google')->redirect();
    }

    public function redirect()
    {
        $google = Socialite::driver('google')->user();

        $user = User::firstOrNew(['email' => $google->email]);

        $user->name = $google->nickname ?? $google->name;
        $user->picture_url = $google->avatar;
        $user->google_id = $google->id;
        $user->save();

        Auth::login($user);
        return redirect()->route('home');
    }
}
