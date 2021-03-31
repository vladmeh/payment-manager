<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\PaymentOrder;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;

/** @var Factory $factory */
$factory->define(PaymentOrder::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'amount' => random_int(1, 1000),
        'state' => PaymentStatus::UNDEF,
    ];
});
