<?php

use App\ProductAttribute;
use App\AttributeProperty;
use Illuminate\Database\Seeder;

class AttributePropertiesTableSeeder extends Seeder
{
    public function run()
    {
        $attributes = ProductAttribute::all();
        $attributes->map(function($attr) {
          $prop = factory(AttributeProperty::class, intval(mt_rand(0, 4), 10))->create(['product_attribute_id' => $attr->id]);
        });
    }
}
