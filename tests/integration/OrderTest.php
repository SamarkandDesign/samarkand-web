<?php

namespace Integration;

use App\User;
use TestCase;
use App\Order;
use App\Product;

class OrderTest extends TestCase
{
    use \UsesCart, \CreatesOrders;

    public function setUp()
    {
        parent::setUp();
        Product::flushEventListeners();
    }

    /** @test **/
    public function it_redirects_to_login_if_email_is_recognised()
    {
        $user = factory(User::class)->create([
      'password' => 'secret',
    ]);
        $product = $this->putProductInCart();

        $response = $this->get('checkout');

        $response = $this->post('/orders', [
      'email' => $user->email,
    ]);

        $response->assertRedirect(sprintf('login?email=%s', urlencode($user->email)));
    }

    /** @test **/
    public function it_auto_creates_a_user_for_the_order_when_not_logged_in()
    {
        $product = $this->putProductInCart();
        $shipping_method = factory('App\ShippingMethod')->create()->allowCountries(['GB']);

        $response = $this->get('checkout');

        $response = $this->post('/orders', array_merge($this->getAddressFields(), [
        'email' => 'booboo@tempuser.com',
        'delivery_note' => 'Leave in the barn',
      ]));

        $response->assertRedirect('/checkout/shipping');

        // $expectedTotal = $product->getPrice()->value() + $shipping_method->getPrice()->value();

        $this->assertDatabaseHas('orders', [
        // 'amount' => $expectedTotal,
        'status' => 'pending',
        'delivery_note' => 'Leave in the barn',
      ]);

        $this->assertTrue(User::where('email', 'booboo@tempuser.com')->first()->autoCreated());
        $this->assertDatabaseHas('addresses', ['city' => 'London']);
    }

    /** @test **/
    public function it_creates_an_order_from_a_logged_in_user()
    {
        $user = $this->loginWithUser([], 'customer');
        $product = $this->putProductInCart();
        $address = factory(\App\Address::class)->create(['addressable_id' => $user->id, 'country' => 'GB']);
        $shipping_method = factory('App\ShippingMethod')->create()->allowCountries(['GB']);

        $current_stock = $product->stock_qty;

        $response = $this->get('checkout');

        $response = $this->followRedirects($this->post('/orders', [
        'delivery_note' => 'Leave in the barn',
        'billing_address_id' => $address->id,
        'shipping_address_id' => $address->id,
      ]));

        $response->assertSee('<h1>Pay</h1>');

        $order = \App\Order::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('orders', [
        'status' => 'pending',
        'delivery_note' => 'Leave in the barn',
        'amount' => $shipping_method->getPrice()->value() + $product->getPrice()->value(),
      ]);

        $this->assertEquals($address->id, $order->billing_address_id);
        $this->assertEquals($address->id, $order->shipping_address_id);

        $this->assertEquals($current_stock, $product->fresh()->stock_qty);
    }

    /** @test **/
    public function it_asks_for_a_shipping_method_if_more_than_one_is_available()
    {
        $user = $this->loginWithUser([], 'customer');
        $product = $this->putProductInCart();
        $address = factory(\App\Address::class)->create(['addressable_id' => $user->id, 'country' => 'GB']);

        $shipping_method = factory('App\ShippingMethod')->create(['base_rate' => 550])->allowCountries(['GB']);
        $shipping_method_2 = factory('App\ShippingMethod')->create(['base_rate' => 650])->allowCountries(['GB']);
        $this->get('checkout');
        $response = $this->post('/orders', [
        'billing_address_id' => $address->id,
        'shipping_address_id' => $address->id,
      ]);

        $response = $this->get('/checkout/shipping');
        $response->assertSee($shipping_method->description);
        $response->assertSee($shipping_method_2->description);

        $response = $this->post('/orders/shipping', [
        'shipping_method_id' => $shipping_method_2->id,
        ]);

        $response->assertRedirect('checkout/pay');
    }

    /** @test **/
    public function it_auto_assigns_shipping_if_only_one_method_available()
    {
        $user = $this->loginWithUser([], 'customer');
        $product = $this->putProductInCart();
        $address = factory(\App\Address::class)->create(['addressable_id' => $user->id, 'country' => 'GB']);
        $shipping_method = factory('App\ShippingMethod')->create(['base_rate' => 500]);
        $shipping_method_2 = factory('App\ShippingMethod')->create(['base_rate' => 600]);

        $shipping_method->allowCountries(['GB']);
        $shipping_method_2->allowCountries(['US']);

        $this->get('checkout');
        $response = $this->followRedirects($this->post('/orders', [
        'billing_address_id' => $address->id,
        'shipping_address_id' => $address->id,
      ]));
        $response->assertSee($shipping_method->description);
        $response->assertSee('Order Details');
    }

    /** @test **/
    public function it_does_not_allow_selecting_a_shipping_method_for_the_wrong_country()
    {
        $user = $this->loginWithUser([], 'customer');
        $product = $this->putProductInCart();
        $address = factory(\App\Address::class)->create(['addressable_id' => $user->id, 'country' => 'GB']);

        $shipping_method = factory('App\ShippingMethod')->create(['base_rate' => 500]);
        $shipping_method_2 = factory('App\ShippingMethod')->create(['base_rate' => 600]);
        $shipping_method_3 = factory('App\ShippingMethod')->create(['base_rate' => 200]);

        $shipping_method->allowCountries(['GB']);
        $shipping_method_2->allowCountries(['GB']);

        $response = $this->get('checkout');
        $response = $this->followRedirects($this->post('/orders', [
        'billing_address_id' => $address->id,
        'shipping_address_id' => $address->id,
      ]));
        // // simulate a post request as if the user maliciously changed
        // // the form on the page to choose shipping method 3
        $response = $this->post('/orders/shipping', [
        'shipping_method_id' => $shipping_method_3->id,
      ]);

        $response->assertRedirect('/checkout/shipping');
    }

    /** @test **/
    public function it_redirects_if_no_shipping_is_available()
    {
        $user = $this->loginWithUser([], 'customer');
        $product = $this->putProductInCart();
        $address = factory(\App\Address::class)->create(['addressable_id' => $user->id, 'country' => 'FR']);
        $shipping_method = factory('App\ShippingMethod')->create(['base_rate' => 500]);

        $shipping_method->allowCountries(['GB']);

        $response = $this->get('checkout');
        $response = $this->followRedirects($this->post('/orders', [
        'billing_address_id' => $address->id,
        'shipping_address_id' => $address->id,
      ]));
        $response->assertSee('choose a different shipping address');
    }

    /** @test **/
    public function it_creates_a_user_for_the_order_when_they_select_to_make_new_account()
    {
        $product = $this->putProductInCart();
        factory('App\ShippingMethod')->create(['base_rate' => 500])->allowCountries(['GB']);

        $response = $this->get('checkout');
        $request = array_merge($this->getAddressFields(), [
        'email' => 'booboo@tempuser.com',
        'create_account' => '1',
        'password' => 'smoomoo',
        'password_confirmation' => 'smoomoo',
        ]);

        $response = $this->followRedirects($this->post('orders', $request));
        // $response->dump();

        $this->assertDatabaseHas('addresses', ['city' => 'London']);
        $user = User::where('email', 'booboo@tempuser.com')->first();
        $this->assertFalse($user->autoCreated());

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'status' => \App\Order::PENDING]);
        $this->assertTrue(\Auth::check());
    }

    /** @test **/
    public function it_prompts_login_if_user_exists_but_is_signed_out()
    {
        $product = $this->putProductInCart();
        $user = factory(User::class)->create([
        'password' => 'password',
      ]);

        $response = $this->get('checkout');

        $request = array_merge($this->getAddressFields(), [
        'email' => $user->email,
        ]);

        $response = $this->followRedirects($this->post('orders', $request));
        $response->assertSee('This email has an account here');

        $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password',
      ]);

        $response->assertRedirect('/checkout');
    }

    /** @test **/
    public function it_validates_invalid_user_input()
    {
        $product = $this->putProductInCart();
        $request = array_merge($this->getAddressFields(), [
        'email' => 'tempuser.com',
        ]);

        $response = $this->get('checkout');
        $response = $this->post('orders', $request);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect('/checkout');
    }

    /** @test **/
    public function it_views_an_order_summary()
    {
        $this->createOrder();

        $this->be($this->customer);
        $order = $this->order;

        $response = $this->get("account/orders/{$order->id}");

        $this->assertContains("{$order->amount->asDecimal()}", $response->getContent());
    }

    /** @test **/
    public function it_does_not_allow_viewing_another_users_order_summary()
    {
        $this->createOrder();

        // Login with a different user
        $this->loginWithUser();

        $response = $this->get("account/orders/{$this->order->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function it_allows_paying_for_a_previously_unpaid_order()
    {
        $order = $this->createOrder([
      'status' => Order::PENDING,
    ]);
        $shipping_method = factory('App\ShippingMethod')->create(['base_rate' => 500]);
        $shipping_method->allowCountries([$order->shipping_address->country]);

        $order->setShipping($shipping_method->id);

        $this->be($order->user);

        $response = $this->followRedirects($this->get(route('orders.pay', $order)));

        $this->assertContains($order->order_items->first()->description, $response->getContent());
    }

    protected function getAddressFields($type = 'billing')
    {
        return [
      "{$type}_address" => [
        'name' => 'Joe',
        'line_1' => '10 Downing Street',
        'city' => 'London',
        'country' => 'GB',
        'postcode' => 'SW1A 2AA',
        'phone' => '01234567891',
      ],
    ];
    }
}
