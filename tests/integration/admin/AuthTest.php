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

        $this->visit('admin/login')
             ->type($user->email, 'email')
             ->type('password', 'password')
             ->press('Sign In')
             ->seePageIs('admin');
    }

    /** @test **/
    public function it_shows_the_admin_login_page_if_unauthenticated()
    {
        $this->visit('admin')
             ->seePageIs('admin/login');

        $this->visit('admin/products')
             ->seePageIs('admin/login');
    }
}
