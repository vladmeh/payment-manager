<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\OrderItem;

/** @var Factory $factory */
$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence(4),
        'price' => $faker->numerify('###00'),
        'quantity' => $faker->numberBetween(1, 3),
    ];
});
