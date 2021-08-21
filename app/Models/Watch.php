<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    protected $fillable = [
        'user_id',
        'watchable_id',
        'watchable_type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function watchable()
    {
        return $this->morphTo();
    }
}
