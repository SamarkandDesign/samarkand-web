<?php

namespace App\Services\Geocoder;

use App\Address;

interface Geocoder
{
    public function getCoordinates(Address $address);
}
