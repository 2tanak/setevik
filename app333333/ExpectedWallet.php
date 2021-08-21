<?php

namespace App;

use App\Models\Product;
use App\User;
use App\Models\Bonus;
use App\Models\Wallet;
use App\Observers\WalletObserver;
use Illuminate\Database\Eloquent\Model;

class ExpectedWallet extends Model
{
    protected $fillable = [
        'user_id',
        'bonus_id',
        'red_bonus_expected',
        'expected',
        'upd_expected',
        'status',
        'customer_id',
        'product_id',
        'description',
        'level',
        'type',
    ];

    protected $casts = [
        'red_bonus_expected'  => 'integer',
        'expected'  => 'double',
        'upd_expected'  => 'double',
        'level'  => 'integer',
        'type'  => 'integer',
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
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }
}
