<?php

namespace App;

use App\Traits\Postable;
use App\Traits\ConvertsMedia;
use App\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Page extends \Baum\Node implements HasMediaConversions
{
    use Postable, SoftDeletes, PresentableTrait, HasMediaTrait, ConvertsMedia;

    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'meta_description', 'user_id', 'post_id', 'type', 'status', 'published_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'published_at', 'deleted_at'];

    protected $presenter = 'App\Presenters\PostPresenter';

    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function media()
    {
        return $this->morphMany(config('laravel-medialibrary.media_model'), 'model')->orderBy('order_column', 'ASC');
    }

    /**
     * Get the full url path to the page, based on its page hierarchy.
     *
     * @param bool $excludeSelf Exclude the slug of the current page.
     *
     * @return string
     */
    public function getPath($excludeSelf = false)
    {
        return $this->ancestors()
                    ->pluck('slug')
                    ->merge($excludeSelf ? [] : [$this->slug])
                    ->implode('/');
    }

    /**
     * Make a page a child of another page by ID.
     *
     * @param int $id
     *
     * @return Page
     */
    public function makeChildOfPage($id)
    {
        $page = static::findOrFail($id);

        return $this->makeChildOf($page);
    }
}
