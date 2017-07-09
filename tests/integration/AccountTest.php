<?php

namespace Integration;

use App\User;
use TestCase;

class AccountTest extends TestCase
{
    use \CreatesOrders, \FlushesProductEvents;

    /** @test **/
    public function it_can_see_the_account_page()
    {
        $this->loginWithUser();
        $response = $this->get('/account');
        $this->assertContains('Manage Addresses', $response->getContent());
    }

    /** @test **/
    public function it_can_view_an_order_summary_with_a_deleted_address()
    {
        $this->createOrder();

        $this->be($this->customer);

        $response = $this->get('/account/addresses');
        // ->press('Delete')
        // $this->assertContains('Address deleted', $response->getContent());

        $response = $this->get("/account/orders/{$this->order->id}");
        $this->assertContains("Order #{$this->order->id}", $response->getContent());
    }

    /** @test **/
    public function it_can_view_an_order_summary_with_a_deleted_product()
    {
        $order = $this->createOrder();

        $product = $order->order_items->first()->orderable;

        $product->delete();

        $this->be($this->customer);

        $response = $this->get("/account/orders/{$this->order->id}");
        $this->assertContains("Order #{$this->order->id}", $response->getContent());
    }

    /** @test **/
    public function it_can_update_a_user_account()
    {
        $user = $this->loginWithUser();

        $response = $this->patch("/account/{$user->id}", [
            'name' => 'Joe Bloggs',
            'email' => 'joe@example.com',
        ]);

        $response->assertRedirect('/account');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'joe@example.com']);
    }

    /** @test **/
    public function it_cannot_update_another_user()
    {
        $other_user = factory(User::class)->create();

        $user = $this->loginWithUser();

        $response = $this->patch(route('accounts.update', $other_user), ['email' => 'bad@email.com']);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('users', ['id' => $other_user->id, 'email' => 'bad@email.com']);
    }

    /** @test **/
    public function it_does_not_allow_a_user_to_update_admin_fields()
    {
        $user = factory(User::class)->create();
        $customer_role = factory(\App\Role::class)->create(['name' => 'customer', 'display_name' => 'Customer']);
        $admin_role = factory(\App\Role::class)->create(['name' => 'admin', 'display_name' => 'Admin']);

        $this->be($user);
        $response = $this->patch(route('accounts.update', $user), [
            'role_id'         => $admin_role->id,
            'is_shop_manager' => true,
            'username'        => 'foobar',
            ]);

        $this->assertFalse($user->fresh()->hasRole('admin'));
        $this->assertDatabaseMissing('users', ['id' => $user->id, 'is_shop_manager' => true]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'foobar']);
    }
}
