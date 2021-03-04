<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->uuid('orderId')->unique()->comment('Уникальный идентификатор заказа.');
            $table->string('showOrderId')->comment('Идентификатор заказа, отображаемый Плательщику.');
            $table->string('account')->comment('Идентификатор плательщика');

            $table->string('paymentId')->comment('Уникальный идентификатор платежа в рамках Платежной системы.');
            $table->string('state')->comment('Статус платежа.');
            $table->timestamp('stateDate')->comment('Дата присвоения статуса платежа');

            $table->string('system')->comment('Идетификатор платежной системы');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
