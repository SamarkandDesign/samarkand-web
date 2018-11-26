<?php

namespace Integration;

use TestCase;
use App\Address;
use App\Order;
use App\Product;
use App\User;

class OrderTest extends TestCase
{
  use \UsesCart, \CreatesOrders;

  public function setUp()
  {
    parent::setUp();
    Product::flushEventListeners();
  }

  /** @test **/
  public function it_prompts_for_registration_when_visiting_checkout()
  {
    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $response->assertRedirect('/login');
    $response = $this->post('/register', [
      'name' => 'Jimmy',
      'email' => 'jimmy@example.com',
      'password' => 'secret',
    ]);

    $response->assertRedirect('/checkout');
    $response = $this->followRedirects($response);

    $response->assertSee($product->name);
    $this->assertDatabaseHas('users', [
      'name' => 'Jimmy',
      'email' => 'jimmy@example.com',
    ]);
  }

  /** @test **/
  public function it_prompts_for_login_when_visiting_checkout()
  {
    $user = factory('App\User')->create(['password' => 'secret']);

    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $response->assertRedirect('/login');
    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'secret',
    ]);

    $response->assertRedirect('/checkout');
    $response = $this->followRedirects($response);

    $response->assertSee($product->name);
  }

  /** @test **/
  public function it_completes_an_order_using_a_new_address()
  {
    $user = $this->loginWithUser();
    $shippingMethod = factory('App\ShippingCountry')->create()->shipping_method;

    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $response->assertSee($product->name);

    $addressFields = factory(Address::class)->make([
      'addressable_id' => $user->id,
      'country' => 'GB',
    ]);

    // Submit the checkout form
    $response = $this->post('/orders', [
      // no 'different_shipping_address' field is sent
      'address' => ['billing' => $addressFields->toArray()],
      'delivery_note' => 'Leave in safe place',
    ]);
    $response->assertRedirect('/checkout/shipping');

    $order = Order::where([
      'delivery_note' => 'Leave in safe place',
      'status' => 'pending',
    ])->first();
    $address = Address::where(['line_1' => $addressFields['line_1']])->first();

    $this->assertEquals($order->billing_address_id, $address->id);
    $this->assertEquals($order->shipping_address_id, $address->id);

    // Go straight to the payment page (shipping method should be auto-selected)
    $response = $this->followRedirects($response);

    $response->assertSee($product->name);
    $response->assertSee($shippingMethod->description);

    // Pay for the order
    $response = $this->post(route('payments.store'), [
      'order_id' => $order->id,
      'stripe_token' => 'tok_cardsuccesstoken',
    ]);

    $response->assertRedirect('/order-completed');
    $response = $this->followRedirects($response);
    $response->assertSee('Order Completed');

    $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => \App\Order::PAID]);
  }

  /** @test **/
  public function it_creates_an_order_when_there_are_multiple_shipping_methods()
  {
    $user = $this->loginWithUser();
    $shippingMethod1 = factory('App\ShippingCountry')->create()->shipping_method;
    $shippingMethod2 = factory('App\ShippingCountry')->create()->shipping_method;

    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $billingAddressFields = factory(Address::class)->make([
      'addressable_id' => $user->id,
      'country' => 'GB',
    ]);

    $response = $this->post('/orders', [
      'address' => [
        'billing' => $billingAddressFields->toArray(),
      ],
      'delivery_note' => 'post to my special place',
    ]);
    $response->assertRedirect('/checkout/shipping');
    $response = $this->followRedirects($response);
    $response->assertSee($shippingMethod1->description);

    $response = $this->post('/orders/shipping', ['shipping_method_id' => $shippingMethod1->id]);

    $response->assertRedirect('/checkout/pay');
  }

  /** @test **/
  public function it_creates_an_order_with_different_new_addresses()
  {
    $user = $this->loginWithUser();

    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $billingAddressFields = factory(Address::class)->make(['addressable_id' => $user->id]);
    $shippingAddressFields = factory(Address::class)->make(['addressable_id' => $user->id]);

    $response = $this->post('/orders', [
      'different_shipping_address' => '1',
      'address' => [
        'billing' => $billingAddressFields->toArray(),
        'shipping' => $shippingAddressFields->toArray(),
      ],
      'delivery_note' => 'post to my special place',
    ]);
    $response->assertRedirect('/checkout/shipping');

    $order = Order::where([
      'delivery_note' => 'post to my special place',
      'status' => 'pending',
    ])->first();
    $billingAddress = Address::where(['line_1' => $billingAddressFields['line_1']])->first();
    $shippingAddress = Address::where(['line_1' => $shippingAddressFields['line_1']])->first();

    $this->assertEquals($order->billing_address_id, $billingAddress->id);
    $this->assertEquals($order->shipping_address_id, $shippingAddress->id);
  }

  /** @test **/
  public function it_creates_an_order_with_an_existing_address()
  {
    $user = $this->loginWithUser();
    $address = factory(Address::class)->create(['addressable_id' => $user->id]);

    $product = $this->putProductInCart();
    $response = $this->get('/checkout');

    $response = $this->post('/orders', [
      'billing_address_id' => $address->id,
      'shipping_address_id' => $address->id,
      'delivery_note' => 'Hey!',
    ]);
    $response->assertRedirect('/checkout/shipping');

    $order = Order::where(['delivery_note' => 'Hey!', 'status' => 'pending'])->first();

    $this->assertEquals($order->billing_address_id, $address->id);
    $this->assertEquals($order->shipping_address_id, $address->id);
  }
}
