<?php

use App\User;
use App\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();

        Product::flushEventListeners();

        foreach (range(1, 15) as $index) {
            factory(Product::class)->create([
                'user_id' => $faker->randomElement($userIds),
                ]);
        }
    }
}
