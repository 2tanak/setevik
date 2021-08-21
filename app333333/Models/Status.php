<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'short_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
