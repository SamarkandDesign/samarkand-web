<?php

use Carbon\Carbon;

$factory->define('App\Post', function ($faker) {
  $creationDate = $faker->dateTimeThisMonth();
  $title = $faker->sentence;

  return [
    'title' => $title,
    'content' => $faker->paragraph,
    'slug' => str_slug($title),
    'published_at' => $creationDate,
    'created_at' => $creationDate,
    'updated_at' => $creationDate,
    'user_id' => function () {
      return factory(App\User::class)->create()->id;
    },
  ];
});

$factory->define('App\Page', function ($faker) {
  $creationDate = $faker->dateTimeThisMonth();
  $title = $faker->sentence;

  return [
    'title' => $title,
    'content' => $faker->paragraph,
    'meta_description' => $faker->sentence,
    'slug' => str_slug($title),
    'published_at' => $creationDate,
    'created_at' => $creationDate,
    'updated_at' => $creationDate,
    'user_id' => function () {
      return factory(App\User::class)->create()->id;
    },
  ];
});

$factory->define('App\Product', function ($faker) {
  $name = $faker->sentence(3);
  $price = $faker->numberBetween(30, 30000);

  return [
    'name' => $name,
    'slug' => str_slug($name),
    'sku' => strtoupper($faker->unique()->bothify('???###')),
    'description' => $faker->paragraph(3),
    'price' => $price / 100,
    'sale_price' => $faker->randomElement([null, $faker->numberBetween(2, $price - 1) / 100]),
    'stock_qty' => $faker->numberBetween(1, 15),
    'user_id' => function () {
      return factory(App\User::class)->create()->id;
    },
    'location' => $faker->randomElement(['HQD', 'The Shire']),
    'listed' => true,
  ];
});

$factory->define('App\User', function ($faker) {
  $name = $faker->unique()->name;

  return [
    'name' => $name,
    'username' => str_slug($name),
    'email' => $faker->unique()->email,
    'password' => 'password',
    'last_seen_at' => new \DateTime(),
  ];
});

$factory->define('App\Role', function ($faker) {
  $name = $faker->unique()->word;

  return [
    'name' => str_slug($name),
    'display_name' => $name,
    'description' => $faker->sentence,
  ];
});

$factory->define('App\Term', function ($faker) {
  $term = $faker->unique()->word;
  if (strlen($term) <= 4) {
    $term .= ' ' . $faker->word;
  }

  return [
    'taxonomy' => 'category',
    'term' => $term,
    'slug' => str_slug($term),
  ];
});

$factory->define('App\ProductAttribute', function ($faker) {
  $term = $faker->unique()->word;
  if (strlen($term) <= 4) {
    $term .= ' ' . $faker->word;
  }

  return [
    'name' => $term,
    'slug' => str_slug($term),
  ];
});

$factory->define('App\AttributeProperty', function ($faker) {
  $name = $faker->word;
  if (strlen($name) <= 4) {
    $name .= ' ' . $faker->word;
  }

  return [
    'name' => $name,
    'slug' => str_slug($name),
    'product_attribute_id' => function () {
      return factory(App\ProductAttribute::class)->create()->id;
    },
  ];
});

$factory->define('App\Termable', function ($faker) {
  return [
    'term_id' => function () {
      return factory(App\Term::class)->create()->id;
    },
    'termable_id' => function () {
      return factory(App\Post::class)->create()->id;
    },
    'termable_type' => 'App\Post',
  ];
});

$factory->define('App\OrderItem', function ($faker) {
  $product = factory(App\Product::class)->create(['stock_qty' => 20]);

  return [
    'order_id' => function () {
      return factory('App\Order')->create()->id;
    },
    'description' => $product->name,
    'price_paid' => $product->getPrice()->value(),
    'quantity' => 1,
    'orderable_type' => App\Product::class,
    'orderable_id' => $product->id,
  ];
});

$factory->define('App\Order', function ($faker) {
  $user_id = factory('App\User')->create()->id;
  $address_id = factory('App\Address')->create(['addressable_id' => $user_id])->id;
  $shipping_address_id = mt_rand(0, 1)
    ? $address_id
    : factory('App\Address')->create(['addressable_id' => $user_id])->id;

  return [
    'user_id' => $user_id,
    'billing_address_id' => $address_id,
    'shipping_address_id' => $shipping_address_id,
    'status' => $faker->randomElement(array_keys(App\Order::$statuses)),
  ];
});

$factory->define('App\Address', function ($faker) {
  return [
    'addressable_id' => function () {
      return factory('App\User')->create()->id;
    },
    'addressable_type' => 'App\User',
    'name' => $faker->name,
    'phone' => $faker->phoneNumber,
    'line_1' => $faker->buildingNumber . ' ' . $faker->streetName,
    'city' => $faker->city,
    'postcode' => $faker->postcode,
    'country' => $faker->countryCode,
  ];
});

$factory->define('App\Event', function ($faker) {
  $title = $faker->sentence;

  return [
    'title' => $title,
    'slug' => str_slug($title),
    'description' => $faker->paragraph,
    'start_date' => Carbon::now()->addDays($faker->numberBetween(2, 20)),
    'end_date' => Carbon::now()->addDays($faker->numberBetween(21, 30)),
    'all_day' => $faker->boolean,
    'address_id' => function () {
      return factory('App\Address')->create([
        'addressable_type' => 'App\Event',
        'addressable_id' => 1,
      ])->id;
    },
  ];
});

$factory->define('App\MenuItem', function ($faker) {
  $label = $faker->sentence(2, true);

  return [
    'label' => $label,
    'link' => sprintf('/%s', str_slug($label)),
    'order' => $faker->randomDigit(),
    'menu' => $faker->randomElement(['main', 'footer_left', 'footer_right']),
  ];
});

$factory->define('App\ShippingMethod', function ($faker) {
  return [
    'description' => $faker->sentence,
    'base_rate' => $faker->numberBetween(100, 600),
    'min_order_amount' => 0,
  ];
});

$factory->define('App\ShippingCountry', function ($faker) {
  return [
    'country_id' => 'GB',
    'shipping_method_id' => function () {
      return factory('App\ShippingMethod')->create();
    },
  ];
});
