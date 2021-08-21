<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ResidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:resident,resident-na,partner,partner-na');
    }

    /**
     * Origin wizard activation
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function activateWizard(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            if ($user->id == Auth::id()) {
                $user->is_wizard_activated = true;
                $user->save();
            }

            return Response::json([
                'message' => 'done',
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'error' => 'error',
            ], 200);
        }
    }
}
