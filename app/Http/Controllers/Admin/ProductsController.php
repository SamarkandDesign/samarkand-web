<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Illuminate\Http\Request;
use App\Pagination\Paginator;
use App\Search\TokenGenerator;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

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
    $products = Product::with(['media', 'product_categories'])
      ->orderBy('updated_at', 'desc')
      ->paginate();

    return view('admin.products.index', [
      'products' => $products,
      'productCount' => $this->products->count(),
    ]);
  }

  /**
   * Perform a search for products and display the results.
   *
   * @param Request $request
   *
   * @return Illuminate\Http\Response
   */
  public function search(Request $request)
  {
    $query = $request->get('query');

    $products = (new Paginator($request))->make($this->products->search($query));

    return view('admin.products.index')->with([
      'products' => $products,
      'productCount' => $products->count(),
      'title' => sprintf('Product search results for "%s"', $query),
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

    return redirect()
      ->route('admin.products.edit', $product)
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

    return view('admin.products.edit')->with(
      compact('product', 'selected_product_categories', 'attributes')
    );
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

    return redirect()
      ->route('admin.products.edit', $product)
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
    $title = 'Trashed Products';
    $products = Product::onlyTrashed()
      ->latest()
      ->paginate(10);
    $productCount = $this->products->count();

    return view('admin.products.index')->with(compact('products', 'title', 'productCount'));
  }
}
