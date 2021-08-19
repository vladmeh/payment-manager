<?php

use Faker\Generator as Faker;
use Fh\PaymentManager\Entities\OrderItem;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(4),
        'price' => $faker->numerify('###00.00'),
    ];
});
