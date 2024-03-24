<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('admin-dashboard',[ API\DashboardController::class, 'adminDashboard' ]);
    Route::post('category-save', [ App\Http\Controllers\CategoryController::class, 'store' ] );
    Route::post('category-delete/{id}', [ App\Http\Controllers\CategoryController::class, 'destroy' ] );
    Route::post('category-action',[ App\Http\Controllers\CategoryController::class, 'action' ]);
    Route::get('get-category-list',[API\CategoryController::class,'getCategoryList']);

    Route::get('get-service-list',[API\ServiceController::class,'getServiceList']);
    Route::post('service-delete/{id}',[App\Http\Controllers\ServiceController::class,'destroy']);
    Route::post('service-action',[ App\Http\Controllers\ServiceController::class, 'action' ]);
    Route::post('get-service-detail', [ API\ServiceController::class, 'getServiceDetail' ] );
    
    Route::post('subcategory-save', [ App\Http\Controllers\SubCategoryController::class, 'store' ] );
    Route::post('subcategory-delete/{id}', [ App\Http\Controllers\SubCategoryController::class, 'destroy' ] );
    Route::post('subcategory-action',[ App\Http\Controllers\SubCategoryController::class, 'action' ]);
    Route::get('get-subcategory-list',[API\SubCategoryController::class,'getSubCategoryList']);

    Route::post('provider-save', [ App\Http\Controllers\ProviderController::class, 'store' ] );
    Route::post('provider-delete/{id}', [ App\Http\Controllers\ProviderController::class, 'destroy' ] );
    Route::post('provider-action',[ App\Http\Controllers\ProviderController::class, 'action' ]);

    Route::post('providertype-save', [ App\Http\Controllers\ProviderTypeController::class, 'store' ] );
    Route::post('providertype-delete/{id}', [ App\Http\Controllers\ProviderTypeController::class, 'destroy' ] );
    Route::post('providertype-action',[ App\Http\Controllers\ProviderTypeController::class, 'action' ]);

    Route::post('provideraddress-save', [ App\Http\Controllers\ProviderAddressMappingController::class, 'store' ] );
    Route::post('provideraddress-delete/{id}', [ App\Http\Controllers\ProviderAddressMappingController::class, 'destroy' ] );
    Route::post('provideraddress-action',[ App\Http\Controllers\ProviderAddressMappingController::class, 'action' ]);

    Route::post('providerdocument-save', [ App\Http\Controllers\ProviderDocumentController::class, 'store' ] );
    Route::post('providerdocument-delete/{id}', [ App\Http\Controllers\ProviderDocumentController::class, 'destroy' ] );
    Route::post('providerdocument-action',[ App\Http\Controllers\ProviderDocumentController::class, 'action' ]);

    Route::post('providerpayout-save', [ App\Http\Controllers\ProviderPayoutController::class, 'store' ] );
    
    Route::post('coupon-save', [ App\Http\Controllers\CouponController::class, 'store' ] );
    Route::post('coupon-delete/{id}', [ App\Http\Controllers\CouponController::class, 'destroy' ] );
    Route::post('coupon-action',[ App\Http\Controllers\CouponController::class, 'action' ]);
    Route::get('coupon-list',[API\CommanController::class,'getCouponList']);
    Route::get('get-coupon-service',[API\CommanController::class,'getCouponService']);


    Route::post('document-save', [ App\Http\Controllers\DocumentsController::class, 'store' ] );
    Route::post('document-delete/{id}', [ App\Http\Controllers\DocumentsController::class, 'destroy' ] );
    Route::post('document-action',[ App\Http\Controllers\DocumentsController::class, 'action' ]);

    Route::get('earning-list',[ App\Http\Controllers\EarningController::class, 'setEarningData' ]);
    Route::post('save-earning-setting', [ SettingController::class , 'saveEarningTypeSetting']);

    Route::get('get-type-list',[API\CommanController::class,'getTypeList']);

    Route::post('add-user',[API\User\UserController::class, 'addUser']);
    Route::post('edit-user',[API\User\UserController::class,'editUser']);
    Route::get('get-user-list',[API\User\UserController::class, 'userList']);

    Route::post('handymantype-save', [ App\Http\Controllers\HandymanTypeController::class, 'store' ] );
    Route::post('handymantype-delete/{id}', [ App\Http\Controllers\HandymanTypeController::class, 'destroy' ] );
    Route::post('handymantype-action',[ App\Http\Controllers\HandymanTypeController::class, 'action' ]);

    Route::post('user-delete/{id}', [ App\Http\Controllers\CustomerController::class, 'destroy' ] );
    Route::post('user-action',[ App\Http\Controllers\CustomerController::class, 'action' ]);

    Route::post('handyman-delete/{id}', [ App\Http\Controllers\CustomerController::class, 'destroy' ] );
    Route::post('handyman-action',[ App\Http\Controllers\CustomerController::class, 'action' ]);

    Route::post('provider-delete/{id}', [ App\Http\Controllers\ProviderController::class, 'destroy' ] );
    Route::post('provider-action',[ App\Http\Controllers\ProviderController::class, 'action' ]);

    Route::post('send-push-notification', [ App\Http\Controllers\SettingController::class , 'sendPushNotification']);
    Route::post('servicepackage-save',[  App\Http\Controllers\ServicePackageController::class, 'store' ]);
    Route::get('servicepackage-list',[API\ServicePackageController::class,'getServicePackageList']);
    Route::post('servicepackage-delete/{id}', [ App\Http\Controllers\ServicePackageController::class, 'destroy' ] );

    Route::post('tax-save', [ App\Http\Controllers\TaxController::class, 'store' ] );
    Route::post('tax-delete/{id}', [ App\Http\Controllers\TaxController::class, 'destroy' ] );
    Route::get('get-tax-list',[ API\CommanController::class, 'getTaxList' ]);

    Route::get('get-ratings-list', [ API\BookingController::class, 'getRatingsList' ] );
    Route::post('rating-delete/{id}', [ API\BookingController::class, 'deleteRatingsList' ] );

    Route::get('wallet-list',[API\WalletController::class,'getwalletlist']);
    Route::post('wallet-save',[API\WalletController::class,'store']);
    Route::get('get-handyman-payout-list', [ API\PayoutController::class, 'handymanPayoutList' ] );
    Route::post('handyman-payout-delete/{id}', [ App\Http\Controllers\HandymanPayoutController::class, 'destroy' ] );

    Route::get('get-post-job-list',[ API\PostJobRequestController::class, 'getPostRequestList' ]);
    Route::post('delete-get-post-job/{id}',[ App\Http\Controllers\PostJobRequestController::class, 'destroy' ]);

    Route::get('get-payment-list',[API\PaymentController::class, 'paymentList']);

    Route::post('wallet-delete/{id}',[App\Http\Controllers\WalletController::class,'destroy']);


    Route::get('get-slider-list',[ API\SliderController::class, 'getSliderList' ]);
    Route::post('slider-save',[ App\Http\Controllers\SliderController::class, 'store' ]);

    Route::get('get-handymanrating-list',[ API\BookingController::class, 'getHandymanRatingList' ]);
    Route::post('handymanrating-delete/{id}',[ App\Http\Controllers\HandymanRatingController::class, 'destroy' ]);

    Route::get('get-user-ratings-list', [ API\BookingController::class, 'getUserRatings' ] );
    Route::post('user-ratings-delete/{id}', [ App\Http\Controllers\BookingRatingController::class, 'destroy' ] );

    Route::get('get-cash-payment-history',[API\PaymentController::class, 'getCashPaymentHistory']);
    Route::post('cash-payment-history-delete/{id}',[App\Http\Controllers\PaymentController::class, 'destroy']);

});
