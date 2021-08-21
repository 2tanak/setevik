<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth' ], function () {
    //$users = \DB::select("SELECT * FROM `users` WHERE email = 'anastasia.yerakhtina@gmail.com'");
   //dd($users);

    //$users = \DB::select("SELECT * FROM `users` WHERE last_name = 'Narumbayev'");
    //dd($users);

    // Главная
    Route::get('/', function () {
        $user = auth()->user();

        if ($user->hasRole('partner-na')) {
            return view('sib.info.activation');
        } elseif ($user->hasRole('resident-na')) {
            return view('oss.info.activation');
        } else {
            if ($user->hasRole('partner')) {
                return redirect()->route('sib_news');
            } else {
                return redirect()->route('attestation');
            }
        }
    })->name('home');

    // Профиль
    Route::get('/profile', 'ProfileController@index')->name('profile');
    //Route::post('/profile', 'ProfileController@update');
    //Route::post('/admin/oss/news', 'NewsController@store');
    Route::post('/profile/{id}', 'ProfileController@update');
    Route::post('/profilesing', 'ProfileController@sing');
    Route::post('restore-password', 'RestorePasswordController@restore_password')->name('restore_password');



    // Поиск
    Route::get('/search', 'SearchController@index')->name('search');

    // Квитацнии
    Route::get('/quittance/{id}/image', 'QuittanceController@image');
    Route::get('/quittance/{id}/download', 'QuittanceController@download');

    // Резиденты (и партнеры)
    Route::post('/residents/{id}/wizard', 'ResidentController@activateWizard');

    /*
     * SIB
     */
    Route::namespace('Sib')->group(function () {

        // Финансы
        Route::namespace('Finance')->group(function () {
            // Кошелек
            Route::get('/finance/wallet', 'WalletController@index')->name('wallet');
            //Добавление заявки на вывод средств
            Route::post('/finance/wallet/order', 'WalletController@order')->name('order_create');

            // Активность
            Route::get('/finance/activity', 'ActivityController@index')->name('activity');

            // Платежный календарь
            Route::get('/finance/payment-calendar', 'PaymentCalendarController@index');
        });

        // Классическая схема
        Route::get('/classic', 'ClassicController@index');

        // Лично приглашенные
        Route::get('/personal-invited', 'PersonalInvitedController@index');

        // Я и моя команда
        Route::get('/me-and-my-team/{nodeId?}', 'BinaryTreeController@index')->where('nodeId', '[0-9]+');

        // Я и моя команда (получение реф.ссылки)
        Route::get('/me-and-my-team/links/{nodeId}', 'BinaryTreeController@getLink');

        // Образование -> Партнерское
        Route::get('/education/partners', 'Education\PartnersController@index');

        // Образование -> Школа ERA
        Route::get('/education/school-era', 'Education\SchoolEraController@index');

        // Информация
        Route::namespace('Info')->group(function () {
            // Новости
            Route::get('/info/news', 'NewsController@index')->name('sib_news');
            Route::get('/info/news/{id}', 'NewsController@show');

            // Линейка событии
            Route::get('/info/events', 'EventsController@index');
            Route::get('/info/events/{id}', 'EventsController@show');
            Route::get('/info/events/list', 'EventsController@getList');

            // Промо и акции
            Route::get('/info/promos', 'PromoAndActionsController@index');
            Route::get('/info/promos/{id}', 'PromoAndActionsController@show');

            // Документация
            Route::get('/info/documents', 'DocumentsController@index');
            Route::get('/info/documents/{id}', 'DocumentsController@show');

            // Контакты
            Route::get('/info/contacts', 'ContactsController@index');
        });
    });


    /*
     * OSS (available for partners)
     */
    Route::namespace('Oss')->group(function () {

        // WakeUpEra
        Route::get('/oss/wake-up-era', 'WakeUpEraController@index');
        Route::get('/oss/wake-up-era/{id}/watched', 'WakeUpEraController@watch');
        Route::get('/oss/wake-up-era/broadcast-videos/{id}/source', 'WakeUpEraController@source');

        // Аттестация
        Route::get('/oss/attestation', 'AttestationController@index')->name('attestation');
        Route::get('/oss/attestation/{id}', 'AttestationController@show');
        Route::get('/oss/attestation/{id}/watched', 'AttestationController@watch');
        Route::get('/oss/attestation/{id}/confirmed', 'AttestationController@confirm');

        // Кошелек
        Route::get('/oss/wallet', 'WalletController@index');

        // Заявки
        Route::get('/oss/requisitions', 'RequisitionController@index');
        Route::post('/oss/requisitions', 'RequisitionController@store');
        Route::post('/oss/requisitions/{id}/curator-file-upload', 'RequisitionController@curatorFileUpload');
        Route::get('/oss/requisitions/requisitioncancel', 'RequisitionController@requisitioncancel');
        Route::post('/oss/requisitions/requisitioncancel', 'RequisitionController@requisitioncancel');

        // Команда
        Route::get('/oss/teams', 'TreeController@index');

        // Продукты
        Route::get('/oss/products', 'ProductController@index');
        Route::get('/oss/products/{id}/link', 'ProductController@getLink');

        // Соревнования
        Route::get('/oss/competitions', 'CompetitionsController@index');

        // Информация
        Route::namespace('Info')->group(function () {
            // Новости
            Route::get('/oss/info/news', 'NewsController@index');
            Route::get('/oss/info/news/{id}', 'NewsController@show');

            // Документация
            Route::get('/oss/info/documents', 'DocumentsController@index');

            // Контакты
            Route::get('/oss/info/contacts', 'ContactsController@index');
        });

        // Активация партнерки
        Route::get('/oss/be-partner-request', 'BePartnerRequestController@index');
        Route::post('/oss/be-partner-request', 'BePartnerRequestController@store');
        Route::post('/oss/be-partner-request/{id}/upload', 'BePartnerRequestController@fileUpload');
        Route::put('/oss/be-partner-request/{id}', 'BePartnerRequestController@update');
    });


    /*
     * Admin
     */
    Route::namespace('Admin')->group(function () {
        // Admin panel
        Route::get('/admin', function () {
            return redirect()->route('requisitions');
        });

        // Пользователи
        Route::get('/admin/users', 'UserController@index')->name('users');
        Route::get('/admin/users/{id}', 'UserController@show');
        Route::put('/admin/users/{id}', 'UserController@update');
        Route::post('/admin/users/{id}', 'UserController@updateUserData'); // todo: put (with 'multipart/form-data')
        Route::get('/admin/detailsuser/{id}', 'UserController@detailsuser');
        Route::get('/admin/handupdatedate/{id}', 'UserController@handupdatedate');
        Route::get('/admin/handupdatedatesuccess', 'UserController@handupdatedatesuccess');
        Route::post('/admin/handupdatedatesuccess', 'UserController@handupdatedatesuccess');
        Route::get('/admin/handupdatebonuses/{id}', 'UserController@handupdatebonuses');
        Route::get('/admin/handupdatebonusessuccess/{binary_id}', 'UserController@handupdatebonusessuccess');
        Route::get('/admin/handupdatebonusessuccess', 'UserController@handupdatebonusessuccess');
        Route::post('/admin/handupdatebonusessuccess', 'UserController@handupdatebonusessuccess');
        Route::get('/admin/updatebinarypoints', 'UserController@updatebinarypoints');
        Route::get('/admin/updatebinarypoints/{id}', 'UserController@updatebinarypoints');
        Route::get('/admin/usertoptree/{id}', 'UserController@usertoptree');
        Route::get('/admin/updatebinary', 'UserController@updatebinary');
        Route::get('/admin/updatebinary/{id}', 'UserController@updatebinary');
        Route::get('/admin/updatebinary/{id}/{package}/{date}', 'UserController@updatebinary');
        Route::get('/admin/updatebinary/{id}/{tekpackage}/{package}/{date}', 'UserController@updatebinary');
        Route::get('/admin/updatebinaryview/{id}/{tekpackage}/{package}/{date}', 'UserController@updatebinaryview');
        Route::get('/admin/handmerge/{id}', 'UserController@handmerge');
        Route::get('/admin/handfullrestore/{id}', 'UserController@handfullrestore');
        Route::get('/admin/handrestore/{id}', 'UserController@handrestore');
        Route::get('/admin/handbackup/{id}', 'UserController@handbackup');
        Route::get('/admin/handsave/{id}', 'UserController@handsave');
        Route::get('/admin/handupdate/{id}', 'UserController@handupdate');
        Route::get('/admin/handupdate/{id}/{old_id}', 'UserController@handupdate');
        Route::get('/admin/handupdate/{id}/{old_id}/{date}', 'UserController@handupdate');
        Route::get('/admin/testupdate/{id}/{date}', 'UserController@testupdate');
        Route::get('/admin/testcreate/{id}', 'UserController@testupdate');
        Route::get('/admin/testcreate', 'UserController@testupdate');
        Route::get('/admin/testcreate/{id}/{date}', 'UserController@testupdate');
        Route::get('/admin/testupdate/{id}', 'UserController@testupdate');
        Route::get('/admin/testupdate', 'UserController@testupdate');
        Route::put('/admin/users/{id}', 'UserController@update');
        Route::post('/admin/users/{id}', 'UserController@updateUserData'); // todo: put (with 'multipart/form-data')

        // Запросы SIB
        Route::get('/admin/be-partner-requests', 'BePartnerRequestController@index');
        Route::put('/admin/be-partner-requests/{id}', 'BePartnerRequestController@update');
        Route::get('/admin/be-partner-requests/{id}', 'BePartnerRequestController@update');
        Route::put('/admin/be-partner-requests/canceled/{id}', 'BePartnerRequestController@canceled');
        Route::post('/admin/be-partner-requests/canceled/{id}', 'BePartnerRequestController@canceled');
        Route::get('/admin/be-partner-requests/canceled/{id}', 'BePartnerRequestController@canceled');


        // Заявки
        Route::get('/admin/requisitions', 'RequisitionsController@index')->name('requisitions');
        Route::post('/admin/requisition/{id}', 'RequisitionsController@update');

        //Заявки на вывод средств
        Route::get('/admin/orders', 'OrdersController@index');
        Route::get('/admin/orders/{id}', 'OrdersController@update');
        Route::get('/admin/orders/view/{id}', 'OrdersController@view');
        Route::post('/admin/orders/statusupdate', 'OrdersController@statusupdate');
        Route::post('/admin/orders/statusupdate/{id}', 'OrdersController@statusupdate');
        Route::post('/admin/orders/orderrewrite', 'OrdersController@orderrewrite');
        Route::post('/admin/orders/orderrewrite/{id}', 'OrdersController@orderrewrite');
        Route::post('/admin/orders/withdrawstatus', 'OrdersController@withdrawstatus');
        Route::post('/admin/orders/withdrawstatus/{id}', 'OrdersController@withdrawstatus');

        // Журнал
        Route::get('/admin/journal', 'JournalController@index');

        Route::namespace('Sib')->group(function () {

            // Новости
            Route::get('/admin/sib/news', 'NewsController@index');
            Route::get('/admin/sib/news/{id}', 'NewsController@show');
            Route::post('/admin/sib/news', 'NewsController@store');
            Route::post('/admin/sib/news/{id}', 'NewsController@update'); // todo: put (with 'multipart/form-data')
            Route::delete('/admin/sib/news/{id}', 'NewsController@delete');
            Route::post('/admin/sib/news/{id}/files', 'NewsController@uploadFile');
            Route::delete('/admin/sib/news/{id}/files/{fileId}', 'NewsController@deleteFile');
            Route::post('/admin/sib/news/delete/{id}', 'NewsController@delete');

            // Линейка событии
            Route::get('/admin/sib/events', 'EventsController@index');
            Route::get('/admin/sib/events/{id}', 'EventsController@show');
            Route::post('/admin/sib/events', 'EventsController@store');
            Route::post('/admin/sib/events/{id}', 'EventsController@update'); // todo: put (with 'multipart/form-data')
            Route::delete('/admin/sib/events/{id}', 'EventsController@delete');
            Route::post('/admin/sib/events/{id}/files', 'EventsController@uploadFile');
            Route::delete('/admin/sib/events/{id}/files/{fileId}', 'EventsController@deleteFile');
            Route::post('/admin/sib/events/delete/{id}', 'EventsController@delete');

            // Промо и акции
            Route::get('/admin/sib/promos', 'PromoController@index');
            Route::get('/admin/sib/promos/{id}', 'PromoController@show');
            Route::post('/admin/sib/promos', 'PromoController@store');
            Route::post('/admin/sib/promos/{id}', 'PromoController@update'); // todo: put (with 'multipart/form-data')
            Route::delete('/admin/sib/promos/{id}', 'PromoController@delete');
            Route::post('/admin/sib/promos/{id}/files', 'PromoController@uploadFile');
            Route::delete('/admin/sib/promos/{id}/files/{fileId}', 'PromoController@deleteFile');
            Route::post('/admin/sib/promos/delete/{id}', 'PromoController@delete');

            // Документация
            Route::get('/admin/sib/documents', 'DocumentsController@index');
            Route::get('/admin/sib/documents/{id}', 'DocumentsController@show');
            Route::put('/admin/sib/documents/{id}', 'DocumentsController@update');
            Route::post('/admin/sib/documents', 'DocumentsController@store');
            Route::delete('/admin/sib/documents/{id}', 'DocumentsController@delete');
            Route::post('/admin/sib/documents/{id}/files', 'DocumentsController@uploadFile');
            Route::delete('/admin/sib/documents/{documentId}/files/{fileId}', 'DocumentsController@deleteFile');
        });

        // OSS
        Route::namespace('Oss')->group(function () {
            // Абонементы
            Route::get('/admin/oss/subscriptions', 'SubscriptionController@index');

            // Change curator
            Route::get('/admin/oss/change-curator', 'ChangeCuratorController@index');
            Route::get('/admin/oss/change-curator/{id}/curators', 'ChangeCuratorController@curators');
            Route::put('/admin/oss/change-curator/{id}/change', 'ChangeCuratorController@changeCurator');

            // Новости OSS
            Route::get('/admin/oss/news', 'NewsController@index');
            Route::get('/admin/oss/news/{id}', 'NewsController@show');
            Route::post('/admin/oss/news', 'NewsController@store');
            Route::post('/admin/oss/news/{id}', 'NewsController@update'); // todo: put (with 'multipart/form-data')
            Route::delete('/admin/oss/news/{id}', 'NewsController@delete');
            Route::post('/admin/oss/news/delete/{id}', 'NewsController@delete');
            Route::post('/admin/oss/news/{id}/files', 'NewsController@uploadFile');
            Route::delete('/admin/oss/news/{id}/files/{fileId}', 'NewsController@deleteFile');

            // WakeUpEra
            Route::get('/admin/oss/wake-up-era', 'WakeUpEraController@index');
            Route::namespace('WakeUpEra')->group(function () {
                // Broadcasts
                Route::post('/admin/oss/wake-up-era/broadcasts', 'BroadcastController@store');
                Route::put('/admin/oss/wake-up-era/broadcasts/{id}', 'BroadcastController@update');
                Route::delete('/admin/oss/wake-up-era/broadcasts/{id}', 'BroadcastController@delete');

                // Videos
                Route::post('/admin/oss/wake-up-era/broadcast-videos', 'VideoController@store');
                Route::post('/admin/oss/wake-up-era/broadcast-videos/{id}', 'VideoController@update'); // todo: put (with 'multipart/form-data')
                Route::delete('/admin/oss/wake-up-era/broadcast-videos/{id}', 'VideoController@delete');
                Route::get('/admin/oss/wake-up-era/broadcast-videos/{id}/source', 'VideoController@source');
            });

            // Обучение (Аттестация)
            Route::get('/admin/oss/attestation', 'AttestationController@index');
            Route::post('/admin/oss/attestation', 'AttestationController@store');
            Route::post('/admin/oss/attestation/{id}', 'AttestationController@update'); // todo: put (with 'multipart/form-data')
            Route::delete('/admin/oss/attestation/{id}', 'AttestationController@delete');
            Route::get('/admin/oss/attestation/{id}/source', 'AttestationController@source');
        });

        // Настройки
        Route::get('/admin/settings', 'SettingsController@index')->name('settings');
        Route::get('/admin/delfincalendar', 'SettingsController@delfincalendar')->name('delfincalendar');
        Route::get('/admin/delwallets', 'SettingsController@delwallets')->name('delwallets');
        Route::get('/admin/addorders', 'SettingsController@addorders')->name('addorders');
        Route::get('/admin/updateosstree', 'SettingsController@updateosstree')->name('updateosstree');
        Route::get('/admin/osstreedataupdate', 'SettingsController@osstreedataupdate')->name('osstreedataupdate');
        Route::get('/admin/osstreedatainfo', 'SettingsController@osstreedatainfo')->name('osstreedatainfo');
        Route::get('/admin/osstreedataempty', 'SettingsController@osstreedataempty')->name('osstreedataempty');
        Route::get('/admin/ossfixrequest', 'SettingsController@ossfixrequest')->name('ossfixrequest');
        Route::get('/admin/requestremove', 'SettingsController@requestremove')->name('requestremove');
        Route::get('/admin/badgeremove', 'SettingsController@badgeremove')->name('badgeremove');
        Route::get('/admin/packagesrestore', 'SettingsController@packagesrestore')->name('packagesrestore');
        Route::get('/admin/packagesrestore/{id}', 'SettingsController@packagesrestore')->name('packagesrestore');
        Route::get('/admin/addbadges', 'SettingsController@addbadges')->name('addbadges');
        Route::get('/admin/osstreedatainfoupdater', 'SettingsController@osstreedatainfoupdater')->name('osstreedatainfoupdater');
        Route::get('/admin/osstreedata', 'SettingsController@osstreedata')->name('osstreedata');
        Route::get('/admin/newsbadgable', 'SettingsController@newsbadgable')->name('newsbadgable');

        // Packages
        Route::get('/packages', function () {
            return Response::json(\App\Models\Package::all(), 200);
        });
        Route::get('/packages/{id}', function ($id) {
            return Response::json(\App\Models\Package::findOrFail($id), 200);
        });

        // Отчеты
        Route::namespace('Reports')->group(function () {
            // Финансовая аналитика
            Route::get('/admin/reports/financial-analytics', 'FinancialAnalyticsController@index');

            // Рейтинг чеков
            Route::get('/admin/reports/check-ratings', 'CheckRatingController@index');

            // ФЦ за период
            Route::get('/admin/reports/fc-per-period', 'FcPerPeriodController@index');

            // ФЦ по фин. периодам
            Route::get('/admin/reports/fc-per-fp', 'FcPerFpController@index');

            // История начисления ББС
            Route::get('/admin/reports/bbs-history', 'BbsHistoryController@index');

            // История выплат
            Route::get('/admin/reports/payment-history', 'PaymentHistoryController@index');

            // Баллы по бинару
            Route::get('/admin/reports/binary-points', 'BinaryPointsController@index');
        });

        // Тест
        Route::get('/admin/test', 'TestController@index')->name('test');
        Route::post('/admin/test', 'TestController@update');
    });
});

/*
 * Custom registration for residents
 */

Route::get('/register/oss', 'Auth\RegisterController@showRegistrationFormResident')->name('register.oss');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

/*
 * Default auth routes
 */
Auth::routes();
