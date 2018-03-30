<?php

namespace App\Http\Controllers;

use App\Post;

class PostsController extends Controller
{
	public function index()
	{
		$posts = Post::with('media')
			->where('status', 'published')
			->orderBy('created_at', 'DESC')
			->paginate(10);

		return view('posts.index', ['posts' => $posts]);
	}

    /**
     * Show a post as derived from a string of slugs.
     *
     * @return Response
     */
    public function show($year, $month, $slug)
    {
    	$post = Post::where('slug', $slug)
    		->where('status', 'published')
    		->whereMonth('created_at', $month)
    		->whereYear('created_at', $year)
    		->firstOrFail();

        return view('posts.show', ['post' => $post]);
    }
}
