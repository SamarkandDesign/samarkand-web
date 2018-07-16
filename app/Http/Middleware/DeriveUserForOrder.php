<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use App\Repositories\User\DbUserRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DeriveUserForOrder
{
    use ValidatesRequests;

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    private $users;

    private $request;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, DbUserRepository $users)
    {
        $this->auth = $auth;
        $this->users = $users;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // The user is already logged in, use them in the request
        if ($this->auth->check()) {
            $request->merge(['customer' => $this->auth->user()]);

            return $next($request);
        }

        $this->request = $request;
        $this->validateInput();

        // The email address has an account
        if ($user = User::where('email', $request->email)->first()) {
            // They were auto-created so we can't ask them to login. Just use their account for the order
            if ($user->autoCreated()) {
                $request->merge(['customer' => $user]);

                return $next($request);
            }

            // Ask them to login for the order
            $request->session()->flash('url.intended', 'checkout');

            return redirect()
            ->route('login', ['email' => $request->email])
            ->with([
                   'alert'       => 'This email has an account here. Please login. If you do not know your password please reset it.',
                   'alert-class' => 'warning',
                   ]);
        }

        // They haven't made an account yet
        $request->merge(['customer' => $this->createCustomer()]);

        return $next($request);
    }

    protected function createCustomer()
    {
        $fields = $this->request->has('create_account')
            ? ['email', 'password', 'password_confirmation']
            : ['email'];

        $billing_address = $this->request->get('billing_address');

        $data = collect($this->request->only($fields));
        $data['name'] = $billing_address['name'] ? $billing_address['name'] : 'unknown';

        $user = \App\User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'username'     => $data['email'],
            'password'     => $data->get('password', str_random(15)),
            'last_seen_at' => $data->has('password') ? new \DateTime() : null,
        ]);

        $user->assignRole('customer');

        if ($this->request->has('create_account')) {
            \Auth::login($user);
        }

        return $user;
    }

    /**
     * Validate the customer input.
     *
     * @return void
     */
    protected function validateInput()
    {
        $rules = [
        'email'    => 'required|email|max:255',
        ];

        if ($this->request->has('create_account')) {
            $rules['password'] = 'required|confirmed|min:6';
        }
        $this->validate($this->request, $rules);
    }
}
