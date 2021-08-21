<?php

namespace App\Traits;

use App\User;
use App\Models\Watch;

trait Watchable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function watches()
    {
        return $this->morphMany(Watch::class, 'watchable');
    }

    /**
     * Make watched
     *
     * @param User|int $user
     */
    public function watchedBy($user)
    {
        $watch = new Watch();
        $watch->user_id = ($user instanceof User) ? $user->id : $user;
        $watch->watchable()->associate($this)->save();
    }

    /**
     * Consider only unique watches
     *
     * @param User|int $user
     * @return bool - true if unique
     */
    public function watchedUniqueBy($user)
    {
        $userId = ($user instanceof User) ? $user->id : $user;

        if ($this->watches->contains('user_id', $userId) == false) {
			
            $this->watchedBy($userId);
            return true;
        }
           
        return false;
    }

    /**
     * Check watches
     *
     * @param $user
     * @return mixed
     */
    public function isWatchedBy($user)
    {
        return $this->watches->contains('user_id', ($user instanceof User) ? $user->id : $user);
    }
}