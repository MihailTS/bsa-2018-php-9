<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Currency::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'short_name' => $faker->currencyCode,
        'logo_url' => $faker->imageUrl(),
        'price' => $faker->randomFloat(2, 0, 100000),
    ];
});
