<?php

namespace App;

class Contact
{
    private $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }
}
