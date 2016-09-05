<?php 

namespace App\Services\Geocoder;

class Location {
	public $lat;
	public $lng;

	public function __construct($lat, $lng)
	{
		$this->lat = $lat;
		$this->lng = $lng;
	}

	public function __toString()
	{
		return sprintf('%s, %s', $this->lat, $this->lng);
	}
}

