<?php

namespace App\Services\Geocoder;

use App\Address;

class FakeGeocoder implements Geocoder
{
  public function getCoordinates(Address $address)
  {
    // Location of buckingham palace just for faking
    return new Location(51.501364, -0.14189);
  }
}
