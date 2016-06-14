<?php

namespace App;

use Spatie\MediaLibrary\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $fillable = ['custom_properties', 'collection_name', 'name', 'order_column', 'size', 'manipulations'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['model'];

    /**
     * The attributes to include in json responses and toArray calls.
     *
     * @var array
     */
    protected $appends = ['url', 'thumbnail_url'];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->getUrl('thumb');
    }
}
