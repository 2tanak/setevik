<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    $email = $faker->unique()->safeEmail;

    return [
        'name'              => $faker->name,
        'last_name'         => $faker->lastName,
        'login'             => $email,
        'email'             => $email,
        'password'          => $password ?: $password = bcrypt(env('TEST_PASSWORD', '123456')),
        'remember_token'    => str_random(10),
        'birthday'          => $faker->dateTime,
        'phone'             => $faker->phoneNumber,
        'country_id'        => 1,
        'city'              => $faker->city,
        'photo'             => '/img/avatars/no-photo.png',
    ];
});
