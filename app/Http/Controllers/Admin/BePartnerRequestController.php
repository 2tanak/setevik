<?php

namespace App\Http\Controllers\Admin;

use App\Models\BePartnerRequest;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @package App\Http\Controllers\Admin
 */
class BePartnerRequestController extends AdminController
{
    public function index()
    {
        $data = BePartnerRequest::with(['user', 'curator', 'quittance', 'package'])
            ->orderByDesc('id')
            //->paginate(20);
            ->get();
        $packages = Package::all();

        return view('admin.be_partner_request')
            ->with('data', $data)
            ->with('packages', $packages);
    }

    public function update(Request $request, $id)
    {
        $model = BePartnerRequest::findOrFail($id);

        if ($request->has('is_confirmed') && !$model->is_confirmed) {
            $model->is_confirmed = $request->is_confirmed;
            $model->package_id = $request->package_id;
            $model->save();

            // remove badge (if exists)
            $model->unmarkBadgeFor(Auth::user());

            return $model;
        }
    }

    public function canceled(Request $request, $id)
    {
        $model = BePartnerRequest::findOrFail($id);

        if ($model->is_confirmed == 0) {

            // remove badge (if exists)
            $model->unmarkBadgeFor(Auth::user());

            //remove request
            $remove_request = BePartnerRequest::query()->where('id', '=', $id)->delete();

            return $model;
        }
    }
}
