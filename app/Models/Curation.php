<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class Curation extends Model
{
    protected $fillable = [
        'user_id',
        'curator_id',
        'cabinet_id',
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
    public function curator()
    {
        return $this->belongsTo(User::class, 'curator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
