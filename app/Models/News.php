<?php

namespace App\Models;

use App\Role;
use App\Traits\Badgable;
use App\Traits\Taggable;
use App\Traits\Newsable;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Badgable, Taggable, Newsable;

    protected $fillable = [
        'announce_pic_id',
        'detail_pic_id',
        'title',
        'announce',
        'detail',
        'is_active',
        'is_badgable',
        'started_at',
        'expired_at',
        'files',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'started_at'    => 'datetime',
        'expired_at'    => 'datetime',
    ];

    /**
     * @var array - links of menu contains badge
     */
    protected $badgeMenuLinks = [
        '/info/news',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/public/news/files';

    protected static function boot()
    {
        parent::boot();

/*
        static::created(function ($query) {
            $role = Role::where('slug', 'partner')->first();

            foreach ($role->users as $user) {
                $query->attachBadgeFor($user);
            }
			
        });
		*/
//        static::created(function ($query) {
//            //$role = Role::where('slug', 'partner')->first();
//            $role = Role::where('slug', 'partner')->first();
//
//            foreach ($role->users as $user) {
//                $query->attachBadgeFor($user);
//            }
//        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'file_news', 'news_id');
    }
}
