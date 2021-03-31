<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;
use Vladmeh\PaymentManager\Models\PaymentCustomer;

/** @var Factory $factory */
$factory->define(PaymentCustomer::class, function (Faker $faker) {
    $phone = $faker->e164PhoneNumber;

    return [
        'account' => Str::substr($phone, 2),
        'email' => $faker->email,
        'phone' => $phone,
        'comment' => $faker->sentence,
    ];
});
