<?php

namespace App\Traits;

use App\User;
use App\Models\Menu;
use App\Models\Badge;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Badgable
{
    use Watchable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function badges()
    {
        return $this->morphMany(Badge::class, 'badgable');
    }

    public function getBadgeMenuLinks()
    {
        return $this->badgeMenuLinks;
    }

    public function detachBadgeFromMenu($user, $link)
    {
        if ($user instanceof User) {

            $this->detachFromMenu($user, $this, $link);

        } elseif ($user instanceof Collection) {

            foreach ($user as $item) {
                $this->detachFromMenu($item, $this, $link);
            }

        } else {
            throw new \InvalidArgumentException('Undefined type');
        }
    }

    /**
     * Detaching badges for user
     *
     * @param User|Collection $user
     */
    public function detachBadgeFor($user)
    {
        if ($user instanceof User) {

            $this->detach($user, $this);

        } elseif ($user instanceof Collection) {

            foreach ($user as $item) {
                $this->detach($item, $this);
            }

        } else {
            throw new \InvalidArgumentException('Undefined type');
        }
    }

    /**
     * Attaching badges for user
     *
     * @param User|Collection $user
     * @param array $arBadgeMenuLinks
     */
    public function attachBadgeFor($user, $arBadgeMenuLinks = [])
    {
        $links = count($arBadgeMenuLinks)
            ? $arBadgeMenuLinks
            : $this->getBadgeMenuLinks();

        foreach ($links as $link) {
            foreach (Menu::where('link', $link)->get() as $menu) {

                if ($user instanceof User) {

                    $this->attachBadgeToMenu($menu, $user, $this);


                } elseif ($user instanceof Collection) {

                    foreach ($user as $item) {
						if($item->id){
                        $this->attachBadgeToMenu($menu, $item, $this);
						}else{
							continue;
						}
                    }
					
                  
                } else {
                    throw new \InvalidArgumentException('Undefined type');
                }
            }
        }
    }

    /**
     * Binding badge to menu
     *
     * @param Menu $menu
     * @param User $user
     * @param Model $model
     */
    protected function attachBadgeToMenu(Menu $menu, User $user, Model $model)
    {
       
        $badge = new Badge([
            'user_id' => $user->id,
            'menu_id' => $menu->id,
        ]);
        $badge->badgable()->associate($model)->save();
	
    }

    /**
     * Deleting badge
     *
     * @param User $user
     * @param Model $model
     */
    protected function detach(User $user, Model $model)
    {
        $keys = array_keys(array_where(Relation::morphMap(), function ($v) use ($model) {
            return $v == get_class($model);
        }));

        if ($keys[0]) {

            $badge = $model->badges()
                ->where('user_id', $user->id)
                ->where('badgable_id', $model->id)
                ->where('badgable_type', $keys[0]);

            if ($badge->count()) {
                foreach ($badge->get() as $item) {
                    $item->delete();
                }
            }
        }
    }

    protected function detachFromMenu(User $user, Model $model, $link)
    {
        $keys = array_keys(array_where(Relation::morphMap(), function ($v) use ($model) {
            return $v == get_class($model);
        }));

        if ($keys[0]) {

            foreach (Menu::where('link', $link)->get() as $menu) {
                $badge = $model->badges()
                    ->where('user_id', $user->id)
                    ->where('menu_id', $menu->id)
                    ->where('badgable_id', $model->id)
                    ->where('badgable_type', $keys[0]);

                if ($badge->count()) {
                    foreach ($badge->get() as $item) {
                        $item->delete();
                    }
                }
            }
        }
    }





    /**
     * @deprecated
     * Remove badge and make watched
     *
     * @param User $user
     */
    public function unmarkBadgeFor(User $user)
    {
        if ($this->watchedUniqueBy($user)) {
			$this->detachBadge($user, $this);
        }
    }

    /**
     * @deprecated
     * Deleting badge
     *
     * @param User $user
     * @param Model $model
     */
    public function detachBadge(User $user, Model $model)
    {
        $keys = array_keys(array_where(Relation::morphMap(), function ($v) use ($model) {
            return $v == get_class($model);
        }));

        if ($keys[0]) {

            $badge = $model->badges()
                ->where('user_id', $user->id)
                ->where('badgable_id', $model->id)
                ->where('badgable_type', $keys[0]);

            if ($badge->count()) {
                foreach ($badge->get() as $item) {
                    $item->delete();
                }
            }
        }
    }
}