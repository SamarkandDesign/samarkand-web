<?php

namespace App\Providers;

use App\Countries\CountryRepository;
use Illuminate\Support\ServiceProvider;
use App\Countries\FileCountryRepository;
use App\Countries\CacheCountryRepository;

class CountriesServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(CountryRepository::class, function () {
      return new CacheCountryRepository($this->app->make(FileCountryRepository::class));
    });
  }
}
