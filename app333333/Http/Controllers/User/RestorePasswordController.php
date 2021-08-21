<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Validator;

/**
 * Class RestorePasswordController
 *
 * @package App\Http\Controllers\Auth
 */
class RestorePasswordController extends Controller
{
    /**
     * Restore password via Ajax
     *
     * @return JsonResponse
     */
    public function restore_password()
    {
        // Validate request
        if (!request()->ajax()) {
            abort(405); // Method not allowed
        }

        $user = User::select([
            'id',
            'email',
            'password',
            'email_verified_at',
            'is_active',
        ])->where('email', request()->input('email'))
            ->first();
        if (!$user) {
            return response()->json([
                'status' => 'email-not-found',
            ]);
        }

        // Validate is user email has been verified
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'email-not-verified',
            ]);
        }

        // Validate is_active
        if (!$user->is_active) {
            return response()->json([
                'status' => 'not-active',
            ]);
        }

        // Generate new password
        $newPassword = Str::random();

        DB::beginTransaction();

        $user->password = Hash::make(sha1($newPassword));
        if (!$user->save()) {
            return response()->json([
                'status' => 'restore-password-failed',
            ]);
        }


        DB::commit();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
