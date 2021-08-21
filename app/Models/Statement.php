<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Money Statement
 * @package App\Models
 */
class Statement extends Model
{
    protected $fillable = [
        'user_id',
        'initiator_id',
        'balance_begin',
        'amount',
        'balance_end',
        'bonus_id',
        'package_id',
        'level',
        'team_id',
    ];

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
    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
