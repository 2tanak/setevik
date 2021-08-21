<?php

namespace App\Http\Controllers\Admin\Sib;

use App\Models\Badge;
use App\Models\Event;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

class EventsController extends AdminController
{
    public function index()
    {
        $data = Event::orderByDesc('id')->get();
        return view('admin.sib.events')->with('data', $data);
    }

    public function show($id)
    {
        $item = Event::with(['announcePic', 'detailPic', 'files'])->find($id);

        if (is_null($item)) {
            $item = new Event();
            $item->id = 0;
        }

        return view('admin.sib.events_detail')->with('item', $item);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'         => 'required',
//            'announce_pic'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'detail_pic'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'announce_pic'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'detail_pic'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

        //
        if ($request->hasFile('announce_pic')) {
            if ($event->announce_pic_id) {
                $event->announcePic->update(['is_deleted' => true]);
            }
            $event->attachAnnouncePic($request->file('announce_pic'));
        }

        //
        if ($request->hasFile('detail_pic')) {
            if ($event->detail_pic_id) {
                $event->detailPic->update(['is_deleted' => true]);
            }
            $event->attachDetailPic($request->file('detail_pic'));
        }

        return $event;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required',
//            'announce_pic'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'detail_pic'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'announce_pic'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'detail_pic'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $event = Event::create($request->all());

        //
        if ($request->hasFile('announce_pic')) {
            $event->attachAnnouncePic($request->file('announce_pic'));
        }

        //
        if ($request->hasFile('detail_pic')) {
            $event->attachDetailPic($request->file('detail_pic'));
        }

        return $event;
    }

    /**
     * Delete
     *
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        //Убираем бейджи о непросмотренной новости, которую удаляем
        $badges = Badge::query()->where('badgable_id', '=', $id)->where('badgable_type', '=', 'events')->delete();

        //Убираем запись
        $event = Event::findOrFail($id);
        $event->detachAllFiles();
        $event->delete();
    }

    /**
     * Delete from document
     *
     * @param $id
     * @param $fileId
     * @return mixed
     */
    public function deleteFile($id, $fileId)
    {
        $event = Event::findOrFail($id);
        $event->detachFile($fileId);
        return $event->files;
    }

    /**
     * Store and attach file
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function uploadFile(Request $request, $id)
    {
        $this->validate($request, [
            'files' => 'required',
        ]);

        $event = Event::findOrFail($id);
        $event->attachFiles($request->file('files'));

        return $event->files;
    }
}
