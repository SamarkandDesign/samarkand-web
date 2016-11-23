<?php

use Illuminate\Contracts\Console\Kernel;

trait DatabaseSetup
{
    protected static $migrated = false;

    public function setupDatabase()
    {
        if ($this->isInMemory()) {
            $this->setupInMemoryDatabase();
        } else {
            $this->setupTestDatabase();
        }
    }

    protected function isInMemory()
    {
        return config('database.connections')[config('database.default')]['database'] == ':memory:';
    }

    protected function setupInMemoryDatabase()
    {
        $this->artisan('migrate');
        $this->app[Kernel::class]->setArtisan(null);
    }

    protected function setupTestDatabase()
    {
        if (!static::$migrated) {
            $this->setUpSqliteDatabase();
            $this->artisan('migrate:refresh');
            $this->app[Kernel::class]->setArtisan(null);
            static::$migrated = true;
        }
        $this->beginDatabaseTransaction();
    }

    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $database->connection($name)->rollBack();
            }
        });
    }

    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
                            ? $this->connectionsToTransact : [null];
    }

    public function setUpSqliteDatabase()
    {
        $databaseInUse = config('database.connections')[config('database.default')];
        if ($databaseInUse['driver'] === 'sqlite') {
            shell_exec(sprintf('echo \'\' > %s', $databaseInUse['database']));
        }
    }
}
