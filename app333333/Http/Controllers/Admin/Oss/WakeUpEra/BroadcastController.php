<?php

namespace App\Http\Controllers\Admin\Oss\WakeUpEra;

use App\Models\Broadcast;
use App\Http\Controllers\Admin\AdminController;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BroadcastController extends AdminController
{
    /**
     *
     *
     * @param Request $request
     * @param $id
     * @throws \Exception
     */
    public function delete(Request $request, $id)
    {
        $broadcast = Broadcast::findOrFail($id);
        $broadcast->delete();
    }

    /**
     *
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'product_id'    => 'required',
                'link'          => 'required|string',
                'started_at'    => 'nullable|date',
                'expired_at'    => 'nullable|date',
            ]);

            $broadcast = Broadcast::findOrFail($id);
            $broadcast->update($request->all());

            return response()->json($broadcast, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 200);
        }
    }

    /**
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'product_id'    => 'required',
                'link'          => 'required|string',
                'started_at'    => 'nullable|date',
                'expired_at'    => 'nullable|date',
            ]);

            $broadcast = new Broadcast($request->all());
            $broadcast->save();

            return response()->json($broadcast, 200);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 200);
        }
    }
}
