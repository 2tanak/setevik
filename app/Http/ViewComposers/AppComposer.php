<?php

namespace App\Http\ViewComposers;

use App\User;
use App\Models\Menu;
use App\Models\Cabinet;
use App\Models\Subscription;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AppComposer
{
    public function compose(View $view)
    {
        //
        $isAdminSection = preg_match('/^admin/', request()->path());

        //
        $user = Auth::user();

        //
        $menus = [];

        //
        if ($user instanceof User) {
            //
            $menus = Menu::with([
                    'badges' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }
                ])
                ->whereNull('parent_id')
                ->where(function ($query) use ($user, $isAdminSection) {
                    if ($user->isAdmin()) {
                        if ($isAdminSection) {
                            $query->where('cabinet_id', Cabinet::ADMIN);
                        } else {
                            $query->where('cabinet_id', $user->cabinet->id)->orWhere('cabinet_id', null);
                        }
                    } else {
                        $query->where('cabinet_id', $user->cabinet->id)->orWhere('cabinet_id', null);
                    }
                })
                ->get()
                ->each(function ($parent) use ($user) {
                    $children = [];

                    //
                    $parent->badges_count = 0;
                    foreach ($parent->badges as $badge) {
                        if ($badge->user_id == $user->id) {
							//dump($parent);
                            if (is_null($badge->badgable->getAttribute('is_active'))) {
                                $parent->badges_count++;
                            } elseif ($badge->badgable->is_active) {
                                $parent->badges_count++;
                            }
                        }
                    }

                    $parent->children()->get()->each(function ($child) use ($parent, &$children, $user) {
						

                        //
                        $child->badges_count = 0;
                        foreach ($child->badges as $badge) {
                            if ($badge->user_id == $user->id) {

                                if (is_null($badge->badgable->getAttribute('is_active'))) {
                                    $child->badges_count++;
                                } elseif ($badge->badgable->is_active) {
                                    $child->badges_count++;
                                }
                            }
                        }

                        //
                        if ($child->badges_count > 0) {
                            $parent['badges_count'] += $child->badges_count;
                        }

                        //
                        if (rtrim(request()->path(), '/0..9') == ltrim($child->link, '/')) {
                            $parent['is_active'] = true;
                            $child['is_active'] = true;
                        }

                        $children[] = $child->toArray();
                    });
						

                    $parent['children'] = $children;

                    if (rtrim(request()->path(), '/0..9') == ltrim($parent->link, '/')) {
                        $parent['is_active'] = true;

                    }
                });
        }
          
      

        //
        $subscription = $user->subscriptions()
            ->where('user_id', $user->id)
            ->get()
            ->sortByDesc('expired_at')
            ->first();


        $view->with([
            'is_admin_section'  => $isAdminSection,
            'menus'             => $menus,
            'user'              => $user,
            'options'           => [],
            'subscription'      => $subscription,
        ]);
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}