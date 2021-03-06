<?php

namespace App;

use App\Values\Price;
use Illuminate\Database\Eloquent\Model;

/**
 * An item belonging to an order. Can relate to a product or a shipping item.
 */
class OrderItem extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  public $table = 'order_items';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $fillable = ['order_id', 'quantity', 'description', 'price_paid'];

  /**
   * Make a new Order Item which is associated with a product.
   *
   * @param Product $product  The product that the item is associated with
   * @param int     $quantity The quantity of the product in the order item
   *
   * @return OrderItem
   */
  public static function forProduct(Product $product, $quantity = 1)
  {
    $item = new static([
      'quantity' => $quantity,
      'description' => $product->name,
      'price_paid' => $product->getPrice()->value(),
    ]);
    $item->orderable()->associate($product);

    return $item;
  }

  /**
   * Get the price paid of an order item.
   *
   * @return Price
   */
  public function getPricePaidAttribute($price)
  {
    return new Price($price);
  }

  /**
   * An OrderItem belongs to an order.
   *
   * @return Illuminate\Database\Eloquent\Relations\Relation
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * An order item is related to an orderable entity, e.g. a product.
   *
   * @return Illuminate\Database\Eloquent\Relations\Relation
   */
  public function orderable()
  {
    return $this->morphTo();
  }

  /**
   * Get the total value of the order item.
   *
   * @return float
   */
  public function getTotalPaidAttribute()
  {
    return new Price($this->quantity * $this->price_paid->value());
  }
}
