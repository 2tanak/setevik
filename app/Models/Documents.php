<?php

namespace App\Models;

use App\Role;
use App\Traits\Badgable;
use App\Traits\Taggable;
use App\Traits\FileableMulti;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use Badgable, Taggable, FileableMulti;

    protected $fillable = [
        'title',
        'detail',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @var array - links of menu contains badge
     */
    protected $badgeMenuLinks = [
        '/info/documents',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/public/documents/files';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($query) {
            $role = Role::where('slug', 'partner')->first();

            foreach ($role->users as $user) {
                $query->attachBadgeFor($user);
            }
        });

        static::deleting(function ($query) {
            $role = Role::where('slug', 'partner')->first();

            foreach ($role->users as $user) {
                $query->detachBadgeFor($user);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'document_file', 'document_id');
    }
}
