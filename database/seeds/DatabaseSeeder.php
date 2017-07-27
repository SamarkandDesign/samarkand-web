<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $tables = [
    'users',
    'terms',
    'pages',
    'products',
    'posts',
    'termables',
    'product_attributes',
    'product_attributes',
    'attribute_properties',
    'roles',
    'media',
    'shipping_methods',
    'orders',
    'order_items',
    'addresses',
    'menu_items',
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
      $this->cleanDatabase();
      $this->command->getOutput()->writeln('Truncated Tables');

      Model::unguard();

      $this->call('UsersTableSeeder');
      $this->call('PostsTableSeeder');
      $this->call('PagesTableSeeder');
      $this->call('ProductsTableSeeder');
      $this->call('TermsTableSeeder');
      $this->call('ProductAttributesTableSeeder');
      $this->call('AttributePropertiesTableSeeder');
      $this->call('TermablesTableSeeder');
      $this->call('AddressesTableSeeder');
      $this->call('ShippingMethodsTableSeeder');
      $this->call('OrdersTableSeeder');
      $this->call('MenuItemsTableSeeder');

      $this->command->getOutput()->writeln('Flushing Cache');
      \Cache::flush();
  }

  /**
   * Remove any data currently in the database tables.
   */
  protected function cleanDatabase()
  {
      $this->disableForeignKeyCheck();
      foreach ($this->tables as $table) {
          DB::table($table)->truncate();
      }
    // DB::statement('TRUNCATE TABLE '.implode(',', $this->tables).' CASCADE;');
      $this->enableForeignKeyCheck();
  }

    protected function disableForeignKeyCheck()
    {
        if ($statement = $this->getForeignKeyCheckStatement()) {
            DB::statement($statement['disable']);
        }
    }

    protected function enableForeignKeyCheck()
    {
        if ($statement = $this->getForeignKeyCheckStatement()) {
            DB::statement($statement['enable']);
        }
    }

    protected function getForeignKeyCheckStatement()
    {
        $driver = \DB::connection()->getDriverName();

        $statements = [
      'sqlite' => [
        'disable' => 'PRAGMA foreign_keys = OFF',
        'enable'  => 'PRAGMA foreign_keys = ON',
      ],
      'mysql' => [
        'disable' => 'SET FOREIGN_KEY_CHECKS=0',
        'enable'  => 'SET FOREIGN_KEY_CHECKS=1',
      ],
    ];

        return array_get($statements, $driver, false);
    }
}
