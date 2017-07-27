<?php

namespace App\Listeners;

use Spatie\SearchIndex\Searchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveModelFromSearchIndex implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param \Spatie\SearchIndex\Searchable $model
     *
     * @return void
     */
    public function handle(Searchable $model)
    {
        \SearchIndex::removeFromIndex($model);
    }
}
