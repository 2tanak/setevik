<?php

namespace App\Services\Oss;

use App\User;
use App\Models\Link;
use App\Models\Product;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    /**
     * Check user's subscription for product
     *
     * @param Product $product
     * @param User $user
     * @return bool
     */
	 public function checkHasSubscription(Product $product, User $user)
    {
        $count = $product->subscriptions()
            ->where('user_id', $user->id)
            ->where('started_at', '<=', Carbon::now())
            ->count();

if($count > 0){
	return true;
}else{
	return false;
}
    }

    public function hasSubscription(Product $product, User $user)
    {
        $count = $product->subscriptions()
            ->where('user_id', $user->id)
            ->where('started_at', '<=', Carbon::now())
            ->where('expired_at', '>', Carbon::now())
            ->count();

        return $count > 0;
    }


    /**
     * Getting product discount percentage for user
     *
     * @param Product $product
     * @param User $user
     * @return int
     */
    public function getDiscountPercentForUser(Product $product, User $user)
    {
        $discounts = $product->discounts()->where(function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                if ($user->package_id) {
                    $subQuery->where('user_id', $user->id)->orWhere('package_id', $user->package_id);
                } else {
                    $subQuery->where('user_id', $user->id);
                }
            });
        })->get();

        $discount = $discounts->sortByDesc('percent')->first();
        if ($discount) {
            return $discount->percent;
        }
        return 0;
    }

    /**
     * Getting product price with discount
     *
     * @param Product $product
     * @param User $user
     * @return float|int|mixed
     */
    public function getPriceWithDiscount(Product $product, User $user)
    {
        return $product->price - (($product->price * $this->getDiscountPercentForUser($product, $user)) / 100);
    }

    /**
     * Register RefLink
     *
     * @param $productId
     * @param null $userId
     * @return Link|\Illuminate\Database\Eloquent\Model
     */
    public function registerRefLink($productId, $userId = null)
    {
        $encrypted = encrypt([
            'system'        => 'oss',
            'user_id'       => is_null($userId) ? Auth::id() : $userId,
            'product_id'    => $productId,
        ]);

        return Link::create([
            'user_id'   => is_null($userId) ? Auth::id() : $userId,
            'code'      => $encrypted,
        ]);
    }


    /**
     * Getting URL for registration
     *
     * @param $productId
     * @param null $userId
     * @return string
     */
    public function getRefLink($productId, $userId = null)
    {
        $link = $this->registerRefLink($productId, $userId);
        return URL::to('/register/oss?ref='). $link->code;
    }
}