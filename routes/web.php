<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WatchlistController;
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

    Route::get('/', [HomeController::class, 'handle'])->name('home');

    Route::view('/watchlist', 'watchlist')->name('watchlist');

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

    // Form Routes.
    Route::post('user/upload-positions', [UploadController::class, 'handlePositions'])
        ->name('upload-positions');

    Route::get('user/watchlist/{stock:symbol}/add', [WatchlistController::class, 'handleAdd'])
        ->name('watchlist.add');
    Route::get('user/watchlist/{stock:symbol}/remove', [WatchlistController::class, 'handleRemove'])
        ->name('watchlist.remove');

    Route::get('search', [SearchController::class, 'handle'])->name('search');

    Route::get('test', function () {
        App\Models\Stock::trending('positions');
        // $builder = App\Models\Post::with('stocks')
        //     ->orderBy('posted_at', 'desc');

        // $posts = $builder->paginate();
        // $posts->first()->stocks->first();
    });
});
