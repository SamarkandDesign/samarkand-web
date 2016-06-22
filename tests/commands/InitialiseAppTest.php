<?php


class InitialiseAppTest extends TestCase
{
    /** @test */
    public function it_initialises_the_app_with_a_new_user_admin()
    {
        $email = 'foo@example.com';

        Artisan::call('skd:init', ['email' => $email, 'password' => 'secret']);
        $this->assertContains("Created new admin user with email $email", Artisan::output());

        $this->seeInDatabase('users', ['email' => $email]);
        $user = App\User::where('email', $email)->first();

        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function it_informs_if_the_user_already_exists()
    {
        $user = factory(App\User::class)->create();

        Artisan::call('skd:init', ['email' => $user->email, 'password' => 'secret']);
        $this->assertContains("User with email {$user->email} already exists. Done nothing.", Artisan::output());
    }
}
