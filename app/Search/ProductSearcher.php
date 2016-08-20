<?php

namespace App\Search;

use App\Product;

class ProductSearcher extends Searcher implements SearcherContract
{
    /**
     * Get the type of entity to search for.
     * The search will filter the query to only get entities of the given type.
     *
     * @return string
     */
    protected function getSearchableType()
    {
        return 'product';
    }


    public function getResults()
    {
        return parent::getResults();
        // ->map(function($attributes) {
        //     return new Product($attributes);
        // });
    }
}
