<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawOrdersStatus extends Model
{
    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'expected'  => 'integer',
    ];
}
