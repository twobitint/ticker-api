<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home', [
        'posts' => App\Models\Post::latest()->paginate(),
    ]);
});

Route::get('/cat/{cat}', function ($cat) {
    return view('home', [
        'posts' => App\Models\Post::whereIn('subcategory', config('categories.'.$cat))
            ->latest()
            ->paginate(),
    ]);
})->name('cat');
