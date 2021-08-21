<?php

namespace App\Http\Controllers\Oss;

use App\Models\Product;
use App\Services\Oss\ProductService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProductController extends OssController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * View - list of products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $user = Auth::user();
		//$product = $user->subscriptions[12]->product;
		//dd($product->discounts());
		
        $products = Product::with([
                'subscriptions' => function ($query) use ($user) {
                    $query
                        ->where('user_id', $user->id)
                        ->where('expired_at', '>', Carbon::now());
                    },
                'discounts' => function ($query) use ($user) {
                    $query
                        ->where(function ($subQuery) use ($user) {
                            if($user->package_id) {
                                $subQuery->where('user_id', $user->id)->orWhere('package_id', $user->package_id);
                            } else {
                                $subQuery->where('user_id', $user->id);
                            }
                        });
                },
                'requisitions' => function ($query)  use ($user) {
                    $query
                        ->where('user_id', $user->id)
                        ->where('is_confirmed', false);
                },
            ])
            ->where('category', 'oss')
            ->where('is_active', true)
            ->get();

        // filtering discount
        $filtered = [];
        foreach ($products as $product) {

            // discount
            $product->discount = $this->productService->getDiscountPercentForUser($product, $user);

            // can get link
            if ($product->subscriptions->count()) {
                $product->has_link = true;
            } else {
                $product->has_link = false;

                // allowed for root user of system
                if ($user->id == 1) {
                    $product->has_link = true;
                }
            }

            $filtered[] = $product;
        }
		

        return view('oss.products')->with('data', json_encode($filtered));
    }

    /**
     * Getting link by product
     *
     * @param ProductService $service
     * @param $id - Product ID
     * @return mixed
     */
    public function getLink(ProductService $service, $id)
    {
        return Response::json([
            'link' => $service->getRefLink($id)
        ], 200);
    }
}
