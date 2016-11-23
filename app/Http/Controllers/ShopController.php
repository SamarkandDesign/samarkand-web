<?php

namespace App\Http\Controllers;

use App\Pagination\Paginator;
use App\Repositories\Product\ProductRepository;
use App\Term;
use Illuminate\Http\Request;

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
     * @param Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $product_category = new Term();
        $showNoStock = config('shop.show_out_of_stock');
        $results = $this->products->search($request->get('query'))
                                 ->filter(function ($product) use ($showNoStock) {
                                     return $product->listed and ($showNoStock or $product->inStock());
                                 });

        $products = (new Paginator($request))->make($results);

        return view('shop.index')->with(compact('product_category', 'products'));
    }
}
