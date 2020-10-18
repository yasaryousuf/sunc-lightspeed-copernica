<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('lightspeed_id')->nullable();
            $table->datetime('createdAt')->nullable();
            $table->datetime('updatedAt')->nullable();
            $table->tinyInteger('isActive')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->string('type')->nullable();
            $table->string('discount')->nullable();
            $table->string('applyTo')->nullable();
            $table->string('shipment')->nullable();
            $table->string('usageLimit')->nullable();
            $table->string('timesUsed')->nullable();
            $table->string('minimumAmount')->nullable();
            $table->tinyInteger('before_tax')->nullable();
            $table->tinyInteger('minimum_after')->nullable();
            $table->string('code')->nullable();
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
        Schema::dropIfExists('discounts');
    }
}
