<?php

use App\Address;
use App\Services\Geocoder\GoogleGeocoder;
use GuzzleHttp\Client;

class GoogleGeocoderTest extends TestCase
{
    private $json = '{ "results" : [ { "address_components" : [ { "long_name" : "Buckingham Palace", "short_name" : "Buckingham Palace", "types" : [ "establishment", "point_of_interest" ] }, { "long_name" : "London", "short_name" : "London", "types" : [ "locality", "political" ] }, { "long_name" : "London", "short_name" : "London", "types" : [ "postal_town" ] }, { "long_name" : "Greater London", "short_name" : "Greater London", "types" : [ "administrative_area_level_2", "political" ] }, { "long_name" : "England", "short_name" : "England", "types" : [ "administrative_area_level_1", "political" ] }, { "long_name" : "United Kingdom", "short_name" : "GB", "types" : [ "country", "political" ] }, { "long_name" : "SW1A 1AA", "short_name" : "SW1A 1AA", "types" : [ "postal_code" ] } ], "formatted_address" : "Buckingham Palace, London SW1A 1AA, UK", "geometry" : { "location" : { "lat" : 51.501364, "lng" : -0.14189 }, "location_type" : "APPROXIMATE", "viewport" : { "northeast" : { "lat" : 51.50271298029149, "lng" : -0.140541019708498 }, "southwest" : { "lat" : 51.5000150197085, "lng" : -0.143238980291502 } } }, "place_id" : "ChIJtV5bzSAFdkgRpwLZFPWrJgo", "types" : [ "establishment", "point_of_interest" ] } ], "status" : "OK" }';

  /** @test **/
  public function it_geocodes_an_address()
  {
      $address = new Address([
      'line_1'   => 'Buckingham Palace',
      'city'     => 'London',
      'postcode' => 'SW1A 1AA',
      'country'  => 'GB',
      ]);

      $client = $this->getFakeClient();

      $geocoder = new GoogleGeocoder($client);

      $coordinates = $geocoder->getCoordinates($address);

      $this->assertEquals(51.501364, $coordinates->lat);
  }

  /** @test **/
  public function it_handles_a_non_geocoded_address()
  {
      $address = new Address([
      'line_1'   => 'Some',
      'line_2'   => 'Unknown',
      'city'     => 'Place',
      'postcode' => '',
      'country'  => 'GB',
      ]);

      $client = $this->getFakeClient(false);
      $geocoder = new GoogleGeocoder($client);

      $coordinates = $geocoder->getCoordinates($address);

      $this->assertNull($coordinates->lat);
  }

    private function getFakeClient($results = true)
    {
        $response = Mockery::mock([
    'getBody' => Mockery::mock([
      'getContents' => $results ? $this->json : '{ "results" : [], "status" : "ZERO_RESULTS" }',
      ]),
    ]);

        return Mockery::mock(Client::class, [
    'request' => $response,
    ]);
    }
}
