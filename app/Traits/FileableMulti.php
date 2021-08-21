<?php

namespace App\Traits;

use App\Models\File;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

trait FileableMulti
{
    use Fileable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    /**
     * Detach file from model and mark as deleted
     *
     * @param $file
     */
    public function detachFile($file)
    {
        $file = ($file instanceof File) ? $file : File::findOrFail($file);
        $files = $this->files()
            ->where('id', '!=', $file->id)
            ->get();
        $this->files()->sync($files);
        $file->update(['is_deleted' => true]);
    }

    /**
     * Detach all files from model and mark as deleted
     *
     * @return void
     */
    public function detachAllFiles()
    {
        $files = $this->files;
        $this->files()->sync([]);
        $files->each(function ($item) {
            $item->update(['is_deleted' => true]);
        });
    }

    public function attachFiles($files, $disk = null, $dir = null)
    {
        $directory = is_null($dir)
            ? sprintf('/%s/%s', ltrim($this->getDefaultDir(), '/'), Carbon::now()->format('Y/m/d'))
            : $dir;

        $disk = is_null($disk)
            ? 'local'
            : $disk;

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $model = $this->storeFile($file, $disk, $directory);

                $this->files()->save($model);
            }
        }
    }
}