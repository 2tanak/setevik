<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @package App\Http\Controllers\Admin
 */
class TestController extends AdminController
{
    /**
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $time = DB::table('times')->value('time');

        if (is_null($time)) {
            $time = Carbon::now();
        }

        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $subscriptions = $user->subscriptions()
                ->where('started_at', '<=', Carbon::now())
                ->where('expired_at', '>', Carbon::now());

            if ((int) $subscriptions->count() == 0) {
                $user->update(['has_activity_oss' => false]);
            } else {
                $user->update(['has_activity_oss' => true]);
            }
        }

        return view('admin.test', compact('time'));
    }

    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'time' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        DB::table('times')
            ->where('id', 1)
            ->update(['time' => $request->input('time')]);

        return redirect()->route('test');
    }
}
