<?php

namespace App\Models;

use App\User;
use App\Traits\Treeable;
use App\Traits\Watchable;
use App\Traits\Cloudable;

use Illuminate\Database\Eloquent\Model;

class LearnVideo extends Model
{
    use Treeable, Watchable, Cloudable;

    protected $fillable = [
        'parent_id',
        'learn_video_type_id',
        'file_id',
        'title',
        'description',
        'speaker',
    ];

    /**
     * Default directory in storage
     *
     * @var string
     */
    protected $defaultDir = '/attestation';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(LearnVideoType::class, 'learn_video_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirms()
    {
        return $this->hasMany(LearnVideoConfirm::class);
    }

    /**
     * Check confirmation
     *
     * @param User|int $user
     * @return mixed
     */
    public function isConfirmedBy($user)
    {
        return $this->confirms->contains('user_id', ($user instanceof User) ? $user->id : $user);
    }
}
