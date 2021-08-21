<?php

namespace App\Http\Controllers\Oss;

use App\Models\LearnVideo;
use App\Models\LearnVideoType;
use App\Models\LearnVideoConfirm;
use App\Services\VideoStreamService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttestationController extends OssController
{
    /**
     *
     */
    public function hasAccess()
    {
        //
    }

    /**
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $types = LearnVideoType::with(['videos'])->get();

        // for videos in next type
        $hasAccessToNext = true;

        // types
        foreach ($types as $type) {

            // videos
            foreach ($type->videos as $index => $video) {

                // filtering
                if ($video->isConfirmedBy($user)) {
                    $video->hasAccess = true;
                } else {

                    //if ($user->hasActivityAsResident()) {

                        if ($hasAccessToNext) {
                            if ((int) $video->parent_id == 0) {
                                $video->hasAccess = true;
                            } elseif ($video->getParent()->isConfirmedBy($user)) {
                                $video->hasAccess = true;
                            }
                        } else {
                            $video->hasAccess = false;
                        }

                    //} else {
                        //$video->hasAccess = false;
                    //}
                }

                // check by last video in current type
                if ($type->videos->count() && $type->videos->count() == ($index + 1)) {

                    // if only already been tree
                    if ($hasAccessToNext) {
                        if ($video->isConfirmedBy($user)) {
                            $hasAccessToNext = true;
                        } else {
                            $hasAccessToNext = false;
                        }
                    }
                }
            }
        }

        return view('oss.attestation')->with('types', $types);
    }

    /**
     *
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $learnVideo = LearnVideo::findOrFail($id);

        $publicLink = $learnVideo->getUri();
        $learnVideo->public_link = $publicLink;

        if (!$learnVideo->file) {
            return abort(404);
        }

        if ($learnVideo->confirms->contains('user_id', Auth::id())) {
            $learnVideo->isConfirmed = true;
        }


        return view('oss.attestation_detail')->with('video', $learnVideo);
    }

    /**
     * Viewing confirmation
     *
     * @param $id
     */
    public function confirm($id)
    {
        $user = Auth::user();
        $video = LearnVideo::findOrFail($id);

        if ($video->confirms->contains('user_id', $user->id) == false) {
            $confirm = new LearnVideoConfirm();
            $confirm->video()->associate($video);
            $confirm->user()->associate($user);
            $confirm->save();
        }
    }

    /**
     * Set video as watched
     *
     * @param $id - BroadcastVideo ID
     */
    public function watch($id)
    {
        LearnVideo::findOrFail($id)->watchedBy(Auth::id());
    }
}
