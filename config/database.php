<?php

$dbOptions = [];
// Heroku specific env setup
if (getenv('DATABASE_URL')) {
  $url = parse_url(getenv('DATABASE_URL'));
  putenv("DB_HOST={$url['host']}");
  putenv("DB_USERNAME={$url['user']}");
  putenv("DB_PASSWORD={$url['pass']}");
  $db = substr($url['path'], 1);
  putenv("DB_DATABASE={$db}");
}

if (env('DB_SSL_CA')) {
  $dbOptions[PDO::MYSQL_ATTR_SSL_CA] = base_path(env('DB_SSL_CA'));
}

if (getenv('REDIS_URL')) {
  $url = parse_url(getenv('REDIS_URL'));
  putenv('REDIS_HOST=' . $url['host']);
  putenv('REDIS_PORT=' . $url['port']);
  putenv('REDIS_PASSWORD=' . $url['pass']);
}

return [
  /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

  'fetch' => PDO::FETCH_CLASS,

  /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

  'default' => env('DB_DRIVER', 'mysql'),

  /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

  'connections' => [
    'sqlite' => [
      'driver' => 'sqlite',
      'database' => storage_path(env('DB_PATH', 'database.sqlite')),
      'prefix' => '',
    ],

    'testing' => [
      'driver' => 'sqlite',
      'database' => storage_path('testing.sqlite'),
      'prefix' => '',
    ],

    'mysql' => [
      'driver' => 'mysql',
      'host' => env('DB_HOST', 'localhost'),
      'database' => env('DB_DATABASE', 'samarkand_dev'),
      'username' => env('DB_USERNAME', 'samarkand'),
      'password' => env('DB_PASSWORD', 'secret'),
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
      'strict' => false,
      'options' => $dbOptions,
    ],

    'pgsql' => [
      'driver' => 'pgsql',
      'host' => env('DB_HOST', 'localhost'),
      'database' => env('DB_DATABASE', 'forge'),
      'username' => env('DB_USERNAME', 'forge'),
      'password' => env('DB_PASSWORD', ''),
      'charset' => 'utf8',
      'prefix' => '',
      'schema' => 'public',
    ],

    'sqlsrv' => [
      'driver' => 'sqlsrv',
      'host' => env('DB_HOST', 'localhost'),
      'database' => env('DB_DATABASE', 'forge'),
      'username' => env('DB_USERNAME', 'forge'),
      'password' => env('DB_PASSWORD', ''),
      'prefix' => '',
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

  'migrations' => 'migrations',

  /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

  'redis' => [
    'cluster' => false,

    'default' => [
      'host' => env('REDIS_HOST', '127.0.0.1'),
      'port' => env('REDIS_PORT', 6379),
      'database' => 0,
      'password' => env('REDIS_PASSWORD'),
    ],
  ],
];
