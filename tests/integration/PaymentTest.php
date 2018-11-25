<?php

namespace Integration;

use App\User;
use TestCase;
use App\OrderNote;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;

class PaymentTest extends TestCase
{
    use \CreatesOrders, \UsesCart, \FlushesProductEvents;

    /** @test **/
    public function it_completes_an_order_upon_payment()
    {
        \Mail::fake();
        $user = $this->loginWithUser();
        $shop_admin = factory(User::class)->create(['is_shop_manager' => true]);
        $order = $this->createOrder(['status' => 'pending', 'delivery_note' => 'leave in the linhay', 'user_id' => $user->id]);

        \Session::put('order_id', $order->id);

        $response = $this->get('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->post(route('payments.store'), [
            'order_id'     => $order->id,
            'stripe_token' => $token,
            '_token'       => csrf_token(),
            ]);

        $response->assertRedirect('order-completed');

        $this->followRedirects($response)->assertSee(sprintf("'revenue': '%s'", $this->order->amount->asDecimal()));

        $this->assertDatabaseHas('orders', ['id' => $this->order->id, 'status' => \App\Order::PAID]);

        $note = OrderNote::where([
            'order_id' => $this->order->id,
            'key'      => 'payment_completed',
            ])->first();

        $this->assertContains('Payment completed', $note->body);

        $this->assertEquals(0, \Cart::total());
        $this->assertContains('ch_', $this->order->fresh()->payment_id);
        Mail::assertSent(OrderConfirmed::class, function ($mail) {
            return $mail->order->id === $this->order->id;
        });

        $admin_users = User::shopAdmins()->get();
    }

    /** @test */
    public function it_ensures_an_order_cannot_be_completed_more_than_once()
    {
        Mail::fake();

        $user = $this->loginWithUser();

        $shop_admin = factory(User::class)->create();
        $order = $this->createOrder(['status' => 'completed', 'user_id' => $user->id]);

        \Session::put('order_id', $order->id);

        $response = $this->get('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->call('POST', route('payments.store'), [
          'order_id'     => $this->order->id,
          'stripe_token' => $token,
          '_token'       => csrf_token(),
        ]);

        $response->assertRedirect('shop');
        $this->assertContains('No order exists or your order has expired', \Session::get('alert'));
    }

    /** @test **/
    public function it_returns_to_the_pay_page_if_there_is_a_payment_error()
    {
        Mail::fake();
        $user = $this->loginWithUser();

        $order = $this->createOrder(['status' => 'pending', 'user_id' => $user->id]);

        $order->setShipping(factory(\App\ShippingMethod::class)->create()->id);

        \Session::put('order_id', $order->id);

        $response = $this->get('checkout/pay');

        $token = $this->getFakeToken(true);

        $response = $this->call('POST', route('payments.store'), [
            'order_id'     => $this->order->id,
            'stripe_token' => $token,
            '_token'       => csrf_token(),
            ]);

        $response->assertRedirect('checkout/pay');
        $this->assertContains('declined', \Session::get('alert'));

        $this->assertDatabaseMissing('orders', ['id' => $this->order->id, 'status' => \App\Order::PAID]);

        // ensure an order note was logged
        $this->assertDatabaseHas('order_notes', ['order_id' => $this->order->id]);
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
