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

        // \Event::fake();

        $shop_admin = factory(User::class)->create(['is_shop_manager' => true]);
        $this->createOrder(['status' => 'pending', 'delivery_note' => 'leave in the linhay']);

        \Session::put('order', $this->order);

        $response = $this->get('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->post(route('payments.store'), [
            'order_id'     => $this->order->id,
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

        $shop_admin = factory(User::class)->create();
        $this->createOrder(['status' => 'completed']);

        \Session::put('order', $this->order);

        $response = $this->get('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->call('POST', route('payments.store'), [
          'order_id'     => $this->order->id,
          'stripe_token' => $token,
          '_token'       => csrf_token(),
          ]);

        $response->assertRedirect('shop');
        $this->assertContains('order has either already been paid for', \Session::get('alert'));
    }

    /** @test **/
    public function it_returns_to_the_pay_page_if_there_is_a_payment_error()
    {
        Mail::fake();

        $this->createOrder(['status' => 'pending']);

        $this->order->setShipping(factory(\App\ShippingMethod::class)->create()->id);

        \Session::put('order', $this->order);

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
     * Get a stripe token for creating a charge.
     *
     * @param bool $card_failure Whether the token should result in a payment error (e.g. card denied)
     *
     * @return string
     */
    protected function getToken($card_failure = false)
    {
        $address = factory('App\Address')->create();

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $card_number = $card_failure ? '4000000000000002' : '4242424242424242';

        $token = \Stripe\Token::create([
            'card' => [
            'number'          => $card_number,
            'exp_month'       => 1,
            'exp_year'        => date('Y') + 1,
            'cvc'             => '314',
            'name'            => $address->full_name,
            'address_line1'   => $address->line_1,
            'address_line2'   => $address->line_2,
            'address_city'    => $address->city,
            'address_zip'     => $address->postcode,
            'address_country' => $address->country,
            ],
            ]);

        return $token->id;
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
