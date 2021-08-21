<?php

use App\Models\Menu;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [

            /*
             * Admin
             */
            [
                'name'          => 'Заявки и запросы',
                'link'          => '/admin/',
                'icon'          => 'fa fa-lg fa-fw fa-check-square-o',
                'cabinet_id'    => 1,
                'children'      => [
                    [
                        'name'          => 'Заявки OSS',
                        'link'          => '/admin/requisitions',
                        'icon'          => 'fa fa-lg fa-fw fa-check-square-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Запросы SIB',
                        'link'          => '/admin/be-partner-requests',
                        'icon'          => 'fa fa-lg fa-fw fa-check-square-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Пользователи',
                'link'          => '/admin/users',
                'icon'          => 'fa fa-lg fa-fw fa-users',
                'cabinet_id'    => 1,
                'children'      => [],
            ],
            [
                'name'          => 'Online Smart System',
                'link'          => '/admin/oss',
                'icon'          => 'fa fa-lg fa-fw fa-database',
                'cabinet_id'    => 1,
                'children'      => [
                    [
                        'name'          => 'Абонементы',
                        'link'          => '/admin/oss/subscriptions',
                        'icon'          => 'fa fa-fw fa-ticket',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Смена куратора',
                        'link'          => '/admin/oss/change-curator',
                        'icon'          => 'fa fa-lg fa-fw fa-refresh',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Новости',
                        'link'          => '/admin/oss/news',
                        'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Обучение',
                        'link'          => '/admin/oss/attestation',
                        'icon'          => 'fa fa-lg fa-fw fa-mortar-board',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'WakeUpERA',
                        'link'          => '/admin/oss/wake-up-era',
                        'icon'          => 'fa fa-lg fa-fw fa-sun-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'SIB',
                'link'          => '/admin/sib',
                'icon'          => 'fa fa-lg fa-fw fa-briefcase',
                'cabinet_id'    => 1,
                'children'      => [
                    [
                        'name'          => 'Новости компании',
                        'link'          => '/admin/sib/news',
                        'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Линейка событий',
                        'link'          => '/admin/sib/events',
                        'icon'          => 'fa fa-lg fa-fw fa-calendar',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Промо и акции',
                        'link'          => '/admin/sib/promos',
                        'icon'          => 'fa fa-lg fa-fw fa-gift',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Документация',
                        'link'          => '/admin/sib/documents',
                        'icon'          => 'fa fa-lg fa-fw fa-file-pdf-o',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Отчеты',
                'link'          => '/admin/reports',
                'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                'cabinet_id'    => 1,
                'children'      => [
                    [
                        'name'          => 'Финансовая аналитика',
                        'link'          => '/admin/reports/financial-analytics',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Рейтинг чеков',
                        'link'          => '/admin/reports/check-ratings',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'ФЦ за период',
                        'link'          => '/admin/reports/fc-per-period',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'ФЦ по фин. периодам',
                        'link'          => '/admin/reports/fc-per-fp',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'История ББС',
                        'link'          => '/admin/reports/bbs-history',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'История выплат',
                        'link'          => '/admin/reports/payment-history',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Баллы по бинару',
                        'link'          => '/admin/reports/binary-points',
                        'icon'          => 'fa fa-lg fa-fw fa-line-chart',
                        'cabinet_id'    => 1,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Настройки',
                'link'          => '/admin/settings',
                'icon'          => 'fa fa-lg fa-fw fa-cog',
                'cabinet_id'    => 1,
                'children'      => [],
            ],
            [
                'name'          => 'Журнал',
                'link'          => '/admin/journal',
                'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                'cabinet_id'    => 1,
                'children'      => [],
            ],
            [
                'name'          => 'Тестирование',
                'link'          => '/admin/test',
                'icon'          => 'fa fa-lg fa-fw fa-cogs',
                'cabinet_id'    => 1,
                'children'      => [],
            ],


            /*
             * SIB
             */
            [
                'name'          => 'Финансы',
                'link'          => '/finance',
                'icon'          => 'fa fa-lg fa-fw fa-money',
                'cabinet_id'    => 2,
                'children'      => [
                    [
                        'name'          => 'Активность',
                        'link'          => '/finance/activity',
                        'icon'          => 'fa fa-lg fa-fw fa-clock-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Кошелёк',
                        'link'          => '/finance/wallet',
                        'icon'          => 'fa fa-lg fa-fw fa-credit-card',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Платежный календарь',
                        'link'          => '/finance/payment-calendar',
                        'icon'          => 'fa fa-lg fa-fw fa-calendar',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Лично приглашенные',
                'link'          => '/personal-invited',
                'icon'          => 'fa fa-lg fa-fw fa-share-alt fa-rotate-90',
                'cabinet_id'    => 2,
                'children'      => [],
            ],
            [
                'name'          => 'Классическая схема',
                'link'          => '/classic',
                'icon'          => 'fa fa-lg fa-fw  fa-chain',
                'cabinet_id'    => 2,
                'children'      => [],
            ],
            [
                'name'          => 'Я и моя команда',
                'link'          => '/me-and-my-team',
                'icon'          => 'fa fa-lg fa-fw fa-users',
                'cabinet_id'    => 2,
                'children'      => [],
            ],
            [
                'name'          => 'Online Smart System',
                'link'          => '/oss',
                'icon'          => 'fa fa-lg fa-fw fa-database',
                'cabinet_id'    => 2,
                'children'      => [
                    [
                        'name'          => 'Новости OSS',
                        'link'          => '/oss/info/news',
                        'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Обучение OSS',
                        'link'          => '/oss/attestation',
                        'icon'          => 'fa fa-lg fa-fw fa-mortar-board',
                        'cabinet_id'    => 3,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Абонементы OSS',
                        'link'          => '/oss/products',
                        'icon'          => 'fa fa-lg fa-fw fa-ticket',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'WakeUpERA',
                        'link'          => '/oss/wake-up-era',
                        'icon'          => 'fa fa-lg fa-fw fa-sun-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Соревнования OSS',
                        'link'          => '/oss/competitions',
                        'icon'          => 'fa fa-lg fa-fw fa-trophy',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Заявки OSS',
                        'link'          => '/oss/requisitions',
                        'icon'          => 'fa fa-lg fa-fw fa-check-square-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Команда OSS',
                        'link'          => '/oss/teams',
                        'icon'          => 'fa fa-lg fa-fw fa-users',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Кошелёк OSS',
                        'link'          => '/oss/wallet',
                        'icon'          => 'fa fa-lg fa-fw fa-credit-card',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Образование',
                'link'          => '/education',
                'icon'          => 'fa fa-lg fa-fw fa-book',
                'cabinet_id'    => 2,
                'children'      => [
                    [
                        'name'          => 'Партнёрское',
                        'link'          => '/education/partners',
                        'icon'          => 'fa fa-lg fa-fw fa-briefcase',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Школа ERA',
                        'link'          => '/education/school-era',
                        'icon'          => 'fa fa-lg fa-fw fa-heart-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                ],
            ],
            [
                'name'          => 'Информация',
                'link'          => '/info',
                'icon'          => 'fa fa-lg fa-fw fa-info-circle',
                'cabinet_id'    => 2,
                'children'      => [
                    [
                        'name'          => 'Новости компании',
                        'link'          => '/info/news',
                        'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Линейка событий',
                        'link'          => '/info/events',
                        'icon'          => 'fa fa-lg fa-fw fa-calendar',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Промо и акции',
                        'link'          => '/info/promos',
                        'icon'          => 'fa fa-lg fa-fw fa-gift',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                    [
                        'name'          => 'Документы (файлы)',
                        'link'          => '/info/documents',
                        'icon'          => 'fa fa-lg fa-fw fa-file-pdf-o',
                        'cabinet_id'    => 2,
                        'children'      => [],
                    ],
                ],
            ],


            /*
             * OSS
             */
            [
                'name'          => 'Новости',
                'link'          => '/oss/info/news',
                'icon'          => 'fa fa-lg fa-fw fa-newspaper-o',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Обучение',
                'link'          => '/oss/attestation',
                'icon'          => 'fa fa-lg fa-fw fa-mortar-board',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Абонементы',
                'link'          => '/oss/products',
                'icon'          => 'fa fa-lg fa-fw fa-ticket',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'WakeUpEra',
                'link'          => '/oss/wake-up-era',
                'icon'          => 'fa fa-lg fa-fw fa-sun-o',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Соревнования',
                'link'          => '/oss/competitions',
                'icon'          => 'fa fa-lg fa-fw fa-trophy',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Заявки',
                'link'          => '/oss/requisitions',
                'icon'          => 'fa fa-lg fa-fw fa-check-square-o',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Команда',
                'link'          => '/oss/teams',
                'icon'          => 'fa fa-lg fa-fw fa-users',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Кошелёк',
                'link'          => '/oss/wallet',
                'icon'          => 'fa fa-lg fa-fw fa-credit-card',
                'cabinet_id'    => 3,
                'children'      => [],
            ],
            [
                'name'          => 'Информация',
                'link'          => '/oss/info',
                'icon'          => 'fa fa-lg fa-fw fa-info-circle',
                'cabinet_id'    => 3,
                'children'      => [
//                    [
//                        'name'          => 'Документация',
//                        'link'          => '/oss/info/documents',
//                        'icon'          => 'fa fa-lg fa-fw fa-file-pdf-o',
//                        'cabinet_id'    => 3,
//                        'children'      => [],
//                    ],
                    [
                        'name'          => 'Контакты',
                        'link'          => '/oss/info/contacts',
                        'icon'          => 'fa fa-lg fa-fw fa-phone',
                        'cabinet_id'    => 3,
                        'children'      => [],
                    ],
                ],
            ],

            /*
             * Common
             */
            [
                'name'          => 'Мой профиль',
                'link'          => '/profile',
                'icon'          => 'fa fa-lg fa-fw fa-cog',
                'cabinet_id'    => null,
                'children'      => [],
            ],
        ];

        foreach ($menus as $menu) {
            $this->menuCreate($menu);
        }
    }

    public function menuCreate($menu, $parentId = null)
    {
        $item = Menu::create([
            'parent_id'     => $parentId,
            'cabinet_id'    => $menu['cabinet_id'],
            'name'          => $menu['name'],
            'link'          => $menu['link'],
            'icon'          => $menu['icon'],
        ]);
        if (count($menu['children']) > 0) {
            foreach ($menu['children'] as $child) {
                $this->menuCreate($child, $item->id);
            }
        }
    }
}
