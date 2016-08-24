<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Product;
use App\Repositories\Product\ProductRepository;
use App\Search\TokenGenerator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsController extends Controller
{
    use \App\Traits\TrashesModels;

    /**
     * @var \App\Repositories\Product\ProductRepository
     */
    private $products;

    private $tokenGenerator;

    /**
     * Create a new ProductsController instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(ProductRepository $products, TokenGenerator $tokenGenerator)
    {
        $this->products = $products;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Show a list of all the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['media', 'product_categories'])->orderBy('updated_at', 'desc')->paginate();

        return view('admin.products.index', [
            'products'     => $products,
            'productCount' => $this->products->count(),
        ]);
    }

    /**
     * Perform a search for products and display the results.
     * Here we are just extracting the IDs of the result and querying the products from the DB.
     * We will have a more snappy JS-powered search on the front end.
     *
     * @param Request         $request
     * @param ProductSearcher $searcher
     *
     * @return Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $results = Product::search($query)->get()->load('product_categories');
        $products = new LengthAwarePaginator($results, $results->count(), config('shop.products_per_page'));


        return view('admin.products.index')->with([
            'products' => $products,
            'productCount' => $products->count(),
            'title'     => sprintf('Product search results for "%s"', $query)
            ]);
    }

    /**
     * Show the page for creating a new product.
     *
     * @param Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        return view('admin.products.create')->with(compact('product'));
    }

    /**
     * Create a product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $product = Product::create($request->all());

        $product->syncTerms($request->get('terms', []));
        $product->syncAttributes($request->get('attributes', []));

        return redirect()->route('admin.products.edit', $product)
                         ->withAlert('Product Saved')
                         ->with('alert-class', 'success');
    }

    /**
     * Show the page for editing a product.
     *
     * @param Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $selected_product_categories = $product->product_categories->pluck('id');

        return view('admin.products.edit')->with(compact('product', 'selected_product_categories', 'attributes'));
    }

    /**
     * Update a product in storage.
     *
     * @param Product              $product
     * @param UpdateProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        $product->update($request->all());

        $product->syncTerms($request->get('terms', []));
        $product->syncAttributes($request->get('attributes', []));

        return redirect()->route('admin.products.edit', $product)
                         ->withAlert('Product Updated')
                         ->with('alert-class', 'success');
    }

    /**
     * Show the trash page for products.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash()
    {
        $products = Product::onlyTrashed()->latest()->paginate(10);
        $title = 'Trashed Products';
        $productCount = $this->products->count();

        return view('admin.products.index')->with(compact('products', 'title', 'productCount'));
    }
}
