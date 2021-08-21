<?php

namespace App\Models\Trees;

use App\User;
use App\Traits\Ownable;
use App\Traits\Treeable;
use App\Models\Trees\BinaryTreeTeam;
use App\Services\Sib\BinaryTreeService;
use App\Models\Trees\BinaryTreeNodeInfo;

use Illuminate\Database\Eloquent\Model;

class BinaryTreeNode extends Model
{
    use Ownable, Treeable;

    protected $table = 'binary_tree_nodes';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'inviter_id',
        'team_id',
        'root_node_id',
        'last_left_node_id',
        'last_right_node_id',
        'user_id',
        'is_active', // todo: ?
        'is_blocked',
        'is_free',
        'is_blocked_global',
        'is_free_global',
    ];

    protected $casts = [
        'is_active'         => 'boolean', // todo: ?
        'is_blocked'        => 'boolean',
        'is_free'           => 'boolean',
        'is_blocked_global' => 'boolean',
        'is_free_global'    => 'boolean',
    ];

    /**
     * Get node's bound info
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne(BinaryTreeNodeInfo::class, 'node_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function team()
    {
        return $this->hasMany(BinaryTreeTeam::class, 'node_id');
    }

    /**
     * @deprecated use method `info()`
     * @return BinaryTreeNodeInfo|Model|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @deprecated user method `user()`
     * Get node's bound user (if exists)
     * @return User|User[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getUser()
    {
        //return User::find($this->user_id);
        return $this->user;
    }

    public function isTriangleRootNode()
    {
        return $this->id == $this->root_node_id;
    }

    public function getTriangleRootNode()
    {
        if ($this->isTriangleRootNode()) {
            return $this;
        }
        return self::find($this->root_node_id);
    }

    public function getTriangleLeftNode()
    {
        return self::where('parent_id', $this->getTriangleRootNode()->id)
            ->where('team_id', 1)
            ->first();
    }

    public function getTriangleRightNode()
    {
        return self::where('parent_id', $this->getTriangleRootNode()->id)
            ->where('team_id', 2)
            ->first();
    }

    public function addNodeToTeam($nodeId, $teamId)
    {
        BinaryTreeTeam::create([
            'node_id'       => $this->root_node_id,
            'team_id'       => $teamId,
            'team_node_id'  => $nodeId,
            'is_active'     => true,
        ]);
    }

    public function deactivateNodeFromTeam($nodeId)
    {
        BinaryTreeTeam::where('node_id', $this->root_node_id)
            ->where('team_node_id', $nodeId)
            ->first()
            ->update(['is_active' => false]);
    }

    public function getLastLeft()
    {
        return self::find($this->last_left_node_id);
    }

    public function getLastRight()
    {
        return self::find($this->last_right_node_id);
    }

    public function getTeamName()
    {
        return $this->team_id == BinaryTreeService::TEAM_LEFT ? 'Левая' : 'Правая';
    }
}