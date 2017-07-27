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

        $response = $this->get(route('admin.addresses.edit', $address));

        $response->assertSee($address->line_1);

        $response = $this->patch("/account/addresses/{$address->id}", array_merge($address->toArray(), [
            'line_2' => 'My Road',
            ]));

        $response->assertRedirect(route('admin.addresses.edit', $address));
        $this->followRedirects($response)->assertSee('Address Updated');
        $this->followRedirects($response)->assertSee('My Road');
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

        $response = $this->get(route('admin.addresses.index'));
        $this->assertContains($venue->line_1, $response->getContent());
        $this->assertNotContains($address->line_1, $response->getContent());
    }
}
