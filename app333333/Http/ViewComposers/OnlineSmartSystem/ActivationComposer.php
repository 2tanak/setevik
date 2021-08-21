<?php

namespace App\Http\ViewComposers\OnlineSmartSystem;

use App\Models\Product;
use App\Models\Requisition;
use App\Services\Oss\ProductService;
use App\Services\Oss\TreeService;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ActivationComposer
{
    protected $tree;
    protected $productService;

    public function __construct(TreeService $tree, ProductService $productService)
    {
        $this->tree = $tree;
        $this->productService = $productService;
    }

    public function compose(View $view)
    {
        $user       = Auth::user();
        $decrypted  = decrypt($user->registration->link->code);
        $product    = Product::findOrFail($decrypted['product_id']);

        $requisitions   = Requisition::where('user_id', $user->id)
            ->where('is_confirmed', false)
            ->where('is_cancelled', false);

        if ($requisitions->count()) {
            $curator = $requisitions->first()->curator;
        } else {
            $curator = $this->tree->getActiveCurator($user);
        }

        // requisition
        $requisition = Requisition::where('user_id', $user->id)
            //->where('curator_id', $curator->id)
            ->where('product_id', $decrypted['product_id'])
            ->where('requisition_type_id', 1)
            ->where('is_confirmed', false)
            ->first();

        // result
        $data = [
            'user'      => $user,
            'curator'   => [
                'id'        => $curator->id,
                'name'      => $curator->name,
                'last_name' => $curator->last_name,
                'phone'     => $curator->phone,
            ],
            'requisition'   => $requisition,
            'product'       => $product,
            'video'         => [
                'src' => 'https://project.storage-object.pscloud.io/wakeupera-free/090520.WakeUpEra.V4.mp4',
            ],
        ];

        $view->with('data', json_encode($data));
    }
}