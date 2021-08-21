<?php

namespace App\Http\Controllers;

use App\Models\Trees\BinaryTreeTeam;
use App\User;

use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:partner,partner-na');
    }

    public function show($id)
    {
		
        $user = User::findOrFail($id);

        // todo: validate before response
        return $user;
    }
}
