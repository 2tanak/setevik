<?php

namespace App\Http\Controllers\Admin\Oss;

use App\Models\LearnVideo;
use App\Models\LearnVideoType;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class AttestationController extends AdminController
{
    /**
     * Get presigned URL
     *
     * @param $id
     * @return mixed
     */
    public function source($id)
    {
        $learnVideo = LearnVideo::findOrFail($id);
        return (string) $learnVideo->getUri();
    }

    /**
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $attestations = LearnVideo::with(['file', 'type'])->get();
        $types = LearnVideoType::with(['videos', 'videos.file'])->get();

        return view('admin.oss.attestation')
            ->with('attestations', $attestations)
            ->with('types', $types);
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
                'title'                 => 'required|string',
                'learn_video_type_id'   => 'required',
            ]);

            $learnVideo = LearnVideo::findOrFail($id);
            $learnVideo->update($request->all());

            // upload file if sent
            if ($request->hasFile('video')) {

                // delete old video
                if ($learnVideo->file_id) {
                    $learnVideo->file->update(['is_deleted' => true]);
                }

                $learnVideo->attachFile($request->file('video'), 's3');
            }

            return response()->json($learnVideo->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 200);
        }
    }

    /**
     *
     *
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $learnVideo = LearnVideo::findOrFail($id);

        // remove file if exists
        if ($learnVideo->file_id) {
            $learnVideo->file->update(['is_deleted' => true]);
        }

        $learnVideo->delete();
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
                'title'                 => 'required|string',
                'learn_video_type_id'   => 'required',
            ]);

            $learnVideo = new LearnVideo($request->all());
            $learnVideo->save();

            // upload file if sent
            if ($request->hasFile('video')) {
                $learnVideo->attachFile($request->file('video'), 's3');
            }


            return response()->json($learnVideo->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 200);
        }
    }
}
