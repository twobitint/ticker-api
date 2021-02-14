<?php

namespace App\Http\Controllers;

use App\Imports\PositionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function handlePositions(Request $request)
    {
        if ($request->hasFile('positions')) {
            Excel::import(new PositionsImport, $request->file('positions'));
        }

        return redirect()->route('home');
    }
}
