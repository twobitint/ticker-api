<?php

namespace App\Http\Controllers;

use App\Models\Mention as Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function handle(Request $request)
    {
        $builder = Post::with('stocks')
            ->where('type', 'post')
            ->orderBy($request->query('sort', 'posted_at'), 'desc');

        if ($category = $request->query('category')) {
            $builder->whereIn('category', config('categories.'.$category));
        }

        return view('home', [
            'posts' => $builder->paginate(),
        ]);
    }
}
