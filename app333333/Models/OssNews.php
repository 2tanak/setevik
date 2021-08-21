<?php

namespace App\Models;

use App\Role;
use App\User;
use App\Traits\Badgable;
use App\Traits\Taggable;
use App\Traits\Newsable;
use App\Observers\NewsObserver;
use App\Jobs\NewsUser;

use Illuminate\Database\Eloquent\Model;

class OssNews extends Model
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
        '/oss/info/news',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/public/oss-news/files';

    public static function boot()
    {
        parent::boot();
		//OssNews::observe(NewsObserver::class);

       /*
        static::created(function ($query) {
			NewsUser::dispatch($query);
            //$query->attachBadgeFor(User::all());
        });
		*/

//        static::created(function ($query) {
//            //$role = Role::where('slug', 'partner')->first();
////            $role = Role::where('slug', 'admin')->first();
////
////            foreach ($role->users as $user) {
////                $query->attachBadgeFor($user);
////            }
//        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'file_oss_news', 'oss_news_id');
    }
}
