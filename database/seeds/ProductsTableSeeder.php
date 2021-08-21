<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name'          => '«Я — Личность» (базовый)',
                'category'      => 'sib',
                'price'         => 50,
                'description'   => '',
                'photo'         => '',
                'is_active'     => true,
            ],
            [
                'name'          => '«Я — Личность» (стандарт)',
                'category'      => 'sib',
                'price'         => 69,
                'description'   => '',
                'photo'         => '',
                'is_active'     => true,
            ],
            [
                'name'          => '«Я — Личность» (премиум)',
                'category'      => 'sib',
                'price'         => 499,
                'description'   => '',
                'photo'         => '',
                'is_active'     => true,
            ],
            [
                'name'          => '«Я — Личность» (VIP)',
                'category'      => 'sib',
                'price'         => 1999,
                'description'   => '',
                'photo'         => '',
                'is_active'     => true,
            ],
            [
                'name'          => 'WakeUpERA',
                'category'      => 'oss',
                'price'         => 20,
                'description'   => '<p>⠀⠀<strong>#WakeUpERA</strong> &mdash; это утренние мотивационные встречи с невероятной энергетикой огромной Команды, тоннами полезной информации и зарядом позитива на весь день!!! Проходят они в формате онлайн-трансляций <em>(с возможностью просмотреть запись эфира в течение суток)</em> ежедневно по будням <strong>в 7-00 по времени Астаны</strong>.<br />
⠀⠀📢 <strong>Миссия проекта:</strong> РАЗБУДИТЬ МИР!</p>

<p><strong>Что #WakeUpERA даст мне?</strong><br />
⠀⠀🌅 Самое первое &mdash; это <strong>заряд бодрости и невероятной энергии для пробуждения</strong>! Если Ты испытываешь трудности с утренним подъёмом, то #WakeUpERA создан для Тебя!<br />
⠀⠀💭 Если Ты хочешь получать с утра <strong>правильный настрой на весь день</strong>, давая полезную пищу для своего ума, то этот Проект идеальное решение для этого!<br />
⠀⠀🚀 Если с раннего утра Ты хочешь попадать в <strong>окружение успешных, заряженных, позитивных, оптимистичных людей</strong> и перезаряжаться от них этой энергией для б&oacute;льших свершений в течение дня, то милости просим к Нам!<br />
⠀⠀⤴️ Если у Тебя есть желание что-то изменить в своей жизни, но Ты не знаешь, с чего начать и что делать, то здесь Ты найдёшь <strong>исчерпывающие ответы на все свои вопросы</strong>!<br />
⠀⠀🌐 Если Ты хочешь найти себе <strong>добрых друзей со всего мира</strong>, собранных вместе по принципу любви к саморазвитию, то наша площадка к Твоим услугам! С нами Ты не останешься одинок ни в одном уголке нашей необъятной Планеты!)<br />
⠀⠀💖 Если Ты давно ищешь <strong>свою вторую половинку</strong>, но не знаешь, где ещё её искать, то не ходи далеко &mdash; счастье может оказаться рядом!) Прямо тут!) У нас уже есть пары, которые нашли друг друга именно здесь!)<br />
⠀⠀📃 Список того, что Ты можешь получить в Проекте #WakeUpERA можно продолжать бесконечно. Он ограничен только тем, что Ты сам себе можешь позволить от него получить. Присоединяйся скорее!)</p>
',
                'photo'         => '/img/courses/wakeupera.png',
                'is_active'     => true,
            ],
            [
                'name'          => 'PROSekc',
                'category'      => 'oss',
                'price'         => 30,
                'description'   => 'Все про секс и не только..',
                'photo'         => '/img/courses/prosex.png',
                'is_active'     => false,
            ],
        ]);
    }
}