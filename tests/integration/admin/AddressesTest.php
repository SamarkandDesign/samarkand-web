<?php

namespace Integration\Admin;

use TestCase;
use App\Address;

class AddressesTest extends TestCase
{
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->logInAsAdmin();
    }

    /** @test **/
    public function it_can_edit_an_address()
    {
        $address = factory(Address::class)->create();

        $this->visit(route('admin.addresses.edit', $address))
             ->see($address->line_1)
             ->type('My Road', 'line_2')
             ->press('Submit')
             ->seePageIs(route('admin.addresses.edit', $address))
             ->see('Address updated')
             ->see($address->line_2);
    }

    /** @test **/
    public function it_lists_venues()
    {
        $address = factory(Address::class)->create([
            'addressable_type' => 'App\User',
        ]);

        $venue = factory(Address::class)->create([
            'addressable_type' => 'App\Event',
        ]);

        $this->visit(route('admin.addresses.index'))
             ->see($venue->line_1)
             ->dontSee($address->line_1);
    }
}
