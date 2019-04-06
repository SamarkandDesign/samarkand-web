<?php

namespace App\Listeners;

use App\Product;
use App\Events\OrderWasPaid;
use App\Events\ProductStockChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReduceProductStock implements ShouldQueue
{
  /**
   * Handle the event.
   *
   * @param OrderWasCompleted $event
   *
   * @return void
   */
  public function handle(OrderWasPaid $event)
  {
    foreach ($event->order->product_items as $item) {
      \Log::info(sprintf('Reducing product stock by %s', $item->quantity), [
        'product_id' => $item->orderable->id,
        'amount' => $item->quantity,
      ]);
      $this->reduceStock($item->orderable, $item->quantity);
    }
  }

  /**
   * Reduce the stock on a product.
   *
   * @param Product $product
   * @param int     $quantity
   *
   * @return bool
   */
  private function reduceStock(Product $product, $quantity)
  {
    $product->stock_qty -= $quantity;

    event(new ProductStockChanged($product));

    return $product->save();
  }
}
