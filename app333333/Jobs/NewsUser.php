<?php

namespace App\Jobs;

use App\Models\Badge;
use App\Models\News;
use App\Models\OssNews;
use App\Role;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Traits\Badgable;
use App\Traits\Taggable;
use App\Traits\Newsable;

class NewsUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Badgable, Taggable, Newsable;

    protected $news;
   // public $timeout=120;  //лимит на выполнение задачи
    public $tries = 1;      //лимит повторных попыток выполнения задачи

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($news)
    {
        $this->news = $news;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->news->attachBadgeFor(User::all());
        //

        //OSS news событие во время создания новости
        $oss_news = OssNews::where('created_at', '<=', Carbon::now())->where('is_badgable', '=', '0')->first();
        $sib_news = News::where('created_at', '<=', Carbon::now())->where('is_badgable', '=', '0')->first();
        if ((int)$oss_news->count() > 0) {
            echo "NEW OSS NEWS ADDED\n";
            echo "NEW OSS NEWS ID - " . $oss_news->id . "\n";
            echo "NEW OSS NEWS TITLE - " . $oss_news->title . "\n";
            echo "NEW OSS NEWS ACTIVATED - " . $oss_news->is_active . "\n";

            //Если есть отметка активности - проставляем бейджи
            if ($oss_news->is_active !== NULL && !empty($oss_news->is_active)) {
                //Обновляем статус записи
                $oss_news_update = OssNews::where('id', '=', $oss_news->id)->first();
                $oss_news_update->is_badgable = 1;
                $oss_news_update->save();

                //получаем пользователей
                $users = User::where('is_active', '=', '1')->whereNotNull('oss_registered_at')->get();
                //$users = User::query()->get();

                foreach ($users as $user) {
                    echo "USER ID - " . $user->id . "\n";
                    echo "USER NAME - " . $user->name . "\n";

                    //Добавляем новость в список для просмотра
                    $badge_first = new Badge();
                    $badge_first->menu_id = 35;
                    $badge_first->user_id = $user->id;
                    $badge_first->badgable_id = $oss_news->id;
                    $badge_first->badgable_type = 'oss_news';
                    $badge_first->save();

                    //Добавляем бейджи о добавлении новой новости в меню 2
                    $badge_second = new Badge();
                    $badge_second->menu_id = 51;
                    $badge_second->user_id = $user->id;
                    $badge_second->badgable_id = $oss_news->id;
                    $badge_second->badgable_type = 'oss_news';
                    $badge_second->save();
                }
                //Обновляем статус записи
                $oss_news_update = OssNews::where('id', '=', $oss_news->id)->first();
                $oss_news_update->is_badgable = 2;
                $oss_news_update->save();

                //Убиваем задачу в списке
                $task = Job::query()->where('attempts', '>=', '1')->first()->delete();

                $oss_news = NULL;
                die();
            }
        } else {
            //Если нету новостей для OSS - обрабатываем задачу для SIB
            if ((int)$sib_news->count() > 0) {
                echo "NEW SIB NEWS ADDED\n";
                echo "NEW SIB NEWS ID - " . $sib_news->id . "\n";
                echo "NEW SIB NEWS TITLE - " . $sib_news->title . "\n";
                echo "NEW SIB NEWS ACTIVATED - " . $sib_news->is_active . "\n";

                //Если есть отметка активности - проставляем бейджи
                if ($sib_news->is_active !== NULL && !empty($sib_news->is_active)) {
                    //Обновляем статус записи
                    $sib_news_update = News::where('id', '=', $sib_news->id)->first();
                    $sib_news_update->is_badgable = 1;
                    $sib_news_update->save();

                    //получаем пользователей
                    $users = User::where('is_active', '=', '1')->whereNotNull('sib_registered_at')->get();

                    foreach ($users as $user) {
                        echo "USER ID - " . $user->id . "\n";
                        echo "USER NAME - " . $user->name . "\n";

                        //Добавляем бейджи о добавлении новой новости в меню 1
                        $badge_first = new Badge();
                        $badge_first->menu_id = 47;
                        $badge_first->user_id = $user->id;
                        $badge_first->badgable_id = $sib_news->id;
                        $badge_first->badgable_type = 'news';
                        $badge_first->save();

                      //Добавляем бейджи о добавлении новой новости в меню 2
//                    $badge_second = new Badge();
//                    $badge_second->menu_id = 51;
//                    $badge_second->user_id = $user->id;
//                    $badge_second->badgable_id = $oss_news->id;
//                    $badge_second->badgable_type = 'oss_news';
//                    $badge_second->save();
                    }
                    //Обновляем статус записи
                    $sib_news_update = News::where('id', '=', $sib_news->id)->first();
                    $sib_news_update->is_badgable = 2;
                    $sib_news_update->save();

                    //Убиваем задачу в списке
                    $task = Job::query()->where('attempts', '>=', '1')->first()->delete();

                    $sib_news = NULL;
                    die();
                }
            }
        }
    }
}
