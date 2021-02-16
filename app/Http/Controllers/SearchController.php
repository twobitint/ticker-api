<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function handle(Request $request)
    {
        $stock = Stock::where('symbol', '=', $request->input('symbol'))->first();

        if ($stock) {
            return redirect()->route('stock', $stock);
        }

        return redirect()->route('home');
    }
}
