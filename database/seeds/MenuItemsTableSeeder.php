<?php

use App\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
    public function run()
    {
        $links = [
            ['menu' => 'main', 'label' => 'Home', 'link' => '/'],
            ['menu' => 'main', 'label' => 'About', 'link' => '/about'],
            ['menu' => 'main', 'label' => 'FAQ', 'link' => '/faq'],
        ];

        $ids = array_map(function($link) {
            return MenuItem::create($link);
        }, $links);
    }
}
