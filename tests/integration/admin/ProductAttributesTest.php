<?php

namespace App\Http\Controllers\Admin;

class ProductAttributesTest extends \TestCase
{
    /** @test **/
    public function it_can_view_a_list_of_custom_attributes()
    {
        $property_1 = factory('App\ProductAttribute')->create([
            'name' => 'Lampshade Size',
            ]);

        $property_2 = factory('App\ProductAttribute')->create([
            'name' => 'Lampshade Colour',
            ]);

        $this->logInAsAdmin();

        $response = $this->get('admin/attributes');
        $this->assertContains('Lampshade Size', $response->getContent());
        $this->assertContains('Lampshade Colour', $response->getContent());
    }

    /** @test **/
    public function it_updates_an_attribute()
    {
        $this->markTestSkipped();
        $this->logInAsAdmin();

        $property = factory('App\AttributeProperty')->create([
            'name' => 'Lampshade Size',
            ]);

        $response = $this->get("admin/attributes/{$property->id}/edit")
             ->see('Edit Attribute')
             ->type(3, 'order')
             ->press('Update')
             ->seePageIs("admin/attributes/{$property->id}/edit");

        $this->assertDatabaseHas('product_attributes', ['id' => $property->id, 'order' => 3]);
    }

    /** @test **/
    public function it_deletes_all_terms_for_a_given_attribute()
    {
        $this->logInAsAdmin();

        $property = factory('App\AttributeProperty')->create([
            'name' => 'Lampshade Size',
            ]);

        $response = $this->call('DELETE', "admin/attributes/{$property->id}");

        $response->assertRedirect('admin/attributes');
        $this->assertDatabaseMissing('product_attributes', ['slug' => 'lampshade_size']);
    }
}
