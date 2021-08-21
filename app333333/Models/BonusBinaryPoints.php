<?php

namespace App\Models;

use App\User;
use App\Models\Trees\BinaryTreeNode;

use Illuminate\Database\Eloquent\Model;

class BonusBinaryPoints extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'from_node_id',
        'to_node_id',
        'pts',
        'pts_real',
        'pts_cut',
        'level',
        'team_id',
        'is_refunded',
    ];

    protected $casts = [
        'is_refunded' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initiator()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initiatorNode()
    {
        return $this->belongsTo(BinaryTreeNode::class, 'from_node_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipientNode()
    {
        return $this->belongsTo(BinaryTreeNode::class, 'to_node_id');
    }
}
