<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'user_id',
        'node_id',
        'product_id',
        'category',
        'code',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
