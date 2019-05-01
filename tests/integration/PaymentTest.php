<?php

namespace Integration;

use App\User;
use TestCase;
use App\OrderNote;
use App\Mail\OrderConfirmed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentTest extends TestCase
{
  use \CreatesOrders, \UsesCart, \FlushesProductEvents;

  /** @test */
  public function it_ensures_an_order_cannot_be_completed_more_than_once()
  {
    Mail::fake();

    $user = $this->loginWithUser();

    $shop_admin = factory(User::class)->create();
    $order = $this->createOrder(['status' => 'completed', 'user_id' => $user->id]);

    \Session::put('order_id', $order->id);

    $response = $this->get('checkout/pay');

    $response->assertRedirect('shop');
    $this->assertContains('No order exists or your order has expired', \Session::get('alert'));
  }

  /**
   * Get a fake stripe token for performing a charge.
   *
   * @param bool $card_failure should the token fail when attempted?
   *
   * @return string The fake token
   */
  protected function getFakeToken($card_failure = false)
  {
    return $card_failure ? 'tok_cardfailuretoken' : 'tok_cardsuccesstoken';
  }
}
