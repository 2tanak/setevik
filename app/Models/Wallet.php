<?php

namespace App\Models;

use App\User;
use App\Observers\WalletObserver;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'bonus_id',
        'earned',
        'expected',
        'available',
    ];

    protected $casts = [
        'earned'    => 'double',
        'expected'  => 'double',
        'available' => 'double',
    ];

    public static function boot()
    {
        parent::boot();
        Wallet::observe(WalletObserver::class);
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
    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }
}
