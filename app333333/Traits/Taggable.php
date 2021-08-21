<?php

namespace App\Traits;

use App\Models\Tag;

trait Taggable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}