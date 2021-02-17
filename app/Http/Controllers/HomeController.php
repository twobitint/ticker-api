<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function handle(Request $request)
    {
        $builder = Post::with('stocks')
            ->orderBy($request->query('sort', 'posted_at'), 'desc');

        if ($category = $request->query('category')) {
            $builder->whereIn('subcategory', config('categories.'.$category));
        }

        return view('home', [
            'posts' => $builder->paginate(),
        ]);
    }
}
