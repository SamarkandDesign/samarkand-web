<?php

namespace Integration;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use TestCase;

class AuthTest extends TestCase
{
    /** @test **/
    public function it_allows_resetting_a_users_password()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $response = $this->get('/password/reset');
        $response->assertStatus(200);

        $response = $this->post('password/email', [
            'email' => $user->email,
            ]);

        $response->assertRedirect('/password/reset');

        $token = '';

        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function ($notification, $channels) use (&$token) {
                $token = $notification->token;
                return true;
            });

        $response = $this->get("/password/reset/{$token}");

        $response->assertStatus(200);

        $response = $this->post('/password/reset', [
             'token' => $token,
             'email' => $user->email,
             'password' => 'secret',
             'password_confirmation' => 'secret',
            ]);


        \Auth::logout();

        // Ensure the newly reset password works to login with
        $this->assertTrue(\Auth::attempt([
            'email'    => $user->email,
            'password' => 'secret',
            ]));
    }

    /** @test **/
    public function it_marks_a_user_as_not_auto_created_if_they_log_in_successfully()
    {
        $email = 'jb@email.com';
        $user = factory(User::class)->create([
            'password' => 'password',
            'email'    => $email,
            ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);

        $this->assertTrue(\Auth::check());

        $this->assertFalse($user->fresh()->autoCreated());
    }

    /** @test **/
    public function it_cannot_login_with_invalid_credentials()
    {
        $response = $this->get('login');

        $response = $this->post('/login', [
            'email' => 'fakename@noone.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertTrue(\Auth::guest());
    }

    /** @test **/
    public function it_throttles_invalid_logins()
    {
        $response = array_reduce(range(0,5), function() {
            return $this->post('/login', [
                'email' => 'fakename@noone.com',
                'password' => 'password',
            ]);
        });

        $this->assertContains(
            'Too many login attempts. Please try again in 60 seconds.',
            session()->get('errors')->get('email')
            );
    }
}
