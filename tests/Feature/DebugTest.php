<?php

namespace Tests\Feature;

use App\Models\RequisitionType;
use App\User;
use App\Role;
use App\Permission;
use App\Models\Tag;
use App\Models\File;
use App\Models\News;
use App\Models\Link;
use App\Models\Sale;
use App\Models\Menu;
use App\Models\Watch;
use App\Models\Badge;
use App\Models\Bonus;
use App\Models\Wallet;
use App\Models\Reward;
use App\Models\Package;
use App\Models\Journal;
use App\Models\OssNews;
use App\Models\Product;
use App\Models\Country;
use App\Models\Cabinet;
use App\Models\Curation;
use App\Models\Quittance;
use App\Models\Documents;
use App\Models\Broadcast;
use App\Models\LearnVideo;
use App\Models\Requisition;
use App\Models\Subscription;
use App\Models\BroadcastVideo;
use App\Models\LearnVideoType;
use App\Models\BePartnerRequest;
use App\Models\BonusBinaryPoints;
use App\Models\LearnVideoConfirm;
use App\Models\Trees\BinaryTreeNode;
use App\Models\Trees\BinaryTreeTeam;
use App\Models\Trees\OnlineSmartSystemTreeNode;

use App\Services\Sib\ClassicTreeService;
use App\Services\Sib\BinaryTreeService;

use App\Jobs\CheckResidentActivity;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class DebugTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::find(1);


























//        $type = RequisitionType::where('code', 'subscription')->first();
//        $requisitions = DB::table('requisitions')
//            ->select(DB::raw('count(*) as cnt, curator_id'))
//            ->where('requisition_type_id', $type->id)
//            ->where('confirmed_at', '>=', Carbon::now()->firstOfMonth())
//            ->where('confirmed_at', '<', Carbon::now())
//            ->groupBy('curator_id')
//            ->orderByDesc('cnt')
//            ->limit(10)
//            ->get();
//
//
//        dd($requisitions);













    }

    /**
     *
     *
     * @param $dateTimeString - Example: '2021-11-19 21:00:00'
     */
    protected function setDateTestNow($dateTimeString)
    {
        Carbon::setTestNow($dateTimeString);
    }

}
