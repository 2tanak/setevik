<?php

namespace App\Services\Oss;

use App\User;
use App\Models\Trees\OnlineSmartSystemTreeNode as Node;
use Carbon\Carbon;

/**
 * @package App\Services
 */
class TreeService
{
    /**
     * Getting active curator (partner) in OSS Tree
     *
     * @param User $user
     * @return mixed|null
     */
    public function getActiveCuratorSib(User $user)
    {
        $node = Node::where('user_id', $user->id)->first();

        if ($node) {
            $parent = $node->getParent();

            if ($parent) {
                if ($parent->user->isPartner() && $parent->user->has_activity_sib) {
                    return $parent->user;
                } else {
                    return $this->getActiveCuratorSib($parent->user);
                }
            }

        }

        return null;
    }

    /**
     * Getting active curator
     *
     * @param User|int $user
     * @return mixed
     */
	 
	 
    public function getActiveCurator(User $user)
    {
        $node = Node::where('user_id', $user->id)->first();
        $a = false;
        if ($node) {
		    
            $parent = $node->getParent();

            if ($parent) {
				if(count($parent->user->subscriptions)){
				$a = $parent->user->subscriptions->where('expired_at','>',Carbon::now());
				if ($a->count()) {$a = true;}
				}
			  
                if ($parent->user->has_activity_oss && $a === true || $parent->user->id == 1) {
                    return $parent->user;
                } else {
					
                    return $this->getActiveCurator($parent->user);
                }
            }

        } else {
			
            $decrypted = decrypt($user->registration->link->code);
            $inviter = User::find($decrypted['user_id']);
            $a = false;
            $inviterNode = Node::where('user_id', $inviter->id)->first();
			
			//dd($parent->user->subscriptions);
			if(count($inviterNode->user->subscriptions)){
            $a = $inviterNode->user->subscriptions->where('expired_at','>',Carbon::now());
			if ($a->count()) {$a = true;}
			}
			
			
            if ($inviterNode->user->has_activity_oss && $a === true || $inviterNode->user->id == 1) {
                return $inviterNode->user;
            } else {
				
                $parent = $inviterNode->getParent();
                
            if ($parent) {
             if(count($parent->user->subscriptions)){
            $a = $parent->user->subscriptions->where('expired_at','>',Carbon::now());
			if ($a->count()) {$a = true;}
			}
			
			
                if ($parent->user->has_activity_oss && $a === true || $parent->user->id == 1) {

                        return $parent->user;
                    } else {
                        return $this->getActiveCurator($parent->user);
                    }
                }
            }
        }

        return null;
    }

    public function changeCurator(User $user, User $curator)
    {
        $userNode = Node::where('user_id', $user->id)->first();
        $totalCnt = (int) $userNode->total_cnt + 1;

        $this->refreshBeforeChangeCurator($userNode, $totalCnt);

        $curatorNode = Node::where('user_id', $curator->id)->first();
        $userNode->parent_id = $curatorNode->id;
        $userNode->save();

        $this->refreshAfterChangeCurator($userNode, $totalCnt);
    }

    protected function refreshBeforeChangeCurator(Node $node, $totalCnt, $level = 1)
    {
        $parent = $node->getParent();

        if ($parent) {

            if ($level == 1) {
                $parent->decrement('invited_cnt');
            }
            $parent->decrement('total_cnt', $totalCnt);
            $parent->save();

            $this->refreshBeforeChangeCurator($parent, $totalCnt, $level + 1);
        }
    }

    protected function refreshAfterChangeCurator(Node $node, $totalCnt, $level = 1)
    {
        $parent = $node->getParent();

        if ($parent) {

            if ($level == 1) {
                $parent->increment('invited_cnt');
            }
            $parent->increment('total_cnt', $totalCnt);
            $parent->save();

            $this->refreshAfterChangeCurator($parent, $totalCnt, $level + 1);
        }
    }

    /**
     * Get real resident's curator
     *
     * @param User $user
     * @return mixed
     */
    public function getCurator(User $user)
    {
        $node = Node::where('user_id', $user->id)->first();

        if ($node && $node->getParent()) {
            $curator = $node->getParent()->user;

            if ($curator) {
                return $curator;
            }
        }

        return null;
    }

    /**
     * Binding user to curator's chain and activate node
     *
     * @param User $user
     * @param User $curator
     */
    public function bindUserToCurator(User $user, User $curator)
    {
        $node = Node::create([
            'parent_id' => Node::where('user_id', $curator->id)->first()->id,
            'user_id'   => $user->id,
        ]);

        $this->activate($node);
    }

    /**
     * Activate node
     *
     * @param Node $node
     */
    public function activate(Node $node)
    {
        $node->update(['is_active' => true]);
        $this->refresh($node);
    }

    /**
     * Refresh parent's chain
     *
     * @param Node $node
     * @param int $level
     */
    public function refresh(Node $node, $level = 1)
    {
        $parent = $node->getParent();

        if ($parent) {

            if ($level == 1) {
                $parent->increment('invited_cnt');
            }
            $parent->increment('total_cnt');
            $parent->save();

            $this->refresh($parent, $level + 1);
        }
    }

    /**
     * Building a tree
     *
     * @param $node
     * @param array $context
     * @param int $level
     * @return array
     */
    public function getTree($node, &$context = [], $level = 1)
    {
        $node = ($node instanceof Node) ? $node : Node::with('user')->find($node);

        if ($level == 1) {
            $context = $node->toArray();
        }

        $index = 0;
        foreach ($node->children()->with('user')->get() as $child) {
            if ($child->is_active) {
                $context['children'][$index] = $child->toArray();
                $this->getTree($child, $context['children'][$index], $level + 1);
                $index++;
            }
        }

        return $context;
    }

    /**
     * @deprecated
     * Append node and activate
     *
     * @param User|int $user
     */
    public function append($user)
    {
        $node = Node::where('user_id', ($user instanceof User) ? $user->id : $user)->first();
        $node->update(['is_active' => true]);

        $this->refresh($node);
    }
}