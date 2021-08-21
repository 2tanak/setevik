<?php

namespace App\Http\ViewComposers\OnlineSmartSystem;

use App\Models\Package;
use App\Services\Oss\TreeService;

use App\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ActivationPartnerComposer
{
    protected $tree;

    public function __construct(TreeService $tree)
    {
        $this->tree = $tree;
    }

    public function compose(View $view)
    {
        $user       = User::with('bePartnerRequest')->where('id', Auth::id())->first();
        $curator    = $this->tree->getActiveCuratorSib($user);
        $packages   = Package::all();

        // result
        $data = [
            'user'      => $user,
            'curator'   => [
                'id'        => $curator->id,
                'name'      => $curator->name,
                'last_name' => $curator->last_name,
                'phone'     => $curator->phone,
            ],
            'packages'      => $packages,
            'video'         => [
                'src' => 'https://sib.object.pscloud.io/wakeapuera-free/IMG_6753.mp4',
            ],
        ];

        $view->with('data', $data);
    }
}