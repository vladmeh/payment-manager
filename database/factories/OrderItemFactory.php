<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\OrderItem;

/** @var Factory $factory */
$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'quantity' => $faker->numberBetween(1, 3),
        'price' => $faker->numerify('###00'),

        'name' => $faker->sentence(4),
    ];
});
