<?php

namespace App\Models;

use App\Traits\Ownable;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use Ownable;

    protected $fillable = [
        'user_id',
        'link_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
