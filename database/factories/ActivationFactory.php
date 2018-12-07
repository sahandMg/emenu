<?php

use Faker\Generator as Faker;

$factory->define(App\Activation::class, function (Faker $faker) {
    return [
        'code'=>str_random(10),
        'trial' => 0,
        'expired'=>0,
        'original'=>$faker->biasedNumberBetween($min = 0, $max = 1, $function = 'sqrt')
    ];
});
