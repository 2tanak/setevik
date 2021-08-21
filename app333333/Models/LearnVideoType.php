<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnVideoType extends Model
{
    protected $fillable = [
        'slug',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->hasMany(LearnVideo::class);
    }
}
