<?php

namespace App\Http\Controllers;

use App\Repositories\Product\ProductRepository;
use App\Term;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopController extends Controller
{
    private $products;

    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Term $product_category)
    {
        $products = $this->products->shopProducts($product_category);

        return view('shop.index')->with(compact('product_category', 'products'));
    }

    /**
     * Perform a search for products and display the results.
     * Here we are just extracting the IDs of the result and querying the products from the DB.
     * We will have a more snappy JS-powered search on the front end.
     *
     * @param Request         $request
     *
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $product_category = new Term();

        $results = \App\Product::search($request->get('query'))
            ->where('listed', true)
            ->get()
            ->load('product_categories');

        // filter out-of-stock products if needed
        if (!config('shop.show_out_of_stock')) {
            $results = $results->filter(function ($product) {
                return $product->inStock();
            });
        }

        $products = new LengthAwarePaginator($results, $results->count(), config('shop.products_per_page'));

        return view('shop.index')->with(compact('product_category', 'products'));
    }
}
