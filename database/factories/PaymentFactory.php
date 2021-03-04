<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Vladmeh\PaymentManager\Models\Payment;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;

/** @var Factory $factory */
$factory->define(Payment::class, function (Faker $faker) {
    return [
        'paymentId' => $faker->unique()->numberBetween(3, 8),
        'orderId' => $faker->uuid,
        'showOrderId' => date_timestamp_get(date_create()),
        'account' => $faker->phoneNumber,
        'state' => $faker->randomElement([
            PaymentStatus::NEW, PaymentStatus::SENT, PaymentStatus::END,
            PaymentStatus::REF, PaymentStatus::EXP, PaymentStatus::HOLD,
            PaymentStatus::CANCELED, PaymentStatus::ERR, PaymentStatus::REJ,
            PaymentStatus::UNDEF,
        ]),
        'stateDate' => $faker->unixTime,
        'system' => $faker->word,
    ];
});
