<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\StockResource;
use App\Models\Post;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('posts', function (Request $request) {
//     return PostResource::collection(Post::orderBy('posted_at', 'desc')->paginate());
// });

// Route::get('stock/{symbol}', function (Request $request, string $symbol) {
//     return new StockResource(Stock::where('symbol', $symbol)->first());
// });
