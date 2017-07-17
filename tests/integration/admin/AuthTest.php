<?php

namespace Integration\Admin;

use App\User;
use TestCase;

class AuthTest extends TestCase
{
    /** @test **/
    public function it_allows_an_admin_to_login_to_the_dashboard()
    {
        $user = factory(User::class)->create(['password' => 'password']);
        $user->assignRole('admin');

        \Session::put('url.intended', '/admin');

        $response = $this->post('/login', [
         'email'    => $user->email,
         'password' => 'password',
         ]);

        $response->assertRedirect('/admin');
    }

    /** @test **/
    public function it_shows_the_admin_login_page_if_unauthenticated()
    {
        $response = $this->get('admin')->assertRedirect('admin/login');

        $response = $this->get('admin/products')->assertRedirect('admin/login');
    }
}
