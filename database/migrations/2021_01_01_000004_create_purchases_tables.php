<?php

use Fh\PaymentManager\Pscb\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->comment('Уникальный идентификатор заказа');
            $table->integer('total')->default(0)->comment('Количество позиций в заказе.');
            $table->decimal('amount')->default(0.00)->comment('Сумма заказа в рублях');
            $table->string('status')->nullable()->default(PaymentStatus::UNDEF)->comment('Статус оплаты заказа');

            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Наименование позиции.');
            $table->text('details')->nullable()->comment('Детали позиции в Json формате');
            $table->decimal('price')->default(0.00)->comment('Цена позиции (с учётом НДС).');
            $table->integer('quantity')->default(1)->comment('Количество позиций.');

            $table->uuid('order_id')->nullable();
            $table->foreign('order_id')
                ->references('uuid')
                ->on('purchase_orders')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
}
