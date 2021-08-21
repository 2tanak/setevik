<?php

namespace App\Http\Controllers\Admin\Oss;

use App\User;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class SubscriptionController extends AdminController
{
    public function index()
    {
        $users = User::with(['subscriptions', 'subscriptions.product'])->get();

        return view('admin.oss.subscription')
            ->with('users', $users);
    }
}
