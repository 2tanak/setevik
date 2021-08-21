<?php

namespace App\Models\Trees;

use Illuminate\Database\Eloquent\Model;

class BinaryTreeNodeInfo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'node_id',
        'packs_left',
        'packs_right',
        'pts_left',
        'pts_right',
        'pts_missed_left',
        'pts_missed_right',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function node()
    {
        return $this->belongsTo(BinaryTreeNode::class, 'node_id');
    }
}
