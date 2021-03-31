<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\PaymentOrderItem;

/** @var Factory $factory */
$factory->define(PaymentOrderItem::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence(4),
        'price' => $faker->numerify('###00'),
    ];
});
