<?php

namespace App\Listeners;

use App\User;
use App\Product;
use App\Mail\ProductStockLow;
use App\Mail\ProductOutOfStock;
use App\Events\ProductStockChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailStockNotification implements ShouldQueue
{
  /**
   * Handle the event.
   *
   * @param ProductStockChanged $event
   *
   * @return void
   */
  public function handle(ProductStockChanged $event)
  {
    $product = $event->product->fresh();
    if ($this->isLowInStock($product)) {
      $mailable = $this->getStockMailable($product);
      foreach (User::shopAdmins()->get() as $admin) {
        \Mail::to($admin)->send($mailable);
      }
    }
  }

  protected function isLowInStock(Product $product)
  {
    return $product->stock_qty <= config('shop.low_stock_qty');
  }

  protected function getStockMailable(Product $product)
  {
    if ($product->stock_qty == 0) {
      return new ProductOutOfStock($product);
    }

    return new ProductStockLow($product);
  }
}
