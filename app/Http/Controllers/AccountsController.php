<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\UpdateUserRequest;

class AccountsController extends Controller
{
  protected $auth;

  public function __construct(Guard $auth)
  {
    $this->middleware('auth');

    $this->auth = $auth;
  }

  /**
   * Show the page for managing the user account.
   *
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    return view('accounts.show', ['user' => $this->auth->user()]);
  }

  /**
   * Show the page for editing an account.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit()
  {
    return view('accounts.edit', ['user' => $this->auth->user()]);
  }

  /**
   * Update the user in storage.
   *
   * @param User              $user
   * @param UpdateUserRequest $request
   *
   * @return Response
   */
  public function update(User $user, UpdateUserRequest $request)
  {
    $user->update($request->all());

    return redirect()
      ->route('accounts.show')
      ->with([
        'alert' => 'Profile updated',
        'alert-class' => 'success',
      ]);
  }
}
