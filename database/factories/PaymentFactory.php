<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\Payment;

/** @var Factory $factory */
$factory->define(Payment::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(3, 8),
        'orderId' => $faker->uuid,
        'showOrderId' => date_timestamp_get(date_create()),
        'account' => $faker->phoneNumber,
        'state' => $faker->word,
        'stateDate' => $faker->unixTime,
        'system' => $faker->word,
    ];
});
