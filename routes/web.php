<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProviderTypeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\HandymanController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProviderAddressMappingController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\ProviderDocumentController;
use App\Http\Controllers\RatingReviewController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\ProviderPayoutController;
use App\Http\Controllers\HandymanPayoutController;
use App\Http\Controllers\HandymanTypeController;
use App\Http\Controllers\ServiceFaqController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PostJobRequestController;
use App\Http\Controllers\ServicePackageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingRatingController;
use App\Http\Controllers\HandymanRatingController;
use App\Http\Controllers\UserServiceListController;
use App\Http\Controllers\ProviderSlotController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



require __DIR__.'/auth.php';
Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::group(['prefix' => 'auth'], function() {
    Route::get('login', [HomeController::class, 'authLogin'])->name('auth.login');
    Route::get('register', [HomeController::class, 'authRegister'])->name('auth.register');
    Route::get('recover-password', [HomeController::class, 'authRecoverPassword'])->name('auth.recover-password');
    Route::get('confirm-email', [HomeController::class, 'authConfirmEmail'])->name('auth.confirm-email');
    Route::get('lock-screen', [HomeController::class, 'authlockScreen'])->name('auth.lock-screen');
});

Route::get('lang/{locale}', [HomeController::class,'lang'])->name('switch-language');

Route::group(['middleware' => ['auth', 'verified']], function()
{
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::group(['namespace' => '', 'middleware' => ['permission:permission list']], function () {
        Route::resource('permission',PermissionController::class);
        Route::get('permission/add/{type}',[PermissionController::class,'addPermission'])->name('permission.add');
        Route::post('permission/save',[PermissionController::class,'savePermission'])->name('permission.save');

    });

    Route::group(['middleware' => ['permission:role list']], function () {
        Route::resource('role', RoleController::class);
        Route::get('role-index-data',[RoleController::class,'index_data'])->name('role.index_data');
        Route::post('role-bulk-action', [RoleController::class, 'bulk_action'])->name('role.bulk-action');
    });



    Route::get('changeStatus', [ HomeController::class, 'changeStatus'])->name('changeStatus');

    Route::group(['middleware' => ['permission:category list']], function () {
        Route::resource('category', CategoryController::class);
        Route::get('index_data',[CategoryController::class,'index_data'])->name('category.index_data');
        Route::post('category-bulk-action', [CategoryController::class, 'bulk_action'])->name('category.bulk-action');
        Route::post('category-action',[CategoryController::class, 'action'])->name('category.action');
        Route::post('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        Route::post('check-in-trash', [CategoryController::class, 'check_in_trash'])->name('check-in-trash');
        
    });
     
    Route::group(['middleware' => ['permission:service list']], function () {
        Route::resource('service', ServiceController::class);
        Route::get('service-index-data',[ServiceController::class,'index_data'])->name('service.service-index-data');
        Route::post('service-bulk-action', [ServiceController::class, 'bulk_action'])->name('service.bulk-action');
        Route::get('user-service-list',[ServiceController::class,'getUserServiceList'])->name('service.user-service-list');
        Route::post('service-action',[ServiceController::class, 'action'])->name('service.action');
        Route::post('service/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
        Route::get('user-service-index-data',[UserServiceListController::class,'index_data'])->name('service.user-index-data');
        
    });
    Route::get('provider-change-password', [ ProviderController::class , 'getChangePassword'])->name('provider.getchangepassword');
    Route::post('provider-change-password', [ ProviderController::class , 'changePassword'])->name('provider.changepassword');
    Route::get('provider-time-slot',[ProviderController::class,'getProviderTimeSlot'])->name('provider.time-slot');
    Route::get('provider-edit-time-slot',[ProviderController::class,'editProviderTimeSlot'])->name('provider.edit-time-slot');
    Route::post('provider-save-slot', [ProviderSlotController::class, 'store'] )->name('providerslot.store');
    Route::group(['middleware' => ['permission:provider list']], function () {
        Route::resource('provider', ProviderController::class);
        Route::get('provider/list/{status?}', [ProviderController::class,'index'])->name('provider.pending');
        Route::get('provider-index-data',[ProviderController::class,'index_data'])->name('provider.index_data');
        Route::get('provider/approve/{id}',[ProviderController::class, 'approve'])->name('provider.approve');
        Route::post('provider-action',[ProviderController::class, 'action'])->name('provider.action');
        Route::post('provider/{id}', [ProviderController::class, 'destroy'])->name('provider.destroy');
        Route::post('provider-bulk-action', [ProviderController::class, 'bulk_action'])->name('provider.bulk-action');
    });

    Route::group(['middleware' => ['permission:provideraddress list']], function () {
        Route::resource('provideraddress', ProviderAddressMappingController::class);
        Route::get('provideraddress-index-data',[ProviderAddressMappingController::class,'index_data'])->name('provideraddress.index_data');
        Route::post('provideraddress-bulk-action', [ProviderAddressMappingController::class, 'bulk_action'])->name('provideraddress.bulk-action');
        Route::post('provideraddress/{id}', [ProviderAddressMappingController::class, 'destroy'])->name('provideraddress.destroy');
        Route::post('/get-lat-long', [ProviderAddressMappingController::class, 'getLatLong'])->name('getLatLong');
    });

    Route::group(['middleware' => ['permission:providertype list']], function () {
        Route::resource('providertype', ProviderTypeController::class);
        Route::get('providertype-index-data',[ProviderTypeController::class,'index_data'])->name('providertype.index_data');
        Route::post('providertype-bulk-action', [ProviderTypeController::class, 'bulk_action'])->name('providertype.bulk-action');
        Route::post('providertype-action',[ProviderTypeController::class, 'action'])->name('providertype.action');
        Route::post('providertype/{id}', [ProviderTypeController::class, 'destroy'])->name('providertype.destroy');
    });
    Route::get('handyman-change-password', [ HandymanController::class , 'getChangePassword'])->name('handyman.getchangepassword');
    Route::post('handyman-change-password', [ HandymanController::class , 'changePassword'])->name('handyman.changepassword');
    Route::group(['middleware' => ['permission:handyman list']], function () {
        Route::resource('handyman', HandymanController::class);
        Route::get('handyman/list/{status?}', [HandymanController::class,'index'])->name('handyman.pending');
        Route::get('handyman-index-data',[HandymanController::class,'index_data'])->name('handyman.index_data');
        Route::post('handyman-bulk-action', [HandymanController::class, 'bulk_action'])->name('handyman.bulk-action');
        Route::get('handyman/approve/{id}',[ProviderController::class, 'approve'])->name('handyman.approve');
        Route::post('handyman-action',[HandymanController::class, 'action'])->name('handyman.action');
        Route::post('handyman/{id}', [HandymanController::class, 'destroy'])->name('handyman.destroy');
        Route::post('assign-provider', [HandymanController::class, 'updateProvider'])->name('handyman.updateProvider');
    });

    Route::group(['middleware' => ['permission:coupon list']], function () {
        Route::resource('coupon', CouponController::class);
        Route::get('coupon-index_data',[CouponController::class,'index_data'])->name('coupon.index_data');
        Route::post('coupon-bulk-action', [CouponController::class, 'bulk_action'])->name('coupon.bulk-action');
        Route::post('coupons-action',[CouponController::class, 'action'])->name('coupon.action');
        Route::post('coupon/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');
    });

    Route::group(['middleware' => ['permission:booking list']], function () {
        Route::resource('booking', BookingController::class);
        Route::get('booking-index-data',[BookingController::class,'index_data'])->name('booking.index_data');
        Route::post('booking-bulk-action', [BookingController::class, 'bulk_action'])->name('booking.bulk-action');
        Route::post('booking-status-update',[ BookingController::class,'updateStatus'])->name('bookingStatus.update');
        Route::post('booking-save', [ App\Http\Controllers\BookingController::class, 'store' ] )->name('booking.save');
        Route::post('booking-action',[BookingController::class, 'action'])->name('booking.action');
        Route::post('booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    });

    Route::group(['middleware' => ['permission:slider list']], function () {
        Route::resource('slider', SliderController::class);
        Route::get('slider-index-data',[SliderController::class,'index_data'])->name('slider.index_data');
        Route::post('slider-bulk-action', [SliderController::class, 'bulk_action'])->name('slider.bulk-action');
        Route::post('slider-action',[SliderController::class, 'action'])->name('slider.action');
        Route::post('slider/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    });

    Route::resource('payment', PaymentController::class);
    Route::get('cash-payment-list', [PaymentController::class,'cashDatatable'])->name('cash.list');
    Route::get('cash-index-data', [PaymentController::class,'cash_index_data'])->name('cash.index_data');
    Route::get('payment-index-data',[PaymentController::class,'index_data'])->name('payment.index_data');
    Route::post('payment-bulk-action', [PaymentController::class, 'bulk_action'])->name('payment.bulk-action');
    Route::get('cash/history/{id?}', [PaymentController::class,'cashIndex'])->name('cash.index');
    Route::get('paymenthistory-index-data/{id}', [PaymentController::class,'paymenthistory_index_data'])->name('paymenthistory.index_data');
    Route::get('cash/approve/{id}',[PaymentController::class, 'cashApprove'])->name('cash.approve');
    
    Route::post('save-payment',[App\Http\Controllers\API\PaymentController::class, 'savePayment'])->name('payment.save');
    
    Route::get('user-change-password', [ CustomerController::class , 'getChangePassword'])->name('user.getchangepassword');
    Route::post('user-change-password', [ CustomerController::class , 'changePassword'])->name('user.changepassword');
    Route::group(['middleware' => ['permission:user list']], function () {
        Route::resource('user', CustomerController::class);
        Route::get('user/list/{status?}', [CustomerController::class,'index'])->name('user.all');
        Route::get('user-index-data',[CustomerController::class,'index_data'])->name('user.index_data');
        Route::post('user-bulk-action', [CustomerController::class, 'bulk_action'])->name('user.bulk-action');
        Route::post('user-action',[CustomerController::class, 'action'])->name('user.action');
        Route::post('user/{id}', [CustomerController::class, 'destroy'])->name('user.destroy');
    });

    Route::get('booking-assign-form/{id}',[BookingController::class,'bookingAssignForm'])->name('booking.assign_form');
    Route::get('details/{id}',[BookingController::class,'bookingDetails'])->name('booking.details');
    Route::post('booking-assigned',[BookingController::class,'bookingAssigned'])->name('booking.assigned');
    Route::get('comission/{id}',[SettingController::class,'comission'])->name('setting.comission');


    // Setting
    Route::get('setting/{page?}',[ SettingController::class, 'settings'])->name('setting.index');
    Route::post('/layout-page',[ SettingController::class, 'layoutPage'])->name('layout_page');
    Route::post('/layout-page',[ SettingController::class, 'layoutPage'])->name('layout_page');
    Route::post('settings/save',[ SettingController::class , 'settingsUpdates'])->name('settingsUpdates');
    Route::post('dashboard-setting',[ SettingController::class , 'dashboardtogglesetting'])->name('togglesetting');
    Route::post('provider-dashboard-setting',[ SettingController::class , 'providerdashboardtogglesetting'])->name('providertogglesetting');
    Route::post('handyman-dashboard-setting',[ SettingController::class , 'handymandashboardtogglesetting'])->name('handymantogglesetting');
    Route::post('config-save',[ SettingController::class , 'configUpdate'])->name('configUpdate');

    
    Route::post('env-setting', [ SettingController::class , 'envChanges'])->name('envSetting');
    Route::post('update-profile', [ SettingController::class , 'updateProfile'])->name('updateProfile');
    Route::post('change-password', [ SettingController::class , 'changePassword'])->name('changePassword');

    Route::get('notification-list',[ NotificationController::class ,'notificationList'])->name('notification.list');
    Route::get('notification-counts',[ NotificationController::class ,'notificationCounts'])->name('notification.counts');
    Route::get('notification',[ NotificationController::class ,'index'])->name('notification.index');
    Route::get('notification-index-data',[ NotificationController::class ,'index_data'])->name('notification.index_data');

    Route::post('remove-file', [ App\Http\Controllers\HomeController::class, 'removeFile' ] )->name('remove.file');
    Route::post('get-lang-file', [ App\Http\Controllers\LanguageController::class, 'getFile' ] )->name('getLangFile');
    Route::post('save-lang-file', [ App\Http\Controllers\LanguageController::class, 'saveFileContent' ] )->name('saveLangContent');

    Route::group(['middleware' => ['permission:terms condition']], function () {
        Route::get('pages/term-condition',[ SettingController::class, 'termAndCondition'])->name('term-condition');
        Route::post('term-condition-save',[ SettingController::class, 'saveTermAndCondition'])->name('term-condition-save');
    });

    Route::group(['middleware' => ['permission:privacy policy']], function () {
        Route::get('pages/privacy-policy',[ SettingController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::post('privacy-policy-save',[ SettingController::class, 'savePrivacyPolicy'])->name('privacy-policy-save');
    });

    Route::get('pages/help-support',[ SettingController::class, 'helpAndSupport'])->name('help-support');
    Route::post('help-support-save',[ SettingController::class, 'saveHelpAndSupport'])->name('help-support-save');

    Route::get('pages/refund-cancellation-policy',[ SettingController::class, 'refundCancellationPolicy'])->name('refund-cancellation-policy');
    Route::post('refund-cancellation-policy-save',[ SettingController::class, 'saveRefundCancellationPolicy'])->name('refund-cancellation-policy-save');

    Route::group(['middleware' => ['permission:document list|providerdocument list']], function () {
        Route::resource('document', DocumentsController::class);
        Route::get('document-index-data',[DocumentsController::class,'index_data'])->name('document.index_data');
        Route::post('document-bulk-action', [DocumentsController::class, 'bulk_action'])->name('document.bulk-action');
        Route::post('document-action',[DocumentsController::class, 'action'])->name('document.action');
        Route::post('document/{id}', [DocumentsController::class, 'destroy'])->name('document.destroy');
    });

    Route::group(['middleware' => ['permission:providerdocument list']], function () {
        Route::resource('providerdocument', ProviderDocumentController::class);
        Route::get('providerdocument-index-data',[ProviderDocumentController::class,'index_data'])->name('providerdocument.index_data');
        Route::post('providerdocument-bulk-action', [ProviderDocumentController::class, 'bulk_action'])->name('providerdocument.bulk-action');
        Route::post('providerdocument-action',[ProviderDocumentController::class, 'action'])->name('providerdocument.action');
        Route::post('providerdocument/{id}', [ProviderDocumentController::class, 'destroy'])->name('providerdocument.destroy');
    });

    Route::resource('ratingreview', RatingReviewController::class);
    Route::post('ratingreview-action',[RatingReviewController::class, 'action'])->name('ratingreview.action');
    Route::get('ratingreview-index-data',[RatingReviewController::class,'index_data'])->name('ratingreview.index_data');

    Route::resource('booking-rating', BookingRatingController::class);
    Route::get('booking-rating-index-data',[BookingRatingController::class,'index_data'])->name('booking-rating.index_data');
    Route::post('booking-rating-bulk-action', [BookingRatingController::class, 'bulk_action'])->name('booking-rating.bulk-action');
    Route::post('booking-rating/{id}', [BookingController::class, 'destroy'])->name('booking-rating.destroy');
    Route::post('booking-rating-action',[CouponController::class, 'action'])->name('booking-rating.action');

    Route::resource('handyman-rating', HandymanRatingController::class);
    Route::get('handyman-rating-index-data',[HandymanRatingController::class,'index_data'])->name('handyman-rating.index_data');
    Route::post('handyman-rating-bulk-action', [HandymanRatingController::class, 'bulk_action'])->name('handyman-rating.bulk-action');
    Route::post('handyman-rating/{id}', [HandymanController::class, 'destroy'])->name('handyman-rating.destroy');

    Route::post('/payment-layout-page',[ PaymentGatewayController::class, 'paymentPage'])->name('payment_layout_page');
    Route::post('payment-settings/save',[ PaymentGatewayController::class , 'paymentsettingsUpdates'])->name('paymentsettingsUpdates');
    Route::post('get_payment_config',[ PaymentGatewayController::class , 'getPaymentConfig'])->name('getPaymentConfig');

    Route::post('/razorpay-layout-page',[ PaymentGatewayController::class, 'rezorpaypaymentPage'])->name('razorpay_layout_page');

    Route::resource('tax', TaxController::class);
    Route::get('tax-index_data',[TaxController::class,'index_data'])->name('tax.index_data');
    Route::post('tax-bulk-action', [TaxController::class, 'bulk_action'])->name('tax.bulk-action');
    Route::post('tax/{id}', [TaxController::class, 'destroy'])->name('tax.destroy');
    Route::get('earning',[EarningController::class,'index'])->name('earning');
    Route::get('earning-data',[EarningController::class,'setEarningData'])->name('earningData');
    Route::post('earning/{id}', [EarningController::class, 'destroy'])->name('earning.destroy');
    Route::get('earning/{id}', [EarningController::class, 'show'])->name('earning.show');

    Route::get('handyman-earning',[EarningController::class,'handymanEarning'])->name('handymanEarning');
    Route::get('handyman-earning-data',[EarningController::class,'handymanEarningData'])->name('handymanEarningData');

    Route::resource('providerpayout', ProviderPayoutController::class);
    Route::get('providerpayout-index-data',[ProviderPayoutController::class,'index_data'])->name('providerpayout.index_data');
    Route::post('providerpayout-bulk-action', [ProviderPayoutController::class, 'bulk_action'])->name('providerpayout.bulk-action');
    Route::get('providerpayout/create/{id}', [ProviderPayoutController::class,'create'])->name('providerpayout.create');
    Route::get('provider-payout-index-data/{id}',[ProviderPayoutController::class,'ProviderPayout_index_data'])->name('providerpayout.ProviderPayout_index_data');

    Route::get('review/{id}',[ProviderController::class,'review'])->name('provider.review');     
    Route::post('sidebar-reorder-save',[ SettingController::class, 'sequenceSave'])->name('reorderSave');

    Route::resource('handymanpayout', HandymanPayoutController::class);
    Route::get('handymanpayout-index-data',[HandymanPayoutController::class,'index_data'])->name('handymanpayout.index_data');
    Route::post('handymanpayout-bulk-action', [HandymanPayoutController::class, 'bulk_action'])->name('handymanpayout.bulk-action');
    Route::get('handymanpayout/create/{id}', [HandymanPayoutController::class,'create'])->name('handymanpayout.create');

    Route::group(['middleware' => ['permission:handymantype list']], function () {
        Route::resource('handymantype', HandymanTypeController::class);
        Route::get('handyman-index_data',[HandymanTypeController::class,'index_data'])->name('handymantype.index_data');
        Route::post('handymantype-bulk-action', [HandymanTypeController::class, 'bulk_action'])->name('handymantype.bulk-action');
        Route::post('handymantype-action',[HandymanTypeController::class, 'action'])->name('handymantype.action');
        Route::post('handymantype/{id}', [HandymanTypeController::class, 'destroy'])->name('handymantype.destroy');
    });

    Route::group(['middleware' => ['permission:servicefaq list']], function () {
        Route::resource('servicefaq', ServiceFaqController::class);
        Route::get('servicefaq-index-data',[ServiceFaqController::class,'index_data'])->name('servicefaq.index_data');
    });

    Route::post('send-push-notification', [ SettingController::class , 'sendPushNotification'])->name('sendPushNotification');
    Route::post('save-earning-setting', [ SettingController::class , 'saveEarningTypeSetting'])->name('saveEarningTypeSetting');
    Route::post('advance-earning-setting' , [ SettingController::class , 'advanceEarningSetting'])->name('advanceEarningSetting');

    Route::post('enable-user-wallet', [SettingController::class, 'enableUserWallet'])->name('enableUserWallet');

    Route::resource('wallet', WalletController::class);
    Route::get('wallet-index-data',[WalletController::class,'index_data'])->name('wallet.index_data');
    Route::post('wallet-bulk-action', [WalletController::class, 'bulk_action'])->name('wallet.bulk-action');
    Route::post('wallet/{id}', [WalletController::class, 'destroy'])->name('wallet.destroy');
    Route::get('wallet-history-index-data/{id}',[WalletController::class,'wallethistory_index_data'])->name('wallethistory.index_data');

    Route::group(['middleware' => ['permission:subcategory list']], function () {
        Route::resource('subcategory', SubCategoryController::class);
        Route::get('sub-index-data',[SubCategoryController::class,'index_data'])->name('subcategory.sub-index-data');
        Route::post('sub-bulk-action', [SubCategoryController::class, 'bulk_action'])->name('sub-bulk-action');
        Route::post('subcategory-action',[SubCategoryController::class, 'action'])->name('subcategory.action');
        Route::post('subcategory/{id}', [SubCategoryController::class, 'destroy'])->name('subcategory.destroy');
    });

    Route::resource('plans', PlanController::class);
    Route::get('plans-index-data',[PlanController::class,'index_data'])->name('plans.index_data');
    Route::post('plans-bulk-action', [PlanController::class, 'bulk_action'])->name('plans.bulk-action');
    Route::post('plans/{id}', [PlanController::class, 'destroy'])->name('plans.destroy');

    Route::resource('bank',BankController::class);
    Route::get('bank-index-data',[BankController::class, 'index_data'])->name('bank.index_data');
    Route::post('bank-bulk-action',[BankController::class,'bulk_action'])->name('bank.bulk_action');
    Route::post('bank-action',[BankController::class, 'action'])->name('bank.action');
    Route::get('bank/create/', [BankController::class,'create'])->name('bank.create');


    Route::get('frontend/app-download',[ FrontendController::class, 'appDownloadPage'])->name('app-download');
    Route::post('app-download-save',[ FrontendController::class, 'saveAppDownloadPage'])->name('app-download-save');
    Route::get('/provider-detail-page',[ ProviderController::class, 'providerDetail'])->name('provider_detail_pages');
    Route::post('/provider-detail-page',[ ProviderController::class, 'providerDetail'])->name('provider_detail_pages');
    Route::post('/booking-layout-page/{id}',[ BookingController::class, 'bookingstatus'])->name('booking_layout_page');
    Route::get('/invoice_pdf/{id}', [BookingController::class, 'createPDF'])->name('invoice_pdf');
    
    Route::group(['middleware' => ['permission:postjob list']], function () {
        Route::resource('post-job-request', PostJobRequestController::class);
        Route::get('post-job-index-data',[PostJobRequestController::class,'index_data'])->name('post-job.index_data');
        Route::post('post-job-bulk-action', [PostJobRequestController::class, 'bulk_action'])->name('post-job.bulk-action');
        Route::get('post-job-service/list/{postjobid?}', [ServiceController::class, 'index'])->name('postjobrequest.service');
        Route::get('postrequest-index-data/{id}', [PostJobRequestController::class,'postrequest_index_data'])->name('postrequest.index_data');
    });

    Route::group(['middleware' => ['permission:servicepackage list']], function () {
        Route::resource('servicepackage', ServicePackageController::class);
        Route::get('servicepackage/list/{packageid?}', [ServiceController::class,'index'])->name('servicepackage.service');
        Route::get('servicepackage-index-data',[ServicePackageController::class,'index_data'])->name('servicepackage.index-data');
        Route::post('servicepackage-bulk-action', [ServicePackageController::class, 'bulk_action'])->name('servicepackage.bulk-action');
        Route::post('servicepackage-action',[ServicePackageController::class, 'action'])->name('servicepackage.action');
    });

    Route::group(['middleware' => ['permission:blog list']], function () {
        Route::resource('blog', BlogController::class);
        Route::get('blog-index-data',[BlogController::class,'index_data'])->name('blog.index_data');
        Route::post('blog-bulk-action', [BlogController::class, 'bulk_action'])->name('blog.bulk-action');
        Route::post('blog-action',[BlogController::class, 'action'])->name('blog.action');
        Route::post('blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
    });


});
Route::get('/ajax-list',[HomeController::class, 'getAjaxList'])->name('ajax-list');
Route::post('/service-list',[HomeController::class, 'getAjaxServiceList'])->name('service-list');







