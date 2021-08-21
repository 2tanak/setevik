<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    const ADMIN    = 1;
    const SIB      = 2;
    const OSS      = 3;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
