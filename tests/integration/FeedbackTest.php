<?php

namespace Integration;

use TestCase;
use App\Address;
use App\Order;
use App\User;

class FeedbackTest extends TestCase
{
  use \UsesCart, \CreatesOrders;

  public function setUp()
  {
    parent::setUp();
  }

  /** @test **/
  public function it_allows_giving_feedback_on_the_summary_page()
  {
    $user = $this->loginWithUser();
    $order = factory(Order::class)->create();

    \Session::put('order_id', $order->id);
    $message = 'I heard about you at a trade fair';

    $orderSummaryResponse = $this->get('/order-completed');
    $orderSummaryResponse->assertSee('Where did you hear about us');

    $feedbackResponse = $this->post('/feedbacks', ['message' => $message]);
    $feedbackResponse->assertRedirect('/');


    $this->assertDatabaseHas('feedbacks', [
      'message' => $message,
      'user_id' => $user->id,
    ]);
  }

}
