<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_customers', function (Blueprint $table) {
            $table->id();
            $table->string('account')->unique()->comment('Уникальный идентификатор Плательщика.');
            $table->string('email')->nullable()->default('')->comment('Контактный e-mail Плательщика.');
            $table->string('phone')->nullable()->default('')->comment('Контактный телефон Плательщика в международном формате.');
            $table->string('comment')->nullable()->default('')->comment('Комментарий Плательщика.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
