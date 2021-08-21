<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'user_id',
        'badgable_id',
        'badgable_type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function badgable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
