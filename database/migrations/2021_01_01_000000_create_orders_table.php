<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vladmeh\PaymentManager\Pscb\PaymentStatus;

class CreateOrdersTable extends Migration
{
    /**
     * Таблица Заказов.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->comment('Уникальный идентификатор заказа');
            $table->decimal('amount')->comment('Сумма заказа в рублях');
            $table->timestamp('created_at')->comment('Дата создания заказа');
            $table->string('state')->nullable()->default(PaymentStatus::UNDEF)->comment('Статус оплаты заказа');

            $table->text('details')->nullable()->default('')->comment('Детали заказа');
            $table->timestamp('updated_at')->nullable()->comment('Дата обновления записи заказа');

            $table->json('payment')->nullable();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
