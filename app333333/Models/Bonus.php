<?php

namespace App\Models;

use App\ExpectedWallet;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        'code',
        'name',
        'sort',
        'is_active',
        'is_missiable',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'is_missiable'  => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expectedwallets()
    {
        return $this->hasMany(ExpectedWallet::class);
    }
}
