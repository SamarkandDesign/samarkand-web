<?php

namespace Integration;

use App\Jobs\CreateInvoiceForOrder;
use App\OrderNote;
use App\User;
use MailThief\Testing\InteractsWithMail;
use TestCase;

class PaymentTest extends TestCase
{
    use \CreatesOrders, \UsesCart, InteractsWithMail, \FlushesProductEvents;

    /** @test **/
    public function it_completes_an_order_upon_payment()
    {
        $this->expectsJobs(CreateInvoiceForOrder::class);

        $shop_admin = factory(User::class)->create(['is_shop_manager' => true]);
        $this->createOrder(['status' => 'pending', 'delivery_note' => 'leave in the linhay']);

        \Session::put('order', $this->order);

        $this->visit('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->call('POST', route('payments.store'), [
            'order_id'     => $this->order->id,
            'stripe_token' => $token,
            '_token'       => csrf_token(),
            ]);

        $this->assertRedirectedTo('order-completed');

        $this->followRedirects();

        $this->see(sprintf("'revenue': '%s'", $this->order->amount->asDecimal()));

        $this->seeInDatabase('orders', ['id' => $this->order->id, 'status' => \App\Order::PAID]);

        $note = OrderNote::where([
            'order_id' => $this->order->id,
            'key'      => 'payment_completed',
            ])->first();

        $this->assertContains('Payment completed', $note->body);

        $this->assertEquals(0, \Cart::total());
        $this->assertContains('ch_', $this->order->fresh()->payment_id);

        $this->seeMessageFor($this->customer->email);

        $admin_users = User::shopAdmins()->get();
        $this->seeMessageFor($admin_users->first()->email);
        $this->assertContains('leave in the linhay', $this->lastMessage()->getBody());
    }

    /** @test */
    public function it_ensures_an_order_cannot_be_completed_more_than_once()
    {
        $shop_admin = factory(User::class)->create();
        $this->createOrder(['status' => 'completed']);

        \Session::put('order', $this->order);

        $this->visit('checkout/pay');

        $token = $this->getFakeToken();

        $response = $this->call('POST', route('payments.store'), [
          'order_id'     => $this->order->id,
          'stripe_token' => $token,
          '_token'       => csrf_token(),
          ]);

        $this->assertRedirectedTo('shop');
        $this->assertContains('order has either already been paid for', \Session::get('alert'));
    }

    /** @test **/
    public function it_returns_to_the_pay_page_if_there_is_a_payment_error()
    {
        $this->createOrder(['status' => 'pending']);

        $this->order->setShipping(factory(\App\ShippingMethod::class)->create()->id);

        \Session::put('order', $this->order);

        $this->visit('checkout/pay');

        $token = $this->getFakeToken(true);

        $response = $this->call('POST', route('payments.store'), [
            'order_id'     => $this->order->id,
            'stripe_token' => $token,
            '_token'       => csrf_token(),
            ]);

        $this->assertRedirectedTo('checkout/pay');
        $this->assertContains('declined', \Session::get('alert'));

        $this->dontSeeInDatabase('orders', ['id' => $this->order->id, 'status' => \App\Order::PAID]);

        // ensure an order note was logged
        $this->seeInDatabase('order_notes', ['order_id' => $this->order->id]);
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
