<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Unprotected stuff.
Route::get('welcome', function () {
    if (!Auth::check()) {
        return view('welcome');
    }
    return redirect()->route('home');
})->name('welcome');
Route::get('terms', function () {
    return view('terms');
})->name('terms');
Route::get('privacy', function () {
    return view('privacy');
})->name('privacy');
Route::get('login', function () {
    return Socialite::driver('google')->redirect();
})->name('login');
Route::get('login/google/redirect', function () {
    $google = Socialite::driver('google')->user();

    $user = User::firstOrNew(['email' => $google->email]);

    $user->name = $google->nickname ?? $google->name;
    $user->picture_url = $google->avatar;
    $user->google_id = $google->id;
    $user->save();

    Auth::login($user);
    return redirect()->route('home');
});

// Protect all the app routes.
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('home', [
            'posts' => App\Models\Post::latest()->paginate(),
        ]);
    })->name('home');

    Route::get('/cat/{cat}', function ($cat) {
        return view('home', [
            'posts' => App\Models\Post::whereIn('subcategory', config('categories.'.$cat))
                ->latest()
                ->paginate(),
        ]);
    })->name('cat');

    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('welcome');
    });

});
