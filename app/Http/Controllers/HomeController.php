<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
    	$featured_products = \App\Product::with('media')
            ->listed()
            ->inStock()
            ->orderBy('featured', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->take(10)
            ->get();

        return view('home', compact('featured_products'));
    }
}
