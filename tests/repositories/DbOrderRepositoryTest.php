<?php

namespace App\Repositories\Order;

use TestCase;
use App\Order;
use Carbon\Carbon;

class DbOrderRepositoryTest extends TestCase
{
  use \FlushesProductEvents, \CreatesOrders;

  private $orders;

  public function setUp()
  {
    parent::setUp();
    $this->orders = app(DbOrderRepository::class);
  }

  /** @test **/
  public function it_counts_the_orders()
  {
    factory(Order::class, 2)->create(['status' => Order::PAID]);
    factory(Order::class, 3)->create(['status' => Order::COMPLETED]);

    $this->assertEquals(5, $this->orders->count());
    $this->assertEquals(3, $this->orders->count(Order::COMPLETED));
  }

  /** @test **/
  public function it_gets_order_values_by_month()
  {
    Order::unguard(); // so we can set the created_at time
    // make some orders
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()]);
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()->subWeeks(1)]);
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()->subWeeks(3)]);
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()->subWeeks(5)]);
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()->subWeeks(9)]);
    $this->createOrder(['status' => Order::COMPLETED, 'created_at' => Carbon::now()->subWeeks(15)]);
    Order::reguard();
    $data = $this->orders->salesByMonth();
    $this->assertNotEmpty($data);
    // dd($data->toArray());
  }
}
