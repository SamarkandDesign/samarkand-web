<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Product;

/**
 * Convert a multi-dimensional, associative array to CSV data
 * @param  array $data the array of data
 * @return string       CSV text
 */
function str_putcsv($data)
{
  # Generate CSV data from array
  $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
  # to use memory instead

  # write out the headers
  fputcsv($fh, array_keys(current($data)));

  # write out the data
  foreach ($data as $row) {
    fputcsv($fh, $row);
  }
  rewind($fh);
  $csv = stream_get_contents($fh);
  fclose($fh);

  return $csv;
}

class GenerateProductFeed extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'product-feed:generate';

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
      ->toArray();

    $data = str_putcsv($products);

    $filename = 'public/product_feed.csv';

    Storage::put($filename, $data);
    $url = Storage::url($filename);
    $this->info("Product feed saved to $url");
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
