<?php

namespace Integration;

use App\User;
use TestCase;
use App\Address;

class AddressTest extends TestCase
{
  /** @test **/
  public function it_shows_saved_addresses()
  {
    $user = $this->loginWithUser();
    $address = factory(Address::class)->create([
      'addressable_id' => $user->id,
    ]);
    $response = $this->get('account/addresses');
    $response->assertSee(htmlentities($address->line_1));
  }

  /** @test **/
  public function it_can_create_a_new_address()
  {
    $user = $this->loginWithUser();

    $response = $this->get(route('addresses.create'));
    $response->assertStatus(200);
    //  ->type('Mr Joe Bloggs', 'name')
    //  ->type('0123456789', 'phone')
    //  ->type('11 Acacia Avenue', 'line_1')
    //  ->type('London', 'city')
    //  ->type('SW1 4NQ', 'postcode')
    //  ->select('GB', 'country')
    //  ->press('Save Address')
    //  ->seePageIs('/account/addresses')
    //  ->see('saved')->see('11 Acacia Avenue');
  }

  /** @test **/
  public function it_deletes_an_address()
  {
    $user = $this->loginWithUser();
    $address = factory(Address::class)->create([
      'addressable_id' => $user->id,
    ]);

    $this->assertCount(1, $user->fresh()->addresses);

    $response = $this->get('account/addresses');
    $response->assertSee(htmlentities($address->postcode));
    //  ->press('Delete')
    //  ->seePageIs('account/addresses')
    //  ->see('Address Deleted');
    //->dontSee($address->postcode);

    // $this->assertCount(0, $user->fresh()->addresses);
  }

  /** @test **/
  public function it_allows_editing_an_address()
  {
    $user = $this->loginWithUser();
    $address = factory(Address::class)->create([
      'addressable_id' => $user->id,
    ]);
    $response = $this->get("account/addresses/{$address->id}/edit");
    $response->assertStatus(200);
  }

  /** @test **/
  public function it_does_not_allow_user_to_edit_another_users_address()
  {
    $user = $this->loginWithUser();
    $user_2 = factory(User::class)->create();
    $address = factory(Address::class)->create([
      'addressable_id' => $user_2->id,
    ]);
    $response = $this->get("account/addresses/{$address->id}/edit");
    $response->assertStatus(403);
  }

  /** @test **/
  public function it_geocodes_an_address_upon_creation_and_update()
  {
    $address = factory(Address::class)->create();
    $this->assertEquals(51.501364, $address->fresh()->lat);

    $addressToUpdate = Address::find($address->id);

    $addressToUpdate->update([
      'lat' => 1.342,
    ]);
    $this->assertEquals(51.501364, $addressToUpdate->fresh()->lat);
  }
}
