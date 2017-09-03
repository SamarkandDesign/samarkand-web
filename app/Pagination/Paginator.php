<?php

namespace App\Pagination;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Make an iterable paginator from a collection of results.
     *
     * @param Arrayable $results
     *
     * @return LengthAwarePaginator
     */
    public function make($results)
    {
        $currentPage = $this->request->get('page', 1);
        $results = collect($results);
        $perPage = config('shop.products_per_page');

        $paginator = new LengthAwarePaginator($results->slice(($currentPage - 1) * $perPage, $perPage), $results->count(), $perPage, $currentPage, [
          'path'     => '/'.$this->request->path(),
          'pageName' => 'page',
      ]);
        $paginator->appends('query', $this->request->get('query'));

        return $paginator;
    }
}
