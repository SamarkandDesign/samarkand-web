<?php

namespace Integration;

use TestCase;
use App\ShippingMethod;

class ShippingMethodsTest extends TestCase
{
    /** @test **/
    public function it_shows_a_list_of_shipping_methods()
    {
        $this->logInAsAdmin();

        $shipping_method = factory(ShippingMethod::class)->create();

        $response = $this->get('admin/shipping-methods');
        $this->assertContains($shipping_method->description, $response->getContent());
    }

    /** @test **/
    public function it_creates_a_new_shipping_method()
    {
        $this->logInAsAdmin();

        $response = $this->followRedirects($this->post('admin/shipping-methods', [
             'description' => 'Express Shipping',
             'base_rate' => '5.40',
             'shipping_countries' => ['GB'],
            ]));

        $response->assertSee('Shipping Method Saved');
        $response->assertSee('Express Shipping');

        $shipping_method = ShippingMethod::where('description', 'Express Shipping')->first();

        $this->assertTrue($shipping_method->allowsCountry('GB'));
    }

    /** @test **/
    public function it_can_delete_a_shipping_method()
    {
        $this->logInAsAdmin();

        $shipping_method = factory(ShippingMethod::class)->create();

        $response = $this->delete("admin/shipping-methods/{$shipping_method->id}");

        $response->assertRedirect('admin/shipping-methods');

        $this->assertDatabaseMissing('shipping_methods', ['description' => $shipping_method->description]);
    }

    /** @test */
    public function it_edits_a_shipping_method()
    {
        $this->logInAsAdmin();

        $shipping_method = factory(ShippingMethod::class)->create();

        $response = $this->get("admin/shipping-methods/{$shipping_method->id}/edit");
        $response = $this->followRedirects($this->patch("admin/shipping-methods/{$shipping_method->id}", [
             'description' => 'Awesome Shipping',
             'base_rate' => '8.40',
             'shipping_countries' => ['GB'],
            ]));

        $this->assertDatabaseHas('shipping_methods', ['description' => 'Awesome Shipping']);
        $this->assertDatabaseMissing('shipping_methods', ['description' => $shipping_method->description]);
    }
}
