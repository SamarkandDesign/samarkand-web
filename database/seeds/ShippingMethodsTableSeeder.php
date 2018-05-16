<?php

use Illuminate\Database\Seeder;

class ShippingMethodsTableSeeder extends Seeder
{
    public function run()
    {
        $method1 = App\ShippingMethod::create([
            'description'   => 'UK Shipping',
            'base_rate'     => 4.00,
        ]);

        $method2 = App\ShippingMethod::create([
            'description'   => '1st Class Shipping',
            'base_rate'     => 6.50,
        ]);

        App\ShippingCountry::create([
            'country_id' => 'GB',
            'shipping_method_id' => $method1->id,
        ]);
    }
}
