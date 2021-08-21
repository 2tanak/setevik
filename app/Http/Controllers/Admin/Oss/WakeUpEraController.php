<?php

namespace App\Http\Controllers\Admin\Oss;

use App\Models\Broadcast;
use App\Models\BroadcastVideo;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class WakeUpEraController extends AdminController
{
    /**
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $broadcasts = Broadcast::with('product')
            ->orderByDesc('id')
            ->get();

        //
        $videos = BroadcastVideo::with('file')
            ->orderByDesc('created_at')
            ->get()
            ->each(function ($item) {
                $item->url = $item->getUrl();
                $item->file_name = '';

                if ($item->file) {
                    $item->file_name = $item->file->original_name;
                }
            });

        return view('admin.oss.wake_up_era')
            ->with('broadcasts', json_encode($broadcasts))
            ->with('videos', json_encode($videos));
    }
}
