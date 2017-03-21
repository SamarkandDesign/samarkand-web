<?php

// id	title	description	google product category	product type	link	image link	condition	availability	price	sale price	sale price effective date	gtin	brand	mpn	item group id	gender	age group	color	size	shipping	shipping weight

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;
use Illuminate\Cache\Repository as CacheRepository;

class ProductsController extends Controller
{
    protected $products;
    protected $cache;
    protected $keys;
    protected $consts;

    public function __construct(ProductRepository $products, CacheRepository $cache)
    {
        $this->products = $products;
        $this->cache = $cache;
        $this->keys = collect(['id', 'title', 'description', 'link', 'image_link', 'availability', 'price', 'mpn', 'condition', 'brand', 'color', 'size']);
    }

    public function feed()
    {
        $consts = [
      'site_url'  => config('app.url'),
      'shop_name' => config('shop.name'),
      'currency'  => config('shop.currency'),
    ];

        $data = $this->cache->remember('product-feed-text', Carbon::now()->addHours(23), function () use ($consts) {
            $products = $this->products->all(['media', 'product_categories', 'attribute_properties']);

            if (!$products->count()) {
                return $this->keys->implode("\t");
            }

            $data = $products
            ->filter(function ($p) {
                return $p->listed and $p->inStock();
            })
            ->map(function ($p) use ($consts) {
                $image = $p->media->count() ? $p->media->first()->getUrl('wide') : '';

                return $this->keys->combine([
                $p->sku,
                $p->name,
                sprintf('"%s"', str_replace(["\n", "\r"], ' ', strip_tags($p->description))),
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

            return $this->generateFeedText($data);
        });

        return response($data, 200)->header('Content-Type', 'text/plain');
    }

    protected function generateFeedText($data)
    {
        return $data
      ->prepend($data->first()->keys())
      ->map(function ($row) {
          return $row->implode("\t");
      })
      ->implode(PHP_EOL);
    }

    protected function getProductProperties($product, $attribute_slug)
    {
        return $product->attribute_properties->filter(function ($prop) use ($attribute_slug) {
            return $prop->product_attribute->slug == $attribute_slug;
        })->map(function ($p) {
            return $p->name;
        })->implode(',');
    }
}
