<?php

namespace App\Http\Controllers\Admin\Sib;

use App\Models\Badge;
use App\Models\News;
use App\Http\Controllers\Admin\AdminController;
use App\Jobs\NewsSibUser;

use Illuminate\Http\Request;

class NewsController extends AdminController
{
    public function index()
    {
		
        $data = News::orderByDesc('id')->get();
        return view('admin.sib.news')->with('data', $data);
    }

    public function show($id)
    {
        $item = News::with(['announcePic', 'detailPic', 'files'])->find($id);

        if (is_null($item)) {
            $item = new News();
            $item->id = 0;
        }

        return view('admin.sib.news_detail')->with('item', $item);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $item = News::findOrFail($id);
        $item->update($request->all());

        //
        if ($request->hasFile('announce_pic')) {
            if ($item->announce_pic_id) {
                $item->announcePic->update(['is_deleted' => true]);
            }
            $item->attachAnnouncePic($request->file('announce_pic'));
        }

        //
        if ($request->hasFile('detail_pic')) {
            if ($item->detail_pic_id) {
                $item->detailPic->update(['is_deleted' => true]);
            }
            $item->attachDetailPic($request->file('detail_pic'));
        }

        return $item;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $item = News::create($request->all());

        //
        if ($request->hasFile('announce_pic')) {
            $item->attachAnnouncePic($request->file('announce_pic'));
        }

        //
        if ($request->hasFile('detail_pic')) {
            $item->attachDetailPic($request->file('detail_pic'));
        }
	     NewsSibUser::dispatch($item);

        return $item;
    }

    public function delete($id)
    {
        //Убираем бейджи о непросмотренной новости, которую удаляем
        $badges = Badge::query()->where('badgable_id', '=', $id)->where('badgable_type', '=', 'news')->delete();

        //Убираем запись
        $news = News::findOrFail($id);
        $news->detachAllFiles();
        $news->delete();
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
        $item = News::findOrFail($id);
        $item->detachFile($fileId);
        return $item->files;
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

        $item = News::findOrFail($id);
        $item->attachFiles($request->file('files'));

        return $item->files;
    }
}
