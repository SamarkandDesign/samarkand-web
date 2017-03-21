<?php

// id	title	description	google product category	product type	link	image link	condition	availability	price	sale price	sale price effective date	gtin	brand	mpn	item group id	gender	age group	color	size	shipping	shipping weight


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepository;

class ProductsController extends Controller
{
  public function feed(ProductRepository $productRepo)
  {
    $products = $productRepo->all(['media', 'product_categories', 'attribute_properties']);

    $consts = [
      'site_url' => config('app.url'),
      'shop_name' => config('shop.name'),
      'currency' => config('shop.currency'),
    ];

    $keys = collect([
      'id',
      'title',
      'description',
      'link',
      'image_link',
      'availability',
      'price',
      'mpn',
      'condition',
      'brand',
      'color',
      'size',
    ]);

    if (!$products->count()) {
      return response($keys->implode("\t"), 200)->header('Content-Type', 'text/plain');
    }

    $data = $products
    ->filter(function($p) { return $p->inStock(); })
    ->map(function($p) use ($consts, $keys) {
      $image = $p->media->count() ? $p->media->first()->getUrl('wide') : '';

      return $keys->combine([
        $p->sku,
        $p->name,
        $p->description,
        sprintf('%s%s', $consts['site_url'], $p->url),
        $image,
        'in stock',
        sprintf('%s %s', $p->price->asDecimal(), $consts['currency']),
        $p->sku,
        'new',
        $consts['shop_name'],
        $this->getProductProperties($p, 'colour'),
        $this->getProductProperties($p, 'size'),
      ]);
    });

    return response($this->generateFeedText($data), 200)->header('Content-Type', 'text/plain');
  }

  protected function generateFeedText ($data) {
    return $data
      ->prepend($data->first()->keys())
      ->map(function($row) { return $row->implode("\t"); })
      ->implode(PHP_EOL);
  }

  protected function getProductProperties($product, $attribute_slug) {
    return $product->attribute_properties->filter(function($prop) use ($attribute_slug) {
      return $prop->product_attribute->slug == $attribute_slug;
    })->map(function ($p) {
      return $p->name;
    })->implode(',');
  }
}