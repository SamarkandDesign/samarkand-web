<?php

use App\Term;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    public function run()
    {
        $this->createUncategorisedCategories();

        $faker = Faker::create();
        $taxonomies = ['category', 'tag'];

        collect(['Lampshades', 'Cushions', 'Kanthas', 'Home Furnishings', 'Lighting'])->map(function ($attr) {
            return factory(Term::class)->create([
              'term' => $attr,
              'slug' => str_slug($attr),
              'taxonomy' => 'product_category',
            ]);
        });
    }

    private function createUncategorisedCategories()
    {
        Term::create([
            'taxonomy' => 'category',
            'term'     => 'Uncategorised',
            'slug'     => 'uncategorised',
            ]);
    }
}
