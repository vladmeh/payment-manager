<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\Order;
use Vladmeh\PaymentManager\Order\OrderStatus;

/** @var Factory $factory */
$factory->define(Order::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'showOrderId' => date_timestamp_get(date_create()),
        'amount' => random_int(1, 1000),
        'state' => $faker->randomElements([
            OrderStatus::CREATE, OrderStatus::CANCELED, OrderStatus::SENT,
            OrderStatus::PROCESSING, OrderStatus::TREATED, OrderStatus::NOT_FOUND,
            OrderStatus::ERROR, OrderStatus::CLOSE
        ]),
        'details' => $faker->paragraph
    ];
});
