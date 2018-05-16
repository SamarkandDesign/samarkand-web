<?php

use App\Page;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();
        $pages = ['About', 'FAQ'];

        collect($pages)->map(function ($page) use ($faker, $userIds) {
            factory(Page::class)->create([
                'title' => $page,
                'slug' => str_slug($page),
                'user_id' => $faker->randomElement($userIds),
            ]);
        });
    }
}
