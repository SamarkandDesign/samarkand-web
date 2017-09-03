<?php

namespace App\Repositories\Term;

use App\Term;
use App\Contracts\Termable;
use Illuminate\Database\Eloquent\Model;

class CacheTermRepository implements TermRepository
{
    protected $terms;

    protected $cacheTime = 0;

    public function __construct(TermRepository $terms)
    {
        $this->terms = $terms;
        $this->cacheTime = config('cache.time');
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->terms->getTerms('category');
    }

    /**
     * Get a list of categories for use, say, in a checklist or multi-select.
     *
     * @param Termable $related_model If provided, categories attached to the model will be ordered at the top of the list
     *
     * @return array
     */
    public function getCategoryList(Termable $related_model = null)
    {
        $cacheString = is_null($related_model) ? 'categoryList' : "categoryList.{$related_model->id}";

        return \Cache::tags('terms')->remember($cacheString, $this->cacheTime, function () use ($related_model) {
            return $this->terms->getCategoryList($related_model);
        });
    }

    /**
     * @param $taxonomy
     *
     * @return mixed
     */
    public function getTerms($taxonomy)
    {
        return \Cache::tags('terms')->remember("terms.$taxonomy", $this->cacheTime, function () use ($taxonomy) {
            return $this->terms->getTerms($taxonomy);
        });
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->getTerms('tag');
    }

    /**
     * @return array
     */
    public function getTagList()
    {
        return $this->getTags()->pluck('term', 'id');
    }

    /**
     * Process an array of mixed string and numneric terms, create a new term for each string.
     *
     * @param array  $terms    The terms to process
     * @param string $taxonomy The taxonomy of the terms in question
     *
     * @return array An array of the ids of terms, including the newly created ones
     */
    public function process($terms, $taxonomy = 'tag')
    {
        return $this->terms->process($terms, $taxonomy);
    }
}
