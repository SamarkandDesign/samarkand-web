<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
  public function index()
  {
    $users = User::with('role')->paginate();

    return view('admin.users.index')->with(compact('users'));
  }

  /**
   * Show a form for editng the currently logged in user.
   *
   * @return Response
   */
  public function profile()
  {
    return $this->edit(auth()->user());
  }

  /**
   * Show a form for editing a user.
   *
   * @param User $user
   *
   * @return Reponse
   */
  public function edit(User $user)
  {
    return view('admin.users.edit')->with(compact('user'));
  }

  public function create(User $user)
  {
    return view('admin.users.create', compact('user'));
  }

  public function store(CreateUserRequest $request, User $user)
  {
    $user->create($request->all());

    return redirect()
      ->route('admin.users.index')
      ->with([
        'alert' => 'User Created!',
        'alert-class' => 'success',
      ]);
  }

  /**
   * Update the user in storage.
   *
   * @param User              $user
   * @param UpdateUserRequest $request
   *
   * @return \Illuminate\Http\Response
   */
  public function update(User $user, UpdateUserRequest $request)
  {
    $user->update($request->all());

    return redirect()
      ->route('admin.users.edit', $user->username)
      ->with([
        'alert' => 'Profile updated',
        'alert-class' => 'success',
      ]);
  }

  /**
   * Refresh a user's api token.
   *
   * @param User $user
   *
   * @return \Illuminate\Http\Response
   */
  public function token(User $user)
  {
    $user->api_token = str_random(60);
    $user->save();

    return redirect()
      ->back()
      ->with([
        'alert' => 'Token Refreshed',
        'alert-class' => 'success',
      ]);
  }

  /**
   * Show the orders for a user.
   *
   * @param User $user
   *
   * @return \Illuminate\Http\Response
   */
  public function orders(User $user)
  {
    $orders = $user->orders()->paginate();
    $title = "Orders for {$user->name}";

    return view('admin.orders.index', compact('orders', 'title'));
  }

  public function addresses(User $user)
  {
    return view('admin.users.addresses', compact('user'));
  }
}
