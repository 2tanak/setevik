<?php

namespace App\Console\Commands\Avatar;

use App\Models\Package;
use App\User;

use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Импортируем аватарки из файла взятые с sib.company (bitrix)
 *
 * @package App\Console\Commands\Avatar
 */
class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatar:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing avatars from directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $dir = __DIR__.'/files/*';
        $listOfNotFound = [];

        //
        foreach (glob($dir) as $filePath) {

            $fileName = ltrim($filePath, $dir);
            $exploded = explode(',', $fileName);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            $name   = $exploded[0];
            $login  = rtrim($exploded[1], sprintf('.%s', $extension));
            $user   = User::where('login', $login)->first();

            if ($user) {

                $image      = new UploadedFile($filePath, $name);
                $filename   = sprintf('%d_%d.%s', $user->id, time(), $image->getClientOriginalExtension());

                $image_resize = Image::make($image->getRealPath());
                //$image_resize->resize(150, 150);
                $image_resize->save(public_path('storage/avatars/' .$filename));

                $user->photo = sprintf('/storage/avatars/%s', $filename);
                $user->save();

            } else {
                $listOfNotFound[] = $login;
            }

            if (count($exploded) != 2) {
                throw new \Exception(sprintf('Unknown file format %s', $filePath));
            }
        }

        if (count($listOfNotFound)) {
            $this->info('Not found');
            dd($listOfNotFound);
        }
    }
}
