<?php

use App\User;
use App\Address;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
  public function run()
  {
    $faker = Faker::create();
    $userIds = User::pluck('id')->toArray();

    Address::flushEventListeners();

    foreach (range(1, 15) as $i) {
      factory(Address::class)->create([
        'addressable_id' => $faker->randomElement($userIds),
      ]);
    }
  }
}
