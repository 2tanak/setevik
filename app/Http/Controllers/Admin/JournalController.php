<?php

namespace App\Http\Controllers\Admin;

use App\Models\BePartnerRequest;
use App\Models\Country;
use App\Models\Journal;

use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Admin
 */
class JournalController extends AdminController
{
    public function index()
    {
        $data = \App\Models\Journal::with('eventType')
            ->orderByDesc('id')
            //->paginate(20);
            ->get();

        $countries = Country::all();

        return view('admin.journal', compact('data', 'countries'));
    }
}
