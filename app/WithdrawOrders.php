<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WithdrawOrders extends Model
{
     protected $fillable = [
            'user_id',
            'summ',
            'card',
            'bank',
            'name',
            'status',
            'upd_summ',
            'comment',
        ];

        protected $casts = [
            'expected'  => 'integer',
        ];

    public function user_data(){
        return User::where('id', '1')->first();
    }
}
