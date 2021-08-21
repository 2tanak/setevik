<?php

namespace App\Http\Controllers\Oss;

use App\Models\Product;
use App\Models\Broadcast;
use App\Models\BroadcastVideo;
use App\Services\Oss\ProductService;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WakeUpEraController extends Controller
{
    /**
     * Get presigned URL
     *
     * @param $id
     * @return mixed
     */
    public function source($id)
    {
        $broadcastVideo = BroadcastVideo::findOrFail($id);
        return (string) $broadcastVideo->getUri();
    }

    /**
     * Check subscriptions
     *
     * @return bool
     */
    protected function hasAccess()
    {
        $user = Auth::user();
        $product = Product::where('name', 'WakeUpERA')->first();
        return (new ProductService())->hasSubscription($product, $user) || $user->isAdmin();
    }

    /**
     *
     *
     * @return mixed
     */
    public function index()
    {
		
        $broadcast = null;
        $video = null;
        $videos = [];
        $hasActiveSubscription = false;

        if ($this->hasAccess()) {

            //
            $broadcast = Broadcast::where('product_id', 5)
                ->where('started_at', '<=', Carbon::now())
                ->where('expired_at', '>=', Carbon::now())
                ->first();

            // last broadcast video record
            $video = BroadcastVideo::whereNotNull('date')
                ->where(function ($query) {
                    $query
                        ->where('started_at', '<=', Carbon::now())
                        ->where('expired_at', '>=', Carbon::now());
                })
                ->orderByDesc('date')
                ->first();

            // all available videos
            $videos = BroadcastVideo::whereNotNull('date')
                ->where(function ($query) use ($video) {
                    if ($video) {
                        $query->where('id', '!=', $video->id);
                    }
                })
                ->where(function ($query) {
                    $query
                        ->where('started_at', '<=', Carbon::now())
                        ->where('expired_at', '>=', Carbon::now())
                        ->orWhere('is_available', true);
                })
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();

            //
            $hasActiveSubscription = true;
        }

        return view('oss.wake_up_era')
            ->with('broadcast', $broadcast)
            ->with('video', $video)
            ->with('videos', $videos)
            ->with('hasActiveSubscription', $hasActiveSubscription);
    }

    /**
     * Set video as watched
     *
     * @param $id - BroadcastVideo ID
     */
    public function watch($id)
    {
        $video = BroadcastVideo::findOrFail($id);

        if ($this->hasAccess()) {
            $video->watchedBy(Auth::id());
        }
    }
}
