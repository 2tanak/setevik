<?php

namespace App\Http\Controllers\Admin\Oss;

use App\Models\Badge;
use App\Models\News;
use App\Models\OssNews;
use App\Jobs\NewsUser;

use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;

class NewsController extends AdminController
{
    public function index()
    {
		
	         // $item = OssNews::create(['title'=>4444444444]);
             //dd($item->detachBadgeFor(User::all()));
		
        $data = OssNews::orderByDesc('id')->get();

        return view('admin.oss.news')->with('data', $data);
    }

    public function show($id)
    {
        $item = OssNews::with(['announcePic', 'detailPic', 'files'])->find($id);
        if (is_null($item)) {
			
            $item = new OssNews();
            $item->id = 0;
        }

        return view('admin.oss.news_detail')->with('item', $item);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $request->merge([
            'is_active' => filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $item = OssNews::findOrFail($id);
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

        //$item = OssNews::create($request->all());
        //$item = News::create($request->all());
        $item = OssNews::create($request->all());

        //
        if ($request->hasFile('announce_pic')) {
            $item->attachAnnouncePic($request->file('announce_pic'));
        }

        //
        if ($request->hasFile('detail_pic')) {
            $item->attachDetailPic($request->file('detail_pic'));
        }
	     NewsUser::dispatch($item);
         return $item;
		//$item->attachBadgeFor(User::all());
        
        
    }

    public function delete($id)
    {
        //Убираем бейджи о непросмотренной новости, которую удаляем
        $badges = Badge::query()->where('badgable_id', '=', $id)->where('badgable_type', '=', 'oss_news')->delete();

        //Убираем запись
        $event = OssNews::findOrFail($id);
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
        $item = OssNews::findOrFail($id);
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

        $item = OssNews::findOrFail($id);
        $item->attachFiles($request->file('files'));

        return $item->files;
    }
}
