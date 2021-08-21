<?php

namespace App\Traits;

use App\Models\File;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Fileable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function getUrl()
    {
        $file = $this->file;

        if ($file) {
            return Storage::disk($file->disk)->url($file->path);
        }

        return '';
    }

    /**
     *
     *
     * @return mixed
     * @throws \Exception
     */
    public function getDefaultDir()
    {
        if (is_null($this->defaultDir)) {
            throw new \Exception('Default directory not found in '.__CLASS__);
        }
        return $this->defaultDir;
    }

    /**
     * Detach file from model and mark as deleted
     *
     * @return void
     */
    public function detachFile()
    {
        $file = $this->file;
        if ($file) {
            $this->file_id = null;
            $this->save();
            $file->update(['is_deleted' => true]);
        }
    }

    /**
     * Store file and associate itself
     *
     * @param UploadedFile $file
     * @param null $disk
     * @param null $dir
     * @throws \Exception
     */
    public function attachFile(UploadedFile $file, $disk = null, $dir = null)
    {
        $directory = is_null($dir)
            ? sprintf('/%s/%s', ltrim($this->getDefaultDir(), '/'), Carbon::now()->format('Y/m/d'))
            : $dir;

        $disk = is_null($disk)
            ? 'local'
            : $disk;

        $model = $this->storeFile($file, $disk, $directory);
        $this->file()->associate($model)->save();
    }

    /**
     * Store file to disk
     *
     * @param UploadedFile $file
     * @param $disk
     * @param $dir
     * @return File|\Illuminate\Database\Eloquent\Model
     */
    public function storeFile(UploadedFile $file, $disk, $dir)
    {
        $path = $file->store($dir, $disk);

        return File::create([
            'disk'          => $disk,
            'dir'           => $dir,
            'path'          => $path,
            'size'          => $file->getSize(),
            'mime_type'     => $file->getClientMimeType(),
            'name'          => ltrim($path, $dir),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}