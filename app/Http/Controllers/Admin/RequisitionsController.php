<?php

namespace App\Http\Controllers\Admin;

use App\Models\Requisition;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class RequisitionsController extends AdminController
{
    public function index()
    {
        $data = Requisition::with([
            'user',
            'curator',
            'product',
            'requisitionType',
            'userQuittance',
            'curatorQuittance',
        ])
            ->where(function ($query) {
                $query
                    ->whereNotNull('curator_quittance_id')
                    ->orWhere('is_confirmed', true);
            })
            ->orderByDesc('id')
            //->paginate(20);
            ->get();

        return view('admin.requisition', compact('data'));
    }

    /**
     * Update by Ajax
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {

		
        try {
            $requisition = Requisition::findOrFail($id);
            $requisition->update($request->all());

            return Response::json([
                'message' => 'done',
            ], 200);

        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
