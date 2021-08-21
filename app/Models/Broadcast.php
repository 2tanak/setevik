<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = [
        'product_id',
        'link',
        'started_at',
        'expired_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'expired_at'  => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
