<?php

namespace App\Models\Trees;

use App\User;
use App\Traits\Treeable;

use Illuminate\Database\Eloquent\Model;

class OnlineSmartSystemTreeNode extends Model
{
    use Treeable;

    protected $table = 'oss_tree_nodes';

    protected $fillable = [
        'parent_id',
        'user_id',
        'invited_cnt',
        'total_cnt',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
      return $this->belongsTo(User::class)->with('subscriptions');
    }
}