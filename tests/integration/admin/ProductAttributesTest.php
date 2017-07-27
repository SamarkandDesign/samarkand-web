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

        $this->visit('admin/attributes')
             ->see('Lampshade Size')
             ->see('Lampshade Colour');
    }

    /** @test **/
    public function it_updates_an_attribute()
    {
        $this->logInAsAdmin();

        $property = factory('App\AttributeProperty')->create([
            'name' => 'Lampshade Size',
            ]);

        $this->visit("admin/attributes/{$property->id}/edit")
             ->see('Edit Attribute')
             ->type(3, 'order')
             ->press('Update')
             ->seePageIs("admin/attributes/{$property->id}/edit");

        $this->seeInDataBase('product_attributes', ['id' => $property->id, 'order' => 3]);
    }

    /** @test **/
    public function it_deletes_all_terms_for_a_given_attribute()
    {
        $this->logInAsAdmin();

        $property = factory('App\AttributeProperty')->create([
            'name' => 'Lampshade Size',
            ]);

        $this->call('DELETE', "admin/attributes/{$property->id}");

        $this->assertRedirectedTo('admin/attributes');
        $this->notSeeInDatabase('product_attributes', ['slug' => 'lampshade_size']);
    }
}
