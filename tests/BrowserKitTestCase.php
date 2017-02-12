<?php

use Faker\Factory;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    use DatabaseSetup;

    protected $baseUrl;

    protected function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        // set bcrypt rounds to a low number to speed up password hashing
        Hash::setRounds(5);

        $this->baseUrl = \Config::get('app.url', 'http://homestead.app');

        return $app;
    }

    protected function logInAsAdmin(array $overrides = [])
    {
        return $this->loginWithUser($overrides, 'admin');
    }

    protected function loginWithUser(array $overrides = [], $role = 'subscriber')
    {
        $user = factory('App\User')->create($overrides);

        $user->assignRole($this->getRole($role)->name);

        $this->be($user);

        return $user;
    }


}
