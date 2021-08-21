<?php

namespace App\Models;

use App\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    const BASIC = 1;
    const STANDARD = 2;
    const PREMIUM = 3;
    const VIP = 4;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getBinaryReward()
    {
        return Reward::where('id', $this->id)
            ->where('started_at', '<=', Carbon::now())
            ->where('expired_at', '>', Carbon::now())
            ->first();
    }
}
