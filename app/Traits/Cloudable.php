<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

trait Cloudable
{
    use Fileable;

    /**
     * Getting presigned URL
     *
     * @param null $expiry
     * @return string
     */
    public function getUri($expiry = null)
    {
        if ($this->file) {
            $s3     = Storage::disk('s3');
            $client = $s3->getAdapter()->getClient();
            $expiry = is_null($expiry)
                ? '+180 minutes'
                : $expiry;

            $command = $client->getCommand('GetObject', [
                'Bucket' => Config::get('filesystems.disks.s3.bucket'),
                'Key'    => $this->file->path,
            ]);

            $request = $client->createPresignedRequest($command, $expiry);
            return (string) $request->getUri();
        }

        return '';
    }
}