<?php

namespace Integration;

use TestCase;

class UsersTest extends TestCase
{
    use \FlushesProductEvents;

    /** @test **/
    public function it_can_create_a_new_user()
    {
        $this->logInAsAdmin();

        $this->get('/admin/users/new');

        $this->followRedirects($this->post('/admin/users', [
             'name' => 'Joe Bloggs',
             'username' => 'joebloggs',
             'email' => 'joe@bloggs.com',
             'password' => 'secret123',
             'password_confirmation' => 'secret123',
             'trade_discount' => 10,
            ]));

        $this->assertDatabaseHas('users', [
            'username'    => 'joebloggs',
            'email'       => 'joe@bloggs.com',
            'trade_discount'    => 10,
            ]);

        // Ensure the password has been saved and hashed correctly
        $this->assertTrue(\Auth::validate([
            'email'    => 'joe@bloggs.com',
            'password' => 'secret123',
            ]));
    }

    /** @test **/
    public function it_can_edit_its_own_user_profile()
    {
        $currentUser = $this->logInAsAdmin();

        $newUserProfile = factory('App\User')->create()->toArray();

        $response = $this->updateProfile($newUserProfile);

        $response->assertRedirect("/admin/users/{$newUserProfile['username']}");

        $this->followRedirects($response)->assertSee('Profile updated');

        $this->assertDatabaseHas('users', [
            'name'        => $newUserProfile['name'],
            'username'    => $newUserProfile['username'],
            'email'       => $newUserProfile['email'],
            ]);

        // Ensure the password has been saved and hashed correctly
        $this->assertTrue(\Auth::validate([
            'email'    => $newUserProfile['email'],
            'password' => 'secret123',
            ]));
    }

    /** @test **/
    public function it_does_not_allow_user_details_that_are_already_taken()
    {
        $currentUser = $this->logInAsAdmin();

        // Make a new user in the database
        $user = factory('App\User')->create();

        $this->get('/admin/profile');
        // Try to update our own profile with info from the already existant user
        $response = $this->updateProfile([
            'id' => $currentUser->id,
            'email' => $user->email,
            'username' => $user->username,
            ]);

        // Check for error messages
        $content = $this->followRedirects($response)->content();
        $this->assertContains('email has already been taken', $content);
        $this->assertContains('username has already been taken', $content);

        // Ensure we haven't updated the user in the database
        $this->assertDatabaseHas('users', ['username' => $user['username']]);
        $this->assertDatabaseMissing('users', [
            'id'       => $currentUser->id,
            'username' => $user['username'],
            ]);
    }

    /** @test **/
    public function it_shows_the_orders_for_a_user()
    {
        $order_item = factory(\App\OrderItem::class)->create();

        $user = $order_item->order->customer;

        $this->logInAsAdmin();

        $response = $this->get("admin/users/{$user->username}/orders");
        $this->assertContains("#{$order_item->id}", $response->getContent());
    }

    /** @test **/
    public function it_shows_the_addresses_for_a_user()
    {
        $address = factory(\App\Address::class)->make();
        $user = factory(\App\User::class)->create();

        $user->addresses()->save($address);

        $this->logInAsAdmin();

        $response = $this->get("admin/users/{$user->username}/addresses");
        $this->assertContains($address->line_1, $response->getContent());
    }

    /** @test **/
    public function it_regenerates_a_users_api_token()
    {
        $user = factory(\App\User::class)->create();
        $token = $user->api_token;
        $this->logInAsAdmin();

        $response = $this->get("admin/users/{$user->username}");
        $this->assertContains($token, $response->getContent());
        $response = $this->patch("admin/users/{$user->id}/token");

        $response->assertRedirect("admin/users/{$user->username}");

        $this->assertNotEquals($token, $user->fresh()->api_token);
    }

    private function updateProfile($overrides = [])
    {
        $user = array_merge($this->newUserProfile(), $overrides);

        return $this->patch("/admin/users/{$user['id']}", [
             'name' => $user['name'],
             'username' => $user['username'],
             'email' => $user['email'],
             'password' => 'secret123',
             'password_confirmation' => 'secret123',
             ]);
    }

    private function newUserProfile()
    {
        return factory('App\User')->make()->toArray();
    }
}
