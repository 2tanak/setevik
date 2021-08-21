<?php

namespace App\Models;

use App\Role;
use App\Traits\Badgable;
use App\Traits\Newsable;
use App\Traits\FileableMulti;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 */
class Event extends Model
{
    use Badgable, Newsable;

    protected $fillable = [
        'announce_pic_id',
        'detail_pic_id',
        'title',
        'announce',
        'detail',
        'is_active',


        'files',
        'price',
        'date',
        'format',
        'location',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
//        'started_at'    => 'datetime',
//        'expired_at'    => 'datetime',
        'price'         => 'string',
        'data'          => 'string',
        'format'        => 'string',
        'location'      => 'string',
    ];

    /**
     * @var array - links of menu contains badge
     */
    protected $badgeMenuLinks = [
        '/info/events',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/public/events/files';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($query) {
            $role = Role::where('slug', 'partner')->first();

            foreach ($role->users as $user) {
                $query->attachBadgeFor($user);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'event_file', 'event_id');
    }
}
