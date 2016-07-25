<?php

namespace App\Billing;

use Mockery;
use Stripe\Charge;
use Stripe\Stripe;

class FakeStripeGateway implements GatewayInterface
{
    /**
     * Simulate a call to the Stripe api to perform a charge.
     *
     * @param array $data The charge data
     * @param array $meta Meta info
     *
     * @throws \App\Billing\CardException
     *
     * @return \Stripe\Charge
     */
    public function charge(array $data, array $meta = [])
    {
        if ($data['card'] === 'tok_cardfailuretoken') {
            throw new CardException('Card declined');
        }

        $fakeCharge = Mockery::mock(Charge::class);
        $fakeCharge->id = 'ch_18bE2t4C5r3jEhospTyyfba5';

        return $fakeCharge;
    }
}
