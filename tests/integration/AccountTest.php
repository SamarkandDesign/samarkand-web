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
        $this->visit('/account')
        ->see('Manage Addresses');
    }

    /** @test **/
    public function it_can_view_an_order_summary_with_a_deleted_address()
    {
        $this->createOrder();

        $this->be($this->customer);

        $this->visit('/account/addresses')
        ->press('Delete')
        ->see('Address deleted');

        $this->visit("/account/orders/{$this->order->id}")
        ->see("Order #{$this->order->id}");
    }

    /** @test **/
    public function it_can_view_an_order_summary_with_a_deleted_product()
    {
        $order = $this->createOrder();

        $product = $order->order_items->first()->orderable;

        $product->delete();

        $this->be($this->customer);

        $this->visit("/account/orders/{$this->order->id}")
        ->see("Order #{$this->order->id}");
    }

    /** @test **/
    public function it_can_update_a_user_account()
    {
        $user = $this->loginWithUser();

        $this->visit('/account/edit')
        ->type('Joe Bloggs', 'name')
        ->type('joe@example.com', 'email')
        ->press('Update Account')
        ->seePageIs('/account')
        ->see('Profile updated');

        $this->seeInDatabase('users', ['id' => $user->id, 'email' => 'joe@example.com']);
    }

    /** @test **/
    public function it_cannot_update_another_user()
    {
        $other_user = factory(User::class)->create();

        $user = $this->loginWithUser();

        $this->patch(route('accounts.update', $other_user), ['email' => 'bad@email.com']);
        $this->assertResponseStatus(403);

        $this->notSeeInDatabase('users', ['id' => $other_user->id, 'email' => 'bad@email.com']);
    }


    /** @test **/
    public function it_does_not_allow_a_user_to_update_admin_fields()
    {
        $user = factory(User::class)->create();
        $customer_role = factory(\App\Role::class)->create(['name' => 'customer', 'display_name' => 'Customer']);
        $admin_role = factory(\App\Role::class)->create(['name' => 'admin', 'display_name' => 'Admin']);

        $this->be($user);
        $response = $this->patch(route('accounts.update', $user), [
            'role_id' => $admin_role->id,
            'is_shop_manager' => true,
            'username' => 'foobar'
            ]);

        $this->assertFalse($user->fresh()->hasRole('admin'));
        $this->notSeeInDatabase('users', ['id' => $user->id, 'is_shop_manager' => true]);
        $this->seeInDatabase('users', ['id' => $user->id, 'username' => 'foobar']);
    }
}
