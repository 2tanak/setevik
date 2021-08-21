<?php

namespace App\Models;

use App\User;
use App\Observers\SubscriptionObserver;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'started_at',
        'expired_at',
    ];

    protected $dates = [
        'started_at',
        'expired_at',
    ];

    protected static function boot()
    {
        parent::boot();
        Subscription::observe(SubscriptionObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
