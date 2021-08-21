<?php

namespace App\Models;

use App\User;

use Illuminate\Database\Eloquent\Model;

class LearnVideoConfirm extends Model
{
    protected $fillable = [
        'learn_video_id',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(LearnVideo::class, 'learn_video_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
