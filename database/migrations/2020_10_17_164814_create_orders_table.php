<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('orderId')->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->string('orderNumber')->nullable();
            $table->datetime('createdAt')->nullable();
            $table->datetime('updatedAt')->nullable();
            $table->string('status')->nullable();
            $table->double('priceIncl')->nullable();
            $table->string('email')->nullable();
            $table->datetime('deliveryDate')->nullable();
            $table->datetime('pickupDate')->nullable();
            $table->tinyInteger('isSaved')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('orders');
    }
}
