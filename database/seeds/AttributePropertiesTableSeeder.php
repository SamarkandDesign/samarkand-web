<?php

use App\AttributeProperty;
use App\ProductAttribute;
use Illuminate\Database\Seeder;

class AttributePropertiesTableSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        // $attributes = ProductAttribute::all();
        // $attributes->map(function($attr) {
        //   $prop = factory(AttributeProperty::class, intval(mt_rand(0, 4), 10))->create(['product_attribute_id' => $attr->id]);
        // });
=======
        $attributes = ProductAttribute::all();
        $attributes->map(function ($attr) {
            $prop = factory(AttributeProperty::class, intval(mt_rand(0, 4), 10))->create(['product_attribute_id' => $attr->id]);
        });
>>>>>>> origin/attributes
    }
}
