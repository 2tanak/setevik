<?php

namespace App\Http\Controllers\Admin\Oss\WakeUpEra;

use App\Models\BroadcastVideo;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class VideoController extends AdminController
{
    /**
     * Get presigned URL
     *
     * @param $id
     * @return mixed
     */
    public function source($id)
    {
        $broadcastVideo = BroadcastVideo::findOrFail($id);
        return (string) $broadcastVideo->getUri();
    }

    /**
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        set_time_limit(0);
        try {
            $this->validate($request, [
                'title'         => 'required|string',
                'date'          => 'required|date',
                'description'   => 'required',
                'speaker'       => 'required',
            ]);

            $broadcastVideo = new BroadcastVideo($request->all());
            $broadcastVideo->is_available = filter_var($request->input('is_available'), FILTER_VALIDATE_BOOLEAN);
            $broadcastVideo->save();

            // upload file if exists
            if ($request->hasFile('video')) {
                $broadcastVideo->attachFile($request->file('video'), 's3');
            }

            return response()->json($broadcastVideo->toArray(), 200);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 200);
        }
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
        set_time_limit(0);
        try {
            $this->validate($request, [
                'title'         => 'required|string',
                'date'          => 'required|date',
                'description'   => 'required',
                'speaker'       => 'required',
            ]);

            $broadcastVideo = BroadcastVideo::findOrFail($id);
            $broadcastVideo->update($request->all());

            // upload file if sent
            if ($request->hasFile('video')) {
                // mark old file as deleted if bound
                if ($broadcastVideo->file_id) {
                    $broadcastVideo->file->update(['is_deleted' => true]);
                }

                $broadcastVideo->attachFile($request->file('video'), 's3');
            }

            return response()->json($broadcastVideo->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Delete record
     *
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $broadcastVideo = BroadcastVideo::findOrFail($id);

        // mark file as deleted
        if ($broadcastVideo->file_id) {
            $broadcastVideo->file->update(['is_deleted' => true]);
        }

        $broadcastVideo->delete();
    }
}
