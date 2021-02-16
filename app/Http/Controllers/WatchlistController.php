<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function handleAdd(Request $request, Stock $stock)
    {
        $user = $request->user();
        $user->stocksInWatchlist()->attach($stock->id);
        return redirect()->route('watchlist');
    }

    public function handleRemove(Request $request, Stock $stock)
    {
        $user = $request->user();
        $user->stocksInWatchlist()->detach($stock->id);
        return redirect()->route('home');
    }
}
