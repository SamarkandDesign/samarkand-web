<?php

use App\Services\Geocoder\Location;

class LocationTest extends TestCase {
	/** @test **/
	public function it_stringifies_a_location()
	{
		$location = new Location(51.501364, -0.14189);

		$this->assertEquals('51.501364, -0.14189', (string) $location);
	}
}