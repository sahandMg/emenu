<?php

use Faker\Generator as Faker;

$factory->define(\App\Food::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'category' => ['pizza', 'burger', 'sandwich', 'drink'][rand(0,3)],
        'price' => $faker->numberBetween(20000,40000)

    ];
});
