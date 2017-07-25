<?php

namespace App\Jobs;

use App\Address;
use Illuminate\Bus\Queueable;
use App\Services\Geocoder\Geocoder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeocodeAddress implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $address;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Geocoder $geocoder)
    {
        $location = $geocoder->getCoordinates($this->address);

        \DB::table('addresses')
            ->where('id', $this->address->id)
            ->update([
                'lat' => $location->lat,
                'lng' => $location->lng,
                ]);
    }
}
