<?php

use Faker\Generator as Faker;
use Fh\PaymentManager\Models\PaymentOrder;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(PaymentOrder::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'amount' => random_int(1, 1000),
        'state' => PaymentStatus::UNDEF,
    ];
});
