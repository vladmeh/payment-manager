<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_order_items', function (Blueprint $table) {
            $table->id();

            $table->string('text')->comment('Наименование позиции.');
            $table->decimal('price')->comment('Цена позиции (с учётом НДС).');
            $table->integer('quantity')->default(1)->comment('Количество позиций.');

            $table->uuid('order_id')->nullable();
            $table->foreign('order_id')
                ->references('uuid')
                ->on('payment_orders')
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
        Schema::dropIfExists('payment_order_items');
    }
}
