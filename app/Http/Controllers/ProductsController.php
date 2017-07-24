<?php

namespace App\Http\Controllers;

use App\Term;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a single product page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Term $product_category, Product $product, Request $request)
    {
        $shareUrl = urlencode($request->url());

        return view('products.show', compact('product', 'shareUrl'));
    }
}
