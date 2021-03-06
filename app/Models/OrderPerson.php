<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'person_id',
        'nationalId',
        'email',
        'gender',
        'firstName',
        'lastName',
        'phone',
        'mobile',
        'remoteIp',
        'birthDate',
        'isCompany',
        'companyName',
        'companyCoCNumber',
        'companyVatNumber',
        'addressBillingName',
        'addressBillingStreet',
        'addressBillingStreet2',
        'addressBillingNumber',
        'addressBillingExtension',
        'addressBillingZipcode',
        'addressBillingCity',
        'addressBillingRegion',
        'addressBillingCountryCode',
        'addressBillingCountryTitle',
        'addressShippingName',
        'addressShippingStreet',
        'addressShippingStreet2',
        'addressShippingNumber',
        'addressShippingExtension',
        'addressShippingZipcode',
        'addressShippingCity',
        'addressShippingRegion',
        'addressShippingCountryCode',
        'addressShippingCountryTitle',
        'languageCode',
        'languageTitle',
        'isConfirmedCustomer',
        'customerCreatedAt',
        'customerUpdatedAt',
        'lastOnlineAt',
        'languageLocale',
        'customerType',
    ];
}
