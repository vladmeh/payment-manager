<?php

use Faker\Generator as Faker;
use Fh\PaymentManager\Entities\Order;
use Fh\PaymentManager\Pscb\PaymentStatus;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Order::class, function (Faker $faker) {
    return [
        'total' => 0,
        'amount' => 0.00,
        'status' => PaymentStatus::NEW,
    ];
});
