<?php

namespace App\Services\Sib;

use App\User;
use App\Models\Sale;
use App\Models\Bonus;
use App\Models\Wallet;
use App\Models\Product;
use App\Models\Statement;

use Illuminate\Support\Facades\Log;

class WalletService
{
    public function enrol(Sale $sale)
    {
        $seller     = $sale->seller;
        $customer   = $sale->customer;
        $product    = $sale->product;


        // todo: make responsible
//        if ($product->id == 5) {
//            $wallet = $this->getUserWalletByBonusCode($seller, 'oss');
//            $wallet->increment('earned', $sale->price);
//            $wallet->save();
//
//            $statement = Statement::create([
//                'user_id'       => $seller->id,
//                'initiator_id'  => $customer->id,
//                'balance_begin' => 0,
//                'amount'        => $sale->price,
//                'balance_end'   => 0,
//                'bonus_id'      => $wallet->bonus_id,
//                'package_id'    => $customer->package_id,
//            ]);
//        }



    }

    /**
     * Getting user's bonus wallet
     *
     * @param User $user
     * @param $code
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function getUserWalletByBonusCode(User $user, $code)
    {
        $bonus = Bonus::where('code', $code)->first();

        return $bonus->wallets()->where('user_id', $user->id)->first();
    }

    /**
     * Create new records for wallet
     *
     * @param User $user
     */
    public function generateWallet(User $user)
    {
        $bonuses = Bonus::where('is_active', true)
            ->get()
            ->sortBy('sort');

        foreach ($bonuses as $bonus) {
            Wallet::create([
                'user_id'   => $user->id,
                'bonus_id'  => $bonus->id,
            ]);
        }
    }
}