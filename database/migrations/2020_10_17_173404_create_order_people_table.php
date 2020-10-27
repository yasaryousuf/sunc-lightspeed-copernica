<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_people', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customerId');
            $table->string('nationalId')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('remoteIp')->nullable();
            $table->date('birthDate')->nullable();
            $table->tinyInteger('isCompany')->nullable();
            $table->string('companyName')->nullable();
            $table->string('companyCoCNumber')->nullable();
            $table->string('companyVatNumber')->nullable();
            $table->string('addressBillingName')->nullable();
            $table->string('addressBillingStreet')->nullable();
            $table->string('addressBillingStreet2')->nullable();
            $table->string('addressBillingNumber')->nullable();
            $table->string('addressBillingExtension')->nullable();
            $table->string('addressBillingZipcode')->nullable();
            $table->string('addressBillingCity')->nullable();
            $table->string('addressBillingRegion')->nullable();
            $table->string('addressBillingCountryCode')->nullable();
            $table->string('addressBillingCountryTitle')->nullable();
            $table->string('addressShippingName')->nullable();
            $table->string('addressShippingStreet')->nullable();
            $table->string('addressShippingStreet2')->nullable();
            $table->string('addressShippingNumber')->nullable();
            $table->string('addressShippingExtension')->nullable();
            $table->string('addressShippingZipcode')->nullable();
            $table->string('addressShippingCity')->nullable();
            $table->string('addressShippingRegion')->nullable();
            $table->string('addressShippingCountryCode')->nullable();
            $table->string('addressShippingCountryTitle')->nullable();
            $table->string('languageCode')->nullable();
            $table->string('languageTitle')->nullable();
            $table->tinyInteger('isConfirmedCustomer')->nullable();
            $table->datetime('customerCreatedAt')->nullable();
            $table->datetime('customerUpdatedAt')->nullable();
            $table->datetime('lastOnlineAt')->nullable();
            $table->string('languageLocale')->nullable();
            $table->string('customerType')->nullable();
            $table->tinyInteger('optInNewsletter')->default(false);
            $table->string('nieuwsbrief')->default('Nee');
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
        Schema::dropIfExists('order_people');
    }
}
