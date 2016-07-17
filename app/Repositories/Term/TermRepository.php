<?php

namespace App\Repositories\Term;

use App\Contracts\Termable;

interface TermRepository
{
    /**
     * @param $taxonomy
     *
     * @return mixed
     */
    public function getTerms($taxonomy);

    /**
     * Get all the categories.
     *
     * @return mixed
     */
    public function getCategories();

    /**
     * Get a list of all the categories.
     *
     * @param \App\Contracts\Termable $related_model If passed will put attached categories at the top of the list
     *
     * @return mixed
     */
    public function getCategoryList(Termable $related_model);

    /**
     * Get all the tags.
     *
     * @return mixed
     */
    public function getTags();

    /**
     * Get a list of all the tags.
     *
     * @return mixed
     */
    public function getTagList();

    /**
     * Process an array of mixed string and numneric terms, create a new term for each string.
     *
     * @param array  $terms    The terms to process
     * @param string $taxonomy The taxonomy of the terms in question
     *
     * @return array An array of the ids of terms, including the newly created ones
     */
    public function process($terms, $taxonomy = 'tag');
}
