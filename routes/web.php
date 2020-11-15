<?php

use App\Http\Controllers\CopernicaAuthController;
use App\Http\Controllers\CopernicaController;
use App\Http\Controllers\LightspeedDiscountController;
use App\Http\Controllers\LightspeedOrderController;
use App\Http\Controllers\LightspeedSubscriberController;
use App\Http\Controllers\LightspeedCheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LightspeedAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LightspeedController;


    Route::get('success-url', [AuthController::class, 'success']);
    Route::get('cancel-url', [AuthController::class, 'success']);
    Route::get('uninstall-url', [AuthController::class, 'success']);
    Route::get('app-login-url', [AuthController::class, 'success']);
    Route::get('dpa-link', [AuthController::class, 'success']);

    Route::post('lightspeed-auth-api/settings', [LightspeedAuthController::class, 'updateApi']);
    Route::post('copernica-auth-api/settings', [CopernicaAuthController::class, 'updateApi']);
    Route::get('copernica/database/create/user-api', [CopernicaController::class, 'userDatabaseCreateApi']);
    Route::get('copernica/database/create/checkout-api', [CopernicaController::class, 'checkoutDatabaseCreateApi']);
    Route::get('copernica/database/fields/create/user-api', [CopernicaController::class, 'userDatabaseFieldsCreateApi']);
    Route::get('copernica/collection/create/order-api', [CopernicaController::class, 'orderCollectionCreateApi']);
    Route::get('copernica/collection/create/orderrow-api', [CopernicaController::class, 'orderRowCollectionCreateApi']);

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::get('/', [AuthController::class, 'dashboard']);


    Route::get('admin/manage/users', [UserController::class, 'index']);
    Route::get('admin/manage/lightspeed', [LightspeedController::class, 'index']);
    Route::get('admin/manage/copernica', [CopernicaController::class, 'index']);

    Route::get('copernica', [AuthController::class, 'copernica']);
    Route::get('lightspeed', [AuthController::class, 'copernica']);
    Route::get('wizard', [UserController::class, 'wizard']);
    Route::post('wizard', [UserController::class, 'postWizard']);

    Route::get('lightspeed/subscribers', [LightspeedSubscriberController::class, 'show']);
    Route::get('lightspeed/import', [LightspeedSubscriberController::class, 'importapi']);
    Route::get('lightspeed/subscribers/import', [LightspeedSubscriberController::class, 'import']);

    Route::get('lightspeed-auth/settings', [LightspeedAuthController::class, 'edit']);
    Route::post('lightspeed-auth/settings', [LightspeedAuthController::class, 'update']);

    Route::get('lightspeed/discounts', [LightspeedDiscountController::class, 'show']);
    Route::get('lightspeed/discounts/import', [LightspeedDiscountController::class, 'import']);

    Route::get('lightspeed/orders', [LightspeedOrderController::class, 'show']);
    Route::get('lightspeed/orders/import', [LightspeedOrderController::class, 'import']);

    Route::get('lightspeed/checkouts', [LightspeedCheckoutController::class, 'show']);
    Route::get('lightspeed/checkout/import', [LightspeedCheckoutController::class, 'import']);


    Route::get('copernica-auth/settings', [CopernicaAuthController::class, 'edit']);
    Route::post('copernica-auth/settings', [CopernicaAuthController::class, 'update']);
    Route::get('copernica/setup', [CopernicaController::class, 'setup']);
    Route::get('copernica/sync', [CopernicaController::class, 'sync']);
    Route::get('copernica', [CopernicaController::class, 'identify']);

    // Route::get('copernica/database/all', [CopernicaController::class, 'getAllDatabases']);
    // Route::get('copernica/database/create', [CopernicaController::class, 'databaseCreate']);
    Route::get('copernica/database/create/subscriber', [CopernicaController::class, 'subscriberDatabaseCreate']);
    Route::get('copernica/database/create/order', [CopernicaController::class, 'orderDatabaseCreate']);
    Route::get('copernica/collection/create/person', [CopernicaController::class, 'personCollectionCreate']);
    Route::get('copernica/collection/create/product', [CopernicaController::class, 'productCollectionCreate']);

    // Route::get('copernica/database/fields/create', [CopernicaController::class, 'databaseFieldsCreate']);
    Route::get('copernica/database/fields/create/subscriber', [CopernicaController::class, 'subscriberDatabaseFieldsCreate']);
    Route::get('copernica/database/fields/create/order', [CopernicaController::class, 'orderDatabaseFieldsCreate']);
    Route::get('copernica/collection/fields/create/person', [CopernicaController::class, 'personCollectionFieldsCreate']);
    Route::get('copernica/collection/fields/create/product', [CopernicaController::class, 'productCollectionFieldsCreate']);

    Route::get('copernica/profile/create', [CopernicaController::class, 'profileCreate']);
    Route::get('copernica/profile/create/subscriber', [CopernicaController::class, 'profileCreateFromSubscriber']);
    Route::get('copernica/profile/create/discount', [CopernicaController::class, 'profileCreateFromDiscount']);
    Route::get('copernica/profile/create/order', [CopernicaController::class, 'profileCreateFromOrder']);
});

Route::get('login', [AuthController::class, 'index'])->name('login');;
Route::post('login', [AuthController::class, 'postLogin']); 
Route::get('registration', [AuthController::class, 'registration']);
Route::post('registration', [AuthController::class, 'postRegistration']);
Route::get('logout', [AuthController::class, 'logout']);
