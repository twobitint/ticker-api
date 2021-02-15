<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
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

Route::view('welcome', 'welcome')->name('welcome');
Route::view('terms', 'terms')->name('terms');
Route::view('privacy', 'privacy')->name('privacy');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('login/google/redirect', [AuthController::class, 'redirect']);

// Protect all the app routes.
Route::middleware('auth')->group(function () {

    Route::view('/', 'home', [
        'posts' => App\Models\Post::with('stocks')
            ->orderBy('posted_at', 'desc')
            ->paginate(),
    ])->name('home');

    Route::get('stock/{stock:symbol}', function (App\Models\Stock $stock) {
        return view('stock', [
            'stock' => $stock,
        ]);
    })->name('stock');

    Route::get('/cat/{cat}', function ($cat) {
        return view('home', [
            'posts' => App\Models\Post::whereIn('subcategory', config('categories.'.$cat))
                ->orderBy('posted_at', 'desc')
                ->paginate(),
        ]);
    })->name('cat');

    Route::get('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::post('user/upload-positions', [UploadController::class, 'handlePositions'])
        ->name('upload-positions');
});
