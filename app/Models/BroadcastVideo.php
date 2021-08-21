<?php

namespace App\Models;

use App\Traits\Cloudable;
use App\Traits\Watchable;

use Illuminate\Database\Eloquent\Model;

class BroadcastVideo extends Model
{
    use Cloudable, Watchable;

    protected $fillable = [
        'file_id',
        'title',
        'description',
        'speaker',
        'date',
        'started_at',
        'expired_at',
        'is_available',
    ];

    protected $casts = [
        'date'          => 'datetime',
        'started_at'    => 'datetime',
        'expired_at'    => 'datetime',
        'is_available'  => 'boolean',
        'description'   => 'string',
        'speaker'       => 'string',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/wakeupera';

}
