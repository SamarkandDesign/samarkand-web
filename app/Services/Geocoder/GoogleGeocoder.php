<?php

namespace App\Services\Geocoder;

use App\Address;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use GuzzleHttp\Exception\RequestException;

class GoogleGeocoder implements Geocoder
{
  protected $client;
  protected $key;

  public function __construct(Client $client)
  {
    $this->client = $client;
    $this->key = config('services.google.server_key');
  }

  public function getCoordinates(Address $address)
  {
    try {
      $res = $this->client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
        'query' => [
          'address' => $this->getAddressQueryString($address),
          'key' => $this->key,
        ],
      ]);

      return $this->decodeCoordinates($res->getBody()->getContents());
    } catch (RequestException $e) {
      \Log::error('Failed to geocode address: ' . $e->getMessage());

      return new Location();
    }
  }

  protected function decodeCoordinates($json)
  {
    $result = new Collection(array_get(json_decode($json, true), 'results.0.geometry.location'));

    return new Location($result->get('lat'), $result->get('lng'));
  }

  protected function getAddressQueryString(Address $address)
  {
    return (new Collection([
      $address->line_1,
      $address->line_2,
      $address->line_3,
      $address->city,
      $address->postcode,
      $address->country,
    ]))
      ->filter()
      ->implode(', ');
  }
}
