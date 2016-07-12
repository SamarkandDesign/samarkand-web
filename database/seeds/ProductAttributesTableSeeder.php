<?php

use App\ProductAttribute;
use Illuminate\Database\Seeder;

class ProductAttributesTableSeeder extends Seeder
{
    public function run()
    {
        $attributes = collect(['Size', 'Length', 'Colour', 'Top Speed'])->map(function ($attr) {
            return factory(ProductAttribute::class)->create([
              'name' => $attr
            ]);
        });
    }
}
