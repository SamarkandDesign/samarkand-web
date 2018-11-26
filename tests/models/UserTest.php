<?php

namespace App;

use TestCase;

class UserTest extends TestCase
{
  /** @test */
  public function it_verifies_if_a_user_has_been_auto_created()
  {
    $user = factory(User::class)->create();
    $user->last_seen_at = null;
    $user->save();

    $this->assertTrue($user->autoCreated());

    \Auth::attempt(['email' => $user->email, 'password' => 'password']);

    $this->assertFalse($user->fresh()->autoCreated());
  }

  /** @test **/
  public function it_gets_admin_users()
  {
    $admin_user1 = factory(User::class)->create(['is_shop_manager' => true]);
    $admin_user2 = factory(User::class)->create(['is_shop_manager' => true]);

    $normal_user = factory(User::class)->create(['is_shop_manager' => false]);

    $admins = User::shopAdmins()->get();

    $this->assertCount(2, $admins);
    $this->assertTrue($admins->contains('id', $admin_user1->id));
    $this->assertTrue($admins->contains('email', $admin_user2->email));
    $this->assertFalse($admins->contains('id', $normal_user->id));
  }
}
