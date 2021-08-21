<?php

namespace App\Http\Controllers;

use App\Models\Video\Video;
use App\Services\VideoService;

use App\Models\LearnVideo;
use App\Services\VideoStreamService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{





    /**
     * Streaming video
     *
     * @param Request $request
     * @param $id
     */
    public function stream(Request $request, $id)
    {
        $video = LearnVideo::findOrFail($id);

        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $path = sprintf('%s%s', $storagePath, ltrim($video->file->path, '/'));

        $stream = new VideoStreamService($path);
        $stream->start();
    }




    public function show($id = null)
    {
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        return response()->download(
            sprintf('%s%s', $storagePath, 'public/oss-study/IMG_6753.mp4')
        );
    }

    public function watch(Request $request, $id = null)
    {
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $filePath = sprintf('%s%s', $storagePath, 'public/videos/attestations/1_1600228539.mp4');
        //return response()->download($filePath);




////        header('Transfer-Encoding: chunked');
//        header('Content-Encoding: none');
//
//        // No cache
//        header('Cache-Control: no-cache, no-store, must-revalidate');
//        header('Expires: 0');
//
//        // Metadata
//        //header('Content-Type: video/mp4');
//        header('Content-Type: video/webm');
//
//        $this->other($filePath);

        $stream = new VideoStreamService($filePath);
        $stream->start();
    }

    public function other($file)
    {
        $chunkSize = 256;
        $uploadStart = 0;

        $chunkSize = 43549471;
        $chunkSize = 10000000;


        $handle = fopen($file, "rb");
        $fileSize = filesize($file);

        $start = 0;
        while ($start < $fileSize) {

            if ($start > 20000000) {
                return;
            }

            $start += $chunkSize;

            $chunk = fread($handle, $chunkSize);
            echo $chunk;
            ob_flush();
            flush();
        }



//        while($upload_start < $file_size) {
//
//            $contents = fread($handle, $chunk_size);
//
//            $upload_start += strlen($contents);
//            fseek($handle, $upload_start);
//
//            echo $contents;
//            ob_flush();
//            flush();
//        }

        fclose($handle);
    }
}
