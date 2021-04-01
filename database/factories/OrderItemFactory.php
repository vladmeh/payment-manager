<?php

use Faker\Generator as Faker;
use Fh\PaymentManager\Models\PaymentOrderItem;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(PaymentOrderItem::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence(4),
        'price' => $faker->numerify('###00'),
    ];
});
