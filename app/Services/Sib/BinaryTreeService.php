<?php

namespace App\Services\Sib;

use App\User;
use App\Models\Link;
use App\Models\Package;
use App\Models\Trees\BinaryTreeNode as Node;
use App\Models\Trees\BinaryTreeTeam as Team;
use App\Models\Trees\BinaryTreeNodeInfo as NodeInfo;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class BinaryTreeService
{
    const TEAM_LEFT = 1;
    const TEAM_RIGHT = 2;

    public $packages;

    public function __construct()
    {
        $this->packages = Package::all();
    }

    /**
     *
     *
     * @param $userId
     * @param $nodeId
     * @return Link|\Illuminate\Database\Eloquent\Model
     */
    public function registerRefLink($userId, $nodeId)
    {
        $encrypted = encrypt([
            'system'    => 'sib',
            'user_id'   => $userId,
            'node_id'   => $nodeId,
        ]);

        return Link::create([
            'user_id'   => $userId,
            'code'      => $encrypted,
        ]);
    }

    /**
     *
     *
     * @param $nodeId
     * @return string
     */
    public function getRefLink($nodeId)
    {
        return URL::to('/register?ref='). $this->registerRefLink(Auth::id(), $nodeId)->code;
    }

    /**
     * Chain of nodes (parents)
     *
     * @param $node
     * @param array $context
     * @return array
     */
    public function getChainNode($node, &$context = [])
    {
        $child = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $parent = $child->getParent();

        if ($parent && (int) $parent->id > 0) {
            $context[] = $parent;
            $this->getChainNode($parent, $context);
        }

        return collect($context);
    }

    /**
     * Land user to tree node position
     *
     * @param User $user
     * @throws \Exception
     */
    public function place(User $user)
    {
        $node = Node::find($user->tree_node_id);

        // if this node is not free at this time - get a new position
        if ($node->user_id) {
            $node = $this->getLastFreeNode($node, $user);
        }

        // generate node groups
        $this->generateNodeGroup($node, $user);

        // checking node attributes after generating
        $this->updateChainNodeAfterActivating($node);
    }

    /**
     *
     *
     * @param User $user
     */
    public function upgrade(User $user)
    {
        $this->updateChainNodeAfterUpgrading($user);
    }

    /**
     *
     *
     * Deactivate node by user
     * @param User $user
     */
    public function deactivate(User $user)
    {
        $this->updateChainNodeAfterDeactivating($user->tree_node_id);
    }

    /**
     *
     *
     * @param Node|int $node
     * @return void
     */
    public function updateQualification($node)
    {
        $node = ($node instanceof Node) ? $node : Node::findOrFail($node);

        $left = Team::where('is_active', true)
            ->where('node_id', $node->id)
            ->where('team_id', BinaryTreeService::TEAM_LEFT)
            ->count();

        $right = Team::where('is_active', true)
            ->where('node_id', $node->id)
            ->where('team_id', BinaryTreeService::TEAM_RIGHT)
            ->count();

        if ($left && $right) {
            $node->user->is_qualified = true;
        } else {
            $node->user->is_qualified = false;
        }
        $node->user->save();
    }

    /**
     *
     *
     * @param $user
     */
    public function updateChainNodeAfterUpgrading($user)
    {
        $node = Node::findOrFail($user->tree_node_id);
        if (in_array($user->package_id, [Package::PREMIUM, Package::VIP])) {
            $node->getTriangleLeftNode()->update(['is_active' => true]);
            $node->getTriangleRightNode()->update(['is_active' => true]);
        }
    }

    /**
     *
     *
     * @param Node|id $node
     * @param array $context
     * @param int $prevTeamId
     */
    public function updateChainNodeAfterDeactivating($node, &$context = [], $prevTeamId = 0)
    {
        $child = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $parent = $child->getParent();

        // child's team
        $prevTeamId = $child->team_id;

        // init initiator node
        if (!isset($context['initiator'])) {
            $context['initiator'] = $child->id;
            $context['initiator_inviter'] = $child->inviter_id;

            // deactivate node (whole triangle)
            $child->getTriangleRootNode()->update(['is_active' => false]);
            $child->getTriangleLeftNode()->update(['is_active' => false]);
            $child->getTriangleRightNode()->update(['is_active' => false]);

            // free nodes (not all!)
            foreach ($child->getChildren() as $item) {
                if ($item->team_id == 1) {
                    $n = Node::where('parent_id', $item->id)
                        ->where('team_id', 1)->first();
                } else {
                    $n = Node::where('parent_id', $item->id)
                        ->where('team_id', 2)->first();
                }
                if ((int) $n->user_id == 0) {
                    $n->update([
                        'is_blocked'        => false,
                        'is_free'           => true,
                        'is_blocked_global' => false,
                        'is_free_global'    => true,
                    ]);
                }
            }
        }

        if ($parent && (int) $parent->id > 0) {
            if ($child->root_node_id != $parent->id) {

                // block opposite node if it is free
                if ($child->team_id == 1) {
                    $n = Node::where('parent_id', $parent->getTriangleRightNode()->id)
                        ->where('team_id', 2)->first();
                } else {
                    $n = Node::where('parent_id', $parent->getTriangleLeftNode()->id)
                        ->where('team_id', 1)->first();
                }
                if ((int) $n->user_id == 0 && $parent->root_node_id == $context['initiator_inviter']) {
                    $n->update([
                        'is_blocked'    => true,
                        'is_free'       => false,
                    ]);
                }

                // delete current node from parent's team
                $parent->deactivateNodeFromTeam($context['initiator']);
            }

            // refresh count of nodes in each team
            if ($prevTeamId == 1) {
                $parent->info->decrement('packs_left');
            } else {
                $parent->info->decrement('packs_right');
            }

            // update qualification
            $this->updateQualification($parent);

            // up to chain
            $this->updateChainNodeAfterDeactivating($parent, $context, $prevTeamId);
        }
    }

    /**
     *
     *
     * @param Node|int $node
     * @param array $context
     * @param int $prevTeamId
     */
    public function updateChainNodeAfterActivating($node, &$context = [], $prevTeamId = 0)
    {
        $child = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $parent = $child->getParent();

        // child's team
        $prevTeamId = $child->team_id;

        // init initiator node
        if (!isset($context['initiator'])) {
            $context['initiator']           = $child->id;
            $context['initiator_inviter']   = $child->inviter_id;
            $context['initiator_team']      = $child->team_id;
            $context['is_last_node']        = true;
        }

        if ($parent && (int) $parent->id > 0) {

            if ($child->root_node_id != $parent->id) {

                // let's free opposite node of this root node
                if ($prevTeamId == 1) {
                    $n = Node::where('parent_id', $parent->getTriangleRightNode()->id)
                        ->where('team_id', 2)
                        ->first();

                } else {
                    $n = Node::where('parent_id', $parent->getTriangleLeftNode()->id)
                        ->where('team_id', 1)
                        ->first();
                }
                if ((int) $n->user_id == 0 && $parent->root_node_id == $context['initiator_inviter']) {
                    $n->update([
                        'is_blocked'    => false,
                        'is_free'       => true,
                    ]);
                }

                // add current node to parent's team
                $parent->addNodeToTeam($context['initiator'], $prevTeamId);
            }

            // assign count of nodes in each team
            if ($prevTeamId == 1) {
                $parent->getInfo()->increment('packs_left');
            } else {
                $parent->getInfo()->increment('packs_right');
            }

            // assign last node
            if ($context['is_last_node']) {
                if ($parent->team_id != $context['initiator_team']) {
                    $context['is_last_node'] = false;
                }

                if ($prevTeamId == 1) {
                    $parent->update(['last_left_node_id' => $context['initiator']]);
                } else {
                    $parent->update(['last_right_node_id' => $context['initiator']]);
                }
            }

            // update qualification
            $this->updateQualification($parent);

            // up to chain
            $this->updateChainNodeAfterActivating($parent, $context, $prevTeamId);
        }
    }

    /**
     * Needs for importing data.
     *
     * @param $node
     * @param array $context
     * @param int $level
     */
    public function generateNodeGroupWithoutUser($node, &$context = [], $level = 0)
    {
        $parent = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $children = $parent->getChildren();

        // for only root node
        if ($level == 0) {
            // remember some data
            $context = [
                'root_node_id'      => $parent->id,
                'root_node_team_id' => $parent->team_id,
            ];

            // assign level as first
            $level = 1;
        }

        for ($i = 1; $i <= 2; $i++) {

            if ($child = $children->where('team_id', $i)->first()) {

                $data = [
                    'is_blocked'        => true,
                    'is_free'           => false,
                    'is_blocked_global' => true,
                    'is_free_global'    => false,
                ];

                $child->update($data);

            } else {

                $data = [
                    'parent_id'         => $parent->id,
                    'team_id'           => $i,
                    'root_node_id'      => $parent->id,
                    'is_blocked'        => true,
                    'is_free'           => false,
                    'is_blocked_global' => true,
                    'is_free_global'    => false,
                ];

                // create new node
                $child = Node::create($data);

                // create new node info
                NodeInfo::create(['node_id' => $child->id]);

                // correct 'root_node_id' for 'triangle groups'
                if ($level == 2) {
                    $child->update(['root_node_id' => $child->id]);
                }

            }

            if ($level < 3) {
                $this->generateNodeGroupWithoutUser($child, $context, $level + 1);
            }
        }
    }

    /**
     * Generating group for new user (with the root node)
     *
     * @param Node|int $node
     * @param $user
     * @param array $context
     * @param int $level
     */
    public function generateNodeGroup($node, $user, &$context = [], $level = 0)
    {
        $parent = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $children = $parent->getChildren();

        // for only root node
        if ($level == 0) {
            $parent->update([
                'inviter_id'        => $user->tree_inviter_node_id,
                'user_id'           => $user->id,
                'is_active'         => true,
                'is_blocked'        => false,
                'is_free'           => false,
                'is_blocked_global' => false,
                'is_free_global'    => false,
            ]);

            // remember some data
            $context = [
                'root_node_id'      => $parent->id,
                'root_node_team_id' => $parent->team_id,
            ];

            // assign level as first
            $level = 1;
        }

        for ($i = 1; $i <= 2; $i++) {

            if ($child = $children->where('team_id', $i)->first()) {

                $data = [
                    'user_id'           => null,
                    'is_blocked'        => false,
                    'is_free'           => false,
                    'is_blocked_global' => false,
                    'is_free_global'    => false,
                ];

                if ($level == 1) {
                    $data['user_id'] = $user->id;

                    if (in_array($user->package_id, [Package::PREMIUM, Package::VIP])) {
                        $data['is_active'] = true;
                    }

                } elseif ($level == 2) {

                    // is node free for parent in second level (after triangle group)
                    if ($context['root_node_team_id'] == $i &&
                        $context['root_node_team_id'] == $parent->team_id
                    ) {
                        $data['is_blocked']         = false;
                        $data['is_free']            = true;
                        $data['is_blocked_global']  = false;
                        $data['is_free_global']     = true;
                    } else {
                        $data['is_blocked']         = true;
                        $data['is_free']            = false;
                        $data['is_blocked_global']  = true;
                        $data['is_free_global']     = false;
                    }
                } elseif ($level == 3) {
                    $data = [
                        'user_id'           => null,
                        'is_blocked'        => true,
                        'is_free'           => false,
                        'is_blocked_global' => true,
                        'is_free_global'    => false,
                    ];
                }

                $child->update($data);

            } else {

                $data = [
                    'parent_id'         => $parent->id,
                    'team_id'           => $i,
                    'root_node_id'      => $parent->id,
                    'is_blocked'        => true,
                    'is_free'           => false,
                    'is_blocked_global' => true,
                    'is_free_global'    => false,
                ];

                if ($level == 1) {
                    $data['user_id'] = $user->id;

                    if (in_array($user->package_id, [Package::PREMIUM, Package::VIP])) {
                        $data['is_active'] = true;
                    }

                } elseif ($level == 2) {

                    // is node free for parent in second level (after triangle group)
                    if ($context['root_node_team_id'] == $i &&
                        $context['root_node_team_id'] == $parent->team_id
                    ) {
                        $data['is_blocked']         = false;
                        $data['is_free']            = true;
                        $data['is_blocked_global']  = false;
                        $data['is_free_global']     = true;
                    }
                }

                // create new node
                $child = Node::create($data);

                // create new node info
                NodeInfo::create(['node_id' => $child->id]);

                // correct 'root_node_id' for 'triangle groups'
                if ($level == 2) {
                    $child->update(['root_node_id' => $child->id]);
                }

            }

            if ($level < 3) {
                $this->generateNodeGroup($child, $user, $context, $level + 1);
            }
        }
    }

    /**
     * Find new free node
     *
     * @param $node
     * @param $user
     * @return Node|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function getLastFreeNode($node, $user)
    {
        $busyNode = ($node instanceof Node) ? $node : Node::findOrFail($node);
        $inviterNode = Node::findOrFail($user->tree_inviter_node_id);

        if ($busyNode->team_id == static::TEAM_LEFT) {
            $lastNode = $inviterNode->getLastLeft()->getTriangleLeftNode();

        } elseif ($busyNode->team_id == static::TEAM_RIGHT) {
            $lastNode = $inviterNode->getLastRight()->getTriangleRightNode();
        } else {
            throw new \Exception('Team not found');
        }

        return Node::where('parent_id', $lastNode->id)
            ->where('team_id', $busyNode->team_id)
            ->first();
    }

    /**
     * Find root node of triangle
     *
     * @param null $nodeId
     * @return Node|Node[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getRootNode($nodeId = null)
    {
        $defaultRootNode = Node::whereUserId(Auth::id())->first();

        if (is_null($nodeId)) {
            $rootNode = $defaultRootNode;
        } else {

            if ($node = Node::find($nodeId)) {

                // todo: validating node ID (can this user see other node?)
                if (is_null($node->user_id)) {
                    $rootNode = $defaultRootNode;
                } else {
                    $rootNode = $node;
                }

            } else {
                $rootNode = $defaultRootNode;
            }
        }

        return $rootNode;
    }

    /**
     *
     *
     * @param $nodeId
     * @return mixed
     */
    public function getRootNodeId($nodeId = null)
    {
        return $this->getRootNode($nodeId)->id;
    }

    /**
     * Has node an another node in its team
     *
     * @param $nodeId
     * @param $forNodeId
     * @return bool
     */
    public function containsNodeInTeam($nodeId, $forNodeId)
    {
        $count = Team::where('node_id', $forNodeId)
            ->where('team_node_id', $nodeId)
            ->count();

        return $count > 0;
    }

    /**
     * For link in view
     *
     * @param $rootNodeId
     * @return int
     */
    public function getPrevNodeId($rootNodeId)
    {
        $id = 0;
        $node = Node::find($rootNodeId);
        $parent = $node->getParent();

        if (!is_null($parent)) {
            $curUser = Auth::user();

            if ($parent->root_node_id == $curUser->tree_node_id) {
                $id = $parent->root_node_id;
            } elseif ($this->containsNodeInTeam($parent->root_node_id, Auth::user()->tree_node_id)) {
                $id = $parent->root_node_id;
            }
        }

        return $id;
    }

    /**
     * Get last node from left side of team
     * @param $nodeId
     * @return array
     */
    public function getLastLeftNode($nodeId)
    {
        $data = [];
        if ($node = Node::find($nodeId)->getLastLeft()) {
            $user = User::find($node->user_id);
			   
            if(\File::exists('./'.$user->photo)){
				$photo = $user->photo;
			}else{
				$photo ='null';
			}

            $data = array_merge($node->toArray(), [
                'full_name'         => $user->getFullName(),
                'is_active'         => $user->is_active,
                'package_name'      => $this->packages->find($user->package_id)->name,
                'avatar'            => $photo,
                'has_activity_sib'  => $user->has_activity_sib,
                'is_qualified'      => $user->is_qualified,

                'user_public_id'    => $user->getPublicId(),
                'curator_fullname'  => $user->getCuratorNodePartner() ? $user->getCuratorPartner()->getFullName() : '',
                'created_at'        => $user->created_at->format('d.m.Y'),
                'activated_at'      => $user->activated_at->format('d.m.Y'),
                'status_name'       => $user->status->name,
            ]);
        }

        return $data;
    }

    /**
     * Get last node from right side of team
     * @param $nodeId
     * @return array
     */
    public function getLastRightNode($nodeId)
    {
        $data = [];
        if ($node = Node::find($nodeId)->getLastRight()) {
            $user = User::find($node->user_id);
			          
            if(\File::exists('./'.$user->photo)){
				$photo = $user->photo;
			}else{
				$photo ='null';
			}

            $data = array_merge($node->toArray(), [
                'full_name'         => $user->getFullName(),
                'is_active'         => $user->is_active,
                'package_name'      => $this->packages->find($user->package_id)->name,
                'avatar'            => $photo,
                'has_activity_sib'  => $user->has_activity_sib,
                'is_qualified'      => $user->is_qualified,

                'user_public_id'    => $user->getPublicId(),
                'curator_fullname'  => $user->getCuratorNodePartner() ? $user->getCuratorPartner()->getFullName() : '',
                'created_at'        => $user->created_at->format('d.m.Y'),
                'activated_at'      => $user->activated_at->format('d.m.Y'),
                'status_name'       => $user->status->name,
            ]);
        }

        return $data;
    }

    /**
     * Building array to view
     * @param $nodeId
     * @param array $context
     * @param int $level
     * @return array
     */
    public function build($nodeId, &$context = [], $level = 1)
    {
        $node = Node::find($nodeId);

        if ($level == 1) {

            $user = User::find($node->user_id);
            if(\File::exists('./'.$user->photo)){
				$photo = $user->photo;
			}else{
				$photo ='null';
			}

            // user info
            $context['root'] = array_merge($node->toArray(), [
                'full_name'         => $user->getFullName(),
                'is_active'         => $user->is_active,
                'package_name'      => $user->package->name,
                'avatar'            => $photo,
                'has_activity_sib'  => $user->has_activity_sib,
                'is_qualified'      => $user->is_qualified,

                'user_public_id'    => $user->getPublicId(),
                'curator_fullname'  => $user->getCuratorNodePartner() ? $user->getCuratorPartner()->getFullName() : '',
                'created_at'        => $user->created_at->format('d.m.Y'),
                'activated_at'      => $user->activated_at->format('d.m.Y'),
                'status_name'       => $user->status->name,
            ]);

            // node info
            $context['root'] = array_merge($context['root'], NodeInfo::find($node->id)->toArray());
        }

        $index = 0;
        foreach ($node->getChildren() as $child) {

            $row = [];

            if ($child->user_id) {

                $user = User::find($child->user_id);
            if(\File::exists('./'.$user->photo)){
				$photo = $user->photo;
			}else{
				$photo ='null';
			}

                // user info
                $row = array_merge($row, [
                    'full_name'         => $user->getFullName(),
                    'is_active'         => $user->is_active,
                    'package_name'      => $user->package->name,
                    'avatar'            => $photo,
                    'has_activity_sib'  => $user->has_activity_sib,
                    'is_qualified'      => $user->is_qualified,

                    'user_public_id'    => $user->getPublicId(),
                    'curator_fullname'  => $user->getCuratorNodePartner() ? $user->getCuratorPartner()->getFullName() : '',
                    'created_at'        => $user->created_at->format('d.m.Y'),
                    'activated_at'      => $user->activated_at->format('d.m.Y'),
                    'status_name'       => $user->status->name,
                ]);

                // node info
                if (Auth::id() == $user->id) {
                    $row = array_merge($row, NodeInfo::find($child->id)->toArray());
                }
            }

            $context[$index] = array_merge($row, $child->toArray());

            if ($level < 3) {
                $this->build($child->id, $context[$index]['children'], $level + 1);
            }
            $index++;
        }

        if ($level == 1) {
            $data['node'] = $context['root'];
            $data['node']['children'][] = $context[0];
            $data['node']['children'][] = $context[1];

            return $data;

        } else {
            return $context;
        }
    }

    /**
     * Build binary tree array
     * @param null $nodeId
     * @return array
     */
	 


	 	public function getUserVetka($node,$keyword,$level=0,&$result2=[])
        {
		   $level+=1;
           $node = Node::findOrFail($node)->load('user');
		   $children1 = $node->getChildren();
		   foreach ($children1 as $child) {
              if(isset($child->user->email)){
				 if(isset($child->user->name) || isset($child->user->last_name) || isset($child->user->email)){
                  preg_match('/'.$keyword.'/i',$child->user->name,$arr);
			      preg_match('/'.$keyword.'/i',$child->user->last_name,$arr2);
			      preg_match('/'.$keyword.'/i',$child->user->email,$arr3);
				}
			   if(isset($arr[0]) || isset($arr2[0]) || isset($arr3[0])){
				 $size = count($result2);
				 if($size >= 1){
					$size = $size-1;
					//dd($result2[$size]->user_info->email);
				 }
				
					if(count(collect($result2)->where('email',$child->user->email)) > 0){
						
					}else{
			        $child->user_info = $child->user;
					$child->email = $child->user->email;
                    $result2[] = $child;
					}
		
  
			  }
			  $this->getUserVetka($child->id, $keyword, $level,$result2);
			 }else{
				 continue;
			 }
        
          }
		  
		 
         //dd($result2[0]->user);
		 return collect($result2);
		  //return $result2;
			
		
	 }
	 
    public function getArray($nodeId = null)
    {

        $rootNodeId = $this->getRootNodeId($nodeId);
		
        $tree = $this->build($rootNodeId);

        $tree['prev_node_id']       = $this->getPrevNodeId($rootNodeId);
        $tree['last_child_left']    = $this->getLastLeftNode($rootNodeId);
        $tree['last_child_right']   = $this->getLastRightNode($rootNodeId);
        $tree['current_node_id']    = Auth::user()->tree_node_id;

        return $tree;
    }

}