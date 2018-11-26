<?php

class DatabaseTest extends TestCase
{
  /** @test */
  public function it_can_migrate_and_refresh_the_database()
  {
    Artisan::call('migrate');
    Artisan::call('migrate:refresh');
  }
}
