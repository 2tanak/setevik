<?php

namespace App\Providers;

use App\Models\News;
use App\Models\Event;
use App\Models\Promo;
use App\Models\OssNews;
use App\Models\Documents;
use App\Models\LearnVideo;
use App\Models\Requisition;
use App\Models\BroadcastVideo;
use App\Models\BePartnerRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\NewsObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //OssNews::observe(NewsObserver::class);
		
        // @see https://stackoverflow.com/a/23786522
        Schema::defaultStringLength(191);

        // application time (while testing or local)
        if (App::environment(['local', 'testing', 'staging'])) {
            if (Schema::hasTable('times')) {
                $time = DB::table('times')->value('time');
                if ($time) {
                    Carbon::setTestNow($time);
                }
            }
        }
        // polymorphic relations
        Relation::morphMap([
            'news'                  => News::class,
            'oss_news'              => OssNews::class,
            'requisitions'          => Requisition::class,
            'wakeupera_videos'      => BroadcastVideo::class,
            'learn_videos'          => LearnVideo::class,
            'documents'             => Documents::class,
            'be_partner_requests'   => BePartnerRequest::class,
            'events'                => Event::class,
            'promos'                => Promo::class,
        ]);

        // failed queues
        Queue::failing(function (JobFailed $job) {
            \Log::error('failed job', [
                $job->connectionName,
                $job->job,
                $job->exception
            ]);
        });

        // validator: is latin
        Validator::extend('latin', function ($attribute, $value, $parameters, $validator) {
            preg_match('/^[a-zA-Z0-9()*_\-!#$%^&*,."\'\][]+$/', $value, $matches);
            return count($matches) > 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // money convert
        Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
    }
}
