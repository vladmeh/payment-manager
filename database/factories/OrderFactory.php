<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\Order;

/** @var Factory $factory */
$factory->define(Order::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'showOrderId' => date_timestamp_get(date_create()),
        'amount' => random_int(1, 1000),
        'state' => $faker->word,
        'details' => $faker->paragraph
    ];
});
