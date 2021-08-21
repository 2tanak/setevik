<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'cabinet_id',
        'sale_id',
        'date_begin',
        'date_end',
    ];

    protected $casts = [
        'date_begin'    => 'datetime',
        'date_end'      => 'datetime',
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
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
