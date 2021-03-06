<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            ['code' => '', 'name' => 'Россия'],
            ['code' => '', 'name' => 'Казахстан'],
            ['code' => '', 'name' => 'Армения'],
            ['code' => '', 'name' => 'Беларусь'],
            ['code' => '', 'name' => 'Грузия'],
            ['code' => '', 'name' => 'Азербайджан'],
            ['code' => '', 'name' => 'Киргизия'],
            ['code' => '', 'name' => 'Латвия'],
            ['code' => '', 'name' => 'Литва'],
            ['code' => '', 'name' => 'Молдавия'],
            ['code' => '', 'name' => 'Таджикистан'],
            ['code' => '', 'name' => 'Туркменистан'],
            ['code' => '', 'name' => 'Узбекистан'],
            ['code' => '', 'name' => 'Украина'],
            ['code' => '', 'name' => 'Эстония'],
            ['code' => '', 'name' => 'Австралия'],
            ['code' => '', 'name' => 'Австрия'],
            ['code' => '', 'name' => 'Албания'],
            ['code' => '', 'name' => 'Алжир'],
            ['code' => '', 'name' => 'Ангола'],
            ['code' => '', 'name' => 'Арабские Эмираты'],
            ['code' => '', 'name' => 'Аргентина'],
            ['code' => '', 'name' => 'Аруба'],
            ['code' => '', 'name' => 'Афганистан'],
            ['code' => '', 'name' => 'Багамские острова'],
            ['code' => '', 'name' => 'Бангладеш'],
            ['code' => '', 'name' => 'Барбадос'],
            ['code' => '', 'name' => 'Бельгия'],
            ['code' => '', 'name' => 'Бенин'],
            ['code' => '', 'name' => 'Бермудские острова'],
            ['code' => '', 'name' => 'Болгария'],
            ['code' => '', 'name' => 'Боливия'],
            ['code' => '', 'name' => 'Босния и Герцеговина'],
            ['code' => '', 'name' => 'Бразилия'],
            ['code' => '', 'name' => 'Бруней'],
            ['code' => '', 'name' => 'Великобритания'],
            ['code' => '', 'name' => 'Венгрия'],
            ['code' => '', 'name' => 'Венесуэлла'],
            ['code' => '', 'name' => 'Вьетнам'],
            ['code' => '', 'name' => 'Гаити'],
            ['code' => '', 'name' => 'Гамбия'],
            ['code' => '', 'name' => 'Гондурас'],
            ['code' => '', 'name' => 'Гваделупа'],
            ['code' => '', 'name' => 'Гватемала'],
            ['code' => '', 'name' => 'Гвинея'],
            ['code' => '', 'name' => 'Германия'],
            ['code' => '', 'name' => 'Гибралтар'],
            ['code' => '', 'name' => 'Нидерланды'],
            ['code' => '', 'name' => 'Гонконг'],
            ['code' => '', 'name' => 'Гренада'],
            ['code' => '', 'name' => 'Гренландия'],
            ['code' => '', 'name' => 'Греция'],
            ['code' => '', 'name' => 'Гуана'],
            ['code' => '', 'name' => 'Дания'],
            ['code' => '', 'name' => 'Доминиканская Республика'],
            ['code' => '', 'name' => 'Египет'],
            ['code' => '', 'name' => 'Демократическая республика Конго'],
            ['code' => '', 'name' => 'Замбия'],
            ['code' => '', 'name' => 'Зимбабве'],
            ['code' => '', 'name' => 'Израиль'],
            ['code' => '', 'name' => 'Индия'],
            ['code' => '', 'name' => 'Индонезия'],
            ['code' => '', 'name' => 'Иордания'],
            ['code' => '', 'name' => 'Ирак'],
            ['code' => '', 'name' => 'Иран'],
            ['code' => '', 'name' => 'Ирландия'],
            ['code' => '', 'name' => 'Исландия'],
            ['code' => '', 'name' => 'Испания'],
            ['code' => '', 'name' => 'Италия'],
            ['code' => '', 'name' => 'Йемен'],
            ['code' => '', 'name' => 'Каймановы острова'],
            ['code' => '', 'name' => 'Камерун'],
            ['code' => '', 'name' => 'Канада'],
            ['code' => '', 'name' => 'Кения'],
            ['code' => '', 'name' => 'Кипр'],
            ['code' => '', 'name' => 'Китай'],
            ['code' => '', 'name' => 'Колумбия'],
            ['code' => '', 'name' => 'Камбоджа'],
            ['code' => '', 'name' => 'Конго'],
            ['code' => '', 'name' => 'Корея (Южная)'],
            ['code' => '', 'name' => 'Коста Рика'],
            ['code' => '', 'name' => 'Куба'],
            ['code' => '', 'name' => 'Кувейт'],
            ['code' => '', 'name' => 'Либерия'],
            ['code' => '', 'name' => 'Лихтенштейн'],
            ['code' => '', 'name' => 'Люксембург'],
            ['code' => '', 'name' => 'Мавритания'],
            ['code' => '', 'name' => 'Мадагаскар'],
            ['code' => '', 'name' => 'Македония'],
            ['code' => '', 'name' => 'Малайзия'],
            ['code' => '', 'name' => 'Мали'],
            ['code' => '', 'name' => 'Мальта'],
            ['code' => '', 'name' => 'Мексика'],
            ['code' => '', 'name' => 'Мозамбик'],
            ['code' => '', 'name' => 'Монако'],
            ['code' => '', 'name' => 'Монголия'],
            ['code' => '', 'name' => 'Морокко'],
            ['code' => '', 'name' => 'Намибия'],
            ['code' => '', 'name' => 'Непал'],
            ['code' => '', 'name' => 'Нигерия'],
            ['code' => '', 'name' => 'Никарагуа'],
            ['code' => '', 'name' => 'Новая Зеландия'],
            ['code' => '', 'name' => 'Норвегия'],
            ['code' => '', 'name' => 'Пакистан'],
            ['code' => '', 'name' => 'Панама'],
            ['code' => '', 'name' => 'Папуа Новая Гвинея'],
            ['code' => '', 'name' => 'Парагвай'],
            ['code' => '', 'name' => 'Перу'],
            ['code' => '', 'name' => 'Польша'],
            ['code' => '', 'name' => 'Португалия'],
            ['code' => '', 'name' => 'Пуэрто Рико'],
            ['code' => '', 'name' => 'Румыния'],
            ['code' => '', 'name' => 'Саудовская Аравия'],
            ['code' => '', 'name' => 'Сенегал'],
            ['code' => '', 'name' => 'Сингапур'],
            ['code' => '', 'name' => 'Сирия'],
            ['code' => '', 'name' => 'Словакия'],
            ['code' => '', 'name' => 'Словения'],
            ['code' => '', 'name' => 'Сомали'],
            ['code' => '', 'name' => 'Судан'],
            ['code' => '', 'name' => 'США'],
            ['code' => '', 'name' => 'Тайвань'],
            ['code' => '', 'name' => 'Таиланд'],
            ['code' => '', 'name' => 'Тринидад и Тобаго'],
            ['code' => '', 'name' => 'Тунис'],
            ['code' => '', 'name' => 'Турция'],
            ['code' => '', 'name' => 'Уганда'],
            ['code' => '', 'name' => 'Уругвай'],
            ['code' => '', 'name' => 'Филиппины'],
            ['code' => '', 'name' => 'Финляндия'],
            ['code' => '', 'name' => 'Франция'],
            ['code' => '', 'name' => 'Чад'],
            ['code' => '', 'name' => 'Чехия'],
            ['code' => '', 'name' => 'Чили'],
            ['code' => '', 'name' => 'Швейцария'],
            ['code' => '', 'name' => 'Швеция'],
            ['code' => '', 'name' => 'Шри-Ланка'],
            ['code' => '', 'name' => 'Эквадор'],
            ['code' => '', 'name' => 'Эфиопия'],
            ['code' => '', 'name' => 'ЮАР'],
            ['code' => '', 'name' => 'Сербия'],
            ['code' => '', 'name' => 'Ямайка'],
            ['code' => '', 'name' => 'Япония'],
            ['code' => '', 'name' => 'Бахрейн'],
            ['code' => '', 'name' => 'Андорра'],
            ['code' => '', 'name' => 'Белиз'],
            ['code' => '', 'name' => 'Бутан'],
            ['code' => '', 'name' => 'Ботсвана'],
            ['code' => '', 'name' => 'Буркина Фасо'],
            ['code' => '', 'name' => 'Бурунди'],
            ['code' => '', 'name' => 'Центральноафриканская Республика'],
            ['code' => '', 'name' => 'Коморос'],
            ['code' => '', 'name' => 'Кот-Д`ивуар'],
            ['code' => '', 'name' => 'Джибути'],
            ['code' => '', 'name' => 'Восточный Тимор'],
            ['code' => '', 'name' => 'Эль Сальвадор'],
            ['code' => '', 'name' => 'Экваториальная Гвинея'],
            ['code' => '', 'name' => 'Эритрея'],
            ['code' => '', 'name' => 'Фижи'],
            ['code' => '', 'name' => 'Габон'],
            ['code' => '', 'name' => 'Гана'],
            ['code' => '', 'name' => 'Гвинея-Биссау'],
            ['code' => '', 'name' => 'Корея (Северная)'],
            ['code' => '', 'name' => 'Ливан'],
            ['code' => '', 'name' => 'Лесото'],
            ['code' => '', 'name' => 'Ливия'],
            ['code' => '', 'name' => 'Мальдивы'],
            ['code' => '', 'name' => 'Маршалские острова'],
            ['code' => '', 'name' => 'Нигер'],
            ['code' => '', 'name' => 'Оман'],
            ['code' => '', 'name' => 'Катар'],
            ['code' => '', 'name' => 'Руанда'],
            ['code' => '', 'name' => 'Самоа'],
            ['code' => '', 'name' => 'Сейшеллы'],
            ['code' => '', 'name' => 'Сьерра-Леоне'],
            ['code' => '', 'name' => 'Суринам'],
            ['code' => '', 'name' => 'Свазиленд'],
            ['code' => '', 'name' => 'Танзания'],
            ['code' => '', 'name' => 'Западная Сахара'],
            ['code' => '', 'name' => 'Хорватия'],
            ['code' => '', 'name' => 'Ангилья'],
            ['code' => '', 'name' => 'Антарктида'],
            ['code' => '', 'name' => 'Антигуа и Барбуда'],
            ['code' => '', 'name' => 'Остров Буве'],
            ['code' => '', 'name' => 'Британские территории в Индийском Океане'],
            ['code' => '', 'name' => 'Британские Виргинские острова'],
            ['code' => '', 'name' => 'Мьянма'],
            ['code' => '', 'name' => 'Кабо-Верде'],
            ['code' => '', 'name' => 'Остров Рождества'],
            ['code' => '', 'name' => 'Кокосовые острова'],
            ['code' => '', 'name' => 'Острова Кука'],
            ['code' => '', 'name' => 'Доминика'],
            ['code' => '', 'name' => 'Фолклендские острова'],
            ['code' => '', 'name' => 'Фарерские острова'],
            ['code' => '', 'name' => 'Гвиана'],
            ['code' => '', 'name' => 'Французская Полинезия'],
            ['code' => '', 'name' => 'Южные Французские территории'],
            ['code' => '', 'name' => 'Острова Херд и Макдоналд'],
            ['code' => '', 'name' => 'Кирибати'],
            ['code' => '', 'name' => 'Лаос'],
            ['code' => '', 'name' => 'Макао'],
            ['code' => '', 'name' => 'Малави'],
            ['code' => '', 'name' => 'Мартиника'],
            ['code' => '', 'name' => 'Маврикий'],
            ['code' => '', 'name' => 'Майотта'],
            ['code' => '', 'name' => 'Микронезия'],
            ['code' => '', 'name' => 'Монтсеррат'],
            ['code' => '', 'name' => 'Науру'],
            ['code' => '', 'name' => 'Антильские острова'],
            ['code' => '', 'name' => 'Новая Каледония'],
            ['code' => '', 'name' => 'Ниуэ'],
            ['code' => '', 'name' => 'Остров Норфолк'],
            ['code' => '', 'name' => 'Палау'],
            ['code' => '', 'name' => 'Палестина'],
            ['code' => '', 'name' => 'Остров Питкэрн'],
            ['code' => '', 'name' => 'Реюньон'],
            ['code' => '', 'name' => 'Остров Св.Елены'],
            ['code' => '', 'name' => 'Острова Сент-Киттс и Невис'],
            ['code' => '', 'name' => 'Санта-Лючия'],
            ['code' => '', 'name' => 'Острова Сен-Пьер и Микелон'],
            ['code' => '', 'name' => 'Сент-Винсент и Гренадины'],
            ['code' => '', 'name' => 'Сан-Марино'],
            ['code' => '', 'name' => 'Соломоновы острова'],
            ['code' => '', 'name' => 'Южная Георгия и Южные Сандвичевы острова'],
            ['code' => '', 'name' => 'Острова Шпицберген и Ян-Майен'],
            ['code' => '', 'name' => 'Того'],
            ['code' => '', 'name' => 'Токелау'],
            ['code' => '', 'name' => 'Тонга'],
            ['code' => '', 'name' => 'Острова Тёркс и Кайкос'],
            ['code' => '', 'name' => 'Тувалу'],
            ['code' => '', 'name' => 'Американские Виргинские острова'],
            ['code' => '', 'name' => 'Вануату'],
            ['code' => '', 'name' => 'Ватикан'],
            ['code' => '', 'name' => 'Острова Уоллис и Футуна'],
            ['code' => '', 'name' => 'Черногория'],
        ];

        DB::table('countries')->insert($list);
    }
}
