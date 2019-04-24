<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Product;

class GenerateProductFeed extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'product_feed:generate';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate a csv of products';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(Product $product)
  {
    parent::__construct();
    $this->product = $product;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
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
    $consts = [
      'site_url' => config('app.url'),
      'shop_name' => config('shop.name'),
      'currency' => config('shop.currency'),
    ];

    $products = $this->product
      ->where('listed', true)
      ->where('stock_qty', '>', 0)
      ->with(['media', 'product_categories', 'attribute_properties'])
      ->get()
      ->map(function ($p) use ($keys, $consts) {
        $image = $p->media->count() ? $p->media->first()->getUrl() : '';
        return $keys->combine([
          $p->sku,
          $p->name,
          str_replace(["\n", "\r"], ' ', strip_tags($p->description)),
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
      })
      ->toJson();

    $filename = 'public/product_feed.json';

    Storage::put($filename, $products);
    $url = Storage::url($filename);
    $this->info("Product feed saved to $url");
    // $text = $this->generateFeedText($products);
  }

  protected function getProductProperties($product, $attribute_slug)
  {
    return $product->attribute_properties
      ->filter(function ($prop) use ($attribute_slug) {
        return $prop->product_attribute->slug == $attribute_slug;
      })
      ->map(function ($p) {
        return $p->name;
      })
      ->implode(',');
  }
}
