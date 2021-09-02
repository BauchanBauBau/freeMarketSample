<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 2),
        'name' => $faker->realText(10),
        'category_id' => $faker->numberBetween(2, 4),
        'description' => $faker->realText(20),
        'condition' => $faker->numberBetween(1, 2),
        'price' => $faker->numberBetween(300, 100000),
        'shippingOption' => 0,
        'shippingMethod' => $faker->numberBetween(0, 3),
        'days' => $faker->numberBetween(0, 4),
        'buyer_id' => 0,
        'created_at' => $faker->datetime($max = 'now', $timezone = date_default_timezone_get()),
        'updated_at' => $faker->datetime($max = 'now', $timezone = date_default_timezone_get())
    ];
});
