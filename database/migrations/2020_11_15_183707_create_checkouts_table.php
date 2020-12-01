<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('checkoutId');
            $table->string('nationalId')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->date('birthDate')->nullable();
            $table->string('company')->nullable();
            $table->string('coCNumber')->nullable();
            $table->string('vatNumber')->nullable();
            $table->string('addressBillingName')->nullable();
            $table->string('addressBillingStreet')->nullable();
            $table->string('addressBillingStreet2')->nullable();
            $table->string('addressBillingNumber')->nullable();
            $table->string('addressBillingExtension')->nullable();
            $table->string('addressBillingZipcode')->nullable();
            $table->string('addressBillingCity')->nullable();
            $table->string('addressBillingRegion')->nullable();
            $table->string('addressBillingCountryCode')->nullable();
            $table->string('addressShippingName')->nullable();
            $table->string('addressShippingStreet')->nullable();
            $table->string('addressShippingStreet2')->nullable();
            $table->string('addressShippingNumber')->nullable();
            $table->string('addressShippingExtension')->nullable();
            $table->string('addressShippingZipcode')->nullable();
            $table->string('addressShippingCity')->nullable();
            $table->string('addressShippingRegion')->nullable();
            $table->string('addressShippingCountryCode')->nullable();
            $table->datetime('createdAt')->nullable();
            $table->datetime('updatedAt')->nullable();
            $table->string('customerType')->nullable();
            $table->tinyInteger('optInNewsletter')->default(false);
            $table->string('nieuwsbrief')->default('Nee');
            $table->tinyInteger('isSaved')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('checkouts');
    }
}
