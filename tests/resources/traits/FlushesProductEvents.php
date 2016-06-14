<?php

use App\Product;

trait FlushesProductEvents
{
    /**
     * @before
     */
    protected function flushProductEvents()
    {
        Product::flushEventListeners();
    }
}
