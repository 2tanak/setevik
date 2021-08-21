<?php

namespace App\Models\Trees;

use Illuminate\Database\Eloquent\Model;

class BinaryTreeTeam extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'node_id',
        'team_id',
        'team_node_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function node()
    {
        return $this->belongsTo(BinaryTreeNode::class, 'node_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamNode()
    {
        return $this->belongsTo(BinaryTreeNode::class, 'team_node_id');
    }
}
