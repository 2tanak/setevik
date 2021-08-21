<?php

namespace App\Traits;

use App\Models\File;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

trait Newsable
{
    use FileableMulti;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function announcePic()
    {
        return $this->belongsTo(File::class, 'announce_pic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detailPic()
    {
        return $this->belongsTo(File::class, 'detail_pic_id');
    }

    /**
     *
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    public function attachAnnouncePic(UploadedFile $file)
    {
        $dir = sprintf('/%s/%s', ltrim($this->getDefaultDir(), '/'), Carbon::now()->format('Y/m/d'));
        $disk = 'local';
		
        
        $path = $file->store($dir, $disk);
		
		if(isset($this->detailPic->path)){
		$pathDelete= str_replace('public','storage',$this->announcePic->path);
        @chmod('./'.$pathDelete, 0777);
        @unlink('./'.$pathDelete);
        }
		
//public/oss-news/files/2020/10/30/74JS2KH9PzaErvyldrROhDfqvguMv03TuH0MKt8B.jpeg
        $file = File::create([
            'disk'          => $disk,
            'dir'           => $dir,
            'path'          => $path,
            'size'          => $file->getSize(),
            'mime_type'     => $file->getClientMimeType(),
            'name'          => ltrim($path, $dir),
            'original_name' => $file->getClientOriginalName(),
        ]);

        $this->announcePic()->associate($file)->save();
    }

    /**
     *
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    public function attachDetailPic(UploadedFile $file)
    {
        $dir  = sprintf('/%s/%s', ltrim($this->getDefaultDir(), '/'), Carbon::now()->format('Y/m/d'));
        $disk = 'local';

		
        $path = $file->store($dir, $disk);
		if(isset($this->detailPic->path)){
		$pathDelete= str_replace('public','storage',$this->detailPic->path);
        @chmod('./'.$pathDelete, 0777);
        @unlink('./'.$pathDelete);
		}
        $file = File::create([
            'disk'          => $disk,
            'dir'           => $dir,
            'path'          => $path,
            'size'          => $file->getSize(),
            'mime_type'     => $file->getClientMimeType(),
            'name'          => ltrim($path, $dir),
            'original_name' => $file->getClientOriginalName(),
        ]);

        $this->detailPic()->associate($file)->save();
		
		
    }
}