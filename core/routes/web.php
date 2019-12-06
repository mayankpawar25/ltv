<?php
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
/*Route::get('/', function () {
    return redirect('admin');
});*/

/*Route::get('/', function () {
    return redirect('admin');
})->name('login');*/

/*Route::get('/login', function () {
    return redirect('admin');
})->name('login');*/

/*Route::get('/contact', function () {
    return redirect('admin');
});
Route::get('/shop', function () {
    return redirect('admin');
});*/


Route::get('/allcollection', 'Admin\Cron\CronController@viewTodaysCollection')->name('datatables_area');

Route::get('/import/download/sample', 'Admin\AreaController@download_sample_area_import_file')->name('download_sample_area_import_file');
Route::post('/paginate', 'Admin\AreaController@paginate')->name('datatables_area');

Route::get('qrcode', 'QrcodeController@generateQrCode')->name('generateqrcode');

//Payment IPN
Route::get('/ipnbtc', 'Vendor\DepositController@ipnBchain')->name('ipn.bchain');
Route::get('/ipnblockbtc', 'Vendor\DepositController@blockIpnBtc')->name('ipn.block.btc');
Route::get('/ipnblocklite', 'Vendor\DepositController@blockIpnLite')->name('ipn.block.lite');
Route::get('/ipnblockdog', 'Vendor\DepositController@blockIpnDog')->name('ipn.block.dog');
Route::post('/ipnpaypal', 'Vendor\DepositController@ipnpaypal')->name('ipn.paypal');
Route::post('/ipnperfect', 'Vendor\DepositController@ipnperfect')->name('ipn.perfect');
Route::post('/ipnstripe', 'Vendor\DepositController@ipnstripe')->name('ipn.stripe');
Route::post('/ipnskrill', 'Vendor\DepositController@skrillIPN')->name('ipn.skrill');
Route::post('/ipncoinpaybtc', 'Vendor\DepositController@ipnCoinPayBtc')->name('ipn.coinPay.btc');
Route::post('/ipncoinpayeth', 'Vendor\DepositController@ipnCoinPayEth')->name('ipn.coinPay.eth');
Route::post('/ipncoinpaybch', 'Vendor\DepositController@ipnCoinPayBch')->name('ipn.coinPay.bch');
Route::post('/ipncoinpaydash', 'Vendor\DepositController@ipnCoinPayDash')->name('ipn.coinPay.dash');
Route::post('/ipncoinpaydoge', 'Vendor\DepositController@ipnCoinPayDoge')->name('ipn.coinPay.doge');
Route::post('/ipncoinpayltc', 'Vendor\DepositController@ipnCoinPayLtc')->name('ipn.coinPay.ltc');
Route::post('/ipncoin', 'Vendor\DepositController@ipnCoin')->name('ipn.coinpay');
Route::post('/ipncoingate', 'Vendor\DepositController@ipnCoinGate')->name('ipn.coingate');

Route::post('/ipnpaytm', 'Vendor\DepositController@ipnPayTm')->name('ipn.paytm');
Route::post('/ipnpayeer', 'Vendor\DepositController@ipnPayEer')->name('ipn.payeer');
Route::post('/ipnpaystack', 'Vendor\DepositController@ipnPayStack')->name('ipn.paystack');
Route::post('/ipnvoguepay', 'Vendor\DepositController@ipnVoguePay')->name('ipn.voguepay');
//Payment IPN

//Payment IPN
Route::get('/ipnbtc/payment', 'User\GatewayController@ipnBchain')->name('ipn.bchain.payment');
Route::get('/ipnblockbtc/payment', 'User\GatewayController@blockIpnBtc')->name('ipn.block.btc.payment');
Route::get('/ipnblocklite/payment', 'User\GatewayController@blockIpnLite')->name('ipn.block.lite.payment');
Route::get('/ipnblockdog/payment', 'User\GatewayController@blockIpnDog')->name('ipn.block.dog.payment');
Route::post('/ipnpaypal/payment', 'User\GatewayController@ipnpaypal')->name('ipn.paypal.payment');
Route::post('/ipnperfect/payment', 'User\GatewayController@ipnperfect')->name('ipn.perfect.payment');
Route::post('/ipnstripe/payment', 'User\GatewayController@ipnstripe')->name('ipn.stripe.payment');
Route::post('/ipnskrill/payment', 'User\GatewayController@skrillIPN')->name('ipn.skrill.payment');
Route::post('/ipncoinpaybtc/payment', 'User\GatewayController@ipnCoinPayBtc')->name('ipn.coinPay.btc.payment');
Route::post('/ipncoinpayeth/payment', 'User\GatewayController@ipnCoinPayEth')->name('ipn.coinPay.eth.payment');
Route::post('/ipncoinpaybch/payment', 'User\GatewayController@ipnCoinPayBch')->name('ipn.coinPay.bch.payment');
Route::post('/ipncoinpaydash/payment', 'User\GatewayController@ipnCoinPayDash')->name('ipn.coinPay.dash.payment');
Route::post('/ipncoinpaydoge/payment', 'User\GatewayController@ipnCoinPayDoge')->name('ipn.coinPay.doge.payment');
Route::post('/ipncoinpayltc/payment', 'User\GatewayController@ipnCoinPayLtc')->name('ipn.coinPay.ltc.payment');
Route::post('/ipncoin/payment', 'User\GatewayController@ipnCoin')->name('ipn.coinpay.payment');
Route::post('/ipncoingate/payment', 'User\GatewayController@ipnCoinGate')->name('ipn.coingate.payment');
Route::post('/ipnpaytm/payment', 'User\GatewayController@ipnPayTm')->name('ipn.paytm.payment');
Route::post('/ipnpayeer/payment', 'User\GatewayController@ipnPayEer')->name('ipn.payeer.payment');
Route::post('/ipnpaystack/payment', 'User\GatewayController@ipnPayStack')->name('ipn.paystack.payment');
Route::post('/ipnvoguepay/payment', 'User\GatewayController@ipnVoguePay')->name('ipn.voguepay.payment');

//Payment IPN
Route::get('/', 'User\PagesController@home')->name('user.home')->middleware('emailVerification', 'smsVerification', 'bannedUser');
Route::get('/flashsalecheck', 'User\PagesController@flashsalecheck')->name('flashsalecheck');
Route::get('/contact', 'User\PagesController@contact')->name('user.contact');
Route::post('/contact/mail', 'User\PagesController@contactMail')->name('user.contactMail');
Route::get('/product/{slug}/{id}', 'ProductController@show')->name('user.product.details');
Route::get('/product/getcomments', 'ProductController@getcomments')->name('user.product.getcomments');
Route::post('/cart/getproductdetails', 'CartController@getproductdetails')->name('user.cart.getproductdetails');
Route::get('/cart/getcart', 'CartController@getcart')->name('cart.getcart');
Route::get('/cart/clearcart', 'CartController@clearcart')->name('cart.clearcart');
Route::get('/cart/remove', 'CartController@remove')->name('cart.remove');
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/stock/check', 'CartController@stockcheck')->name('stock.check');
Route::post('/cart/update', 'CartController@update')->name('cart.update');
Route::get('/cart/getTotal', 'CartController@getTotal')->name('cart.getTotal');
Route::get('terms&conditions', 'User\PagesController@terms')->name('terms');
Route::get('privacypolicy', 'User\PagesController@privacy')->name('privacy');
Route::get('/{id}/productratings', 'ProductController@productratings')->name('user.productratings');
Route::get('/{id}/avgrating', 'ProductController@avgrating')->name('user.avgrating');
Route::get('/shop_page/{vendor}/{category?}/{subcategory?}', 'Vendor\VendorController@shoppage')->name('vendor.shoppage');
Route::post('/subscribe', 'User\PagesController@subscribe')->name('user.subscribe');

// Dynamic Routes
Route::get('/{slug}/pages', 'User\PagesController@dynamicPage')->name('user.dynamicPage');
// Search Routes
Route::get('/shop/{category?}/{subcategory?}', 'SearchController@search')->name('user.search');
Route::get('/bestsellers', 'User\PagesController@bestsellers')->name('user.bestsellers');

// Cron Jobs
// it wil run per day
Route::get('/couponvaliditycheck', 'User\CheckoutController@couponvaliditycheck')->name('checkout.couponvaliditycheck');

// Ad increase route
Route::post('/ad/increaseAdView', 'User\AdController@increaseAdView')->name('ad.increaseAdView');
Route::post('review/submit', 'ProductController@reviewsubmit')->name('user.review.submit');

#=========== User Routes =============#
Route::group(['middleware' => 'guest'], function() {
		Route::get('/login', 'User\LoginController@login')->name('customer_login_page');
		Route::get('/login2', 'User\LoginController@login')->name('login');
		Route::post('/authenticate', 'User\LoginController@authenticate')->name('user.authenticate');

		Route::get('auth/{provider}', 'User\LoginController@redirectToProvider');

    	Route::get('auth/{provider}/callback', 'User\LoginController@handleProviderCallback');


		Route::get('/register', 'User\RegController@showregform')->name('user.showregform');
		Route::post('/register', 'User\RegController@register')->name('user.register');

		// Password Reset Routes
	    Route::get('/showEmailForm', 'User\ForgotPasswordController@showEmailForm')->name('user.showEmailForm');
	    Route::post('/sendResetPassMail', 'User\ForgotPasswordController@sendResetPassMail')->name('user.sendResetPassMail');
	    Route::get('/reset/{code}', 'User\ForgotPasswordController@resetPasswordForm')->name('user.resetPasswordForm');
	    Route::post('/resetPassword', 'User\ForgotPasswordController@resetPassword')->name('user.resetPassword');
});


Route::group(['middleware' => 'auth'], function() {
	
	Route::get('/logout/{id?}', 'User\LoginController@logout')->name('user.logout');
	
	// Verification Routes...
	Route::get('/showEmailVerForm', 'User\VerificationController@showEmailVerForm')->name('user.showEmailVerForm');
	Route::get('/showSmsVerForm', 'User\VerificationController@showSmsVerForm')->name('user.showSmsVerForm');
	Route::post('/checkEmailVerification', 'User\VerificationController@emailVerification')->name('user.checkEmailVerification');
	Route::post('/checkSmsVerification', 'User\VerificationController@smsVerification')->name('user.checkSmsVerification');
	Route::post('/sendVcode', 'User\VerificationController@sendVcode')->name('user.sendVcode');

	// Profile routes
	Route::get('/profile', 'User\ProfileController@profile')->name('user.profile')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/infoupdate', 'User\ProfileController@infoupdate')->name('user.information.update');
	Route::get('/changepassword', 'User\ProfileController@changepassword')->name('user.changepassword')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/update/password', 'User\ProfileController@updatePassword')->name('user.updatePassword');

	// Shipping address routes
	Route::get('/shipping', 'User\ProfileController@shipping')->name('user.shipping')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/shippingupdate', 'User\ProfileController@shippingupdate')->name('user.shippingupdate');

	// Billing address routes
	Route::get('/billing', 'User\ProfileController@billing')->name('user.billing')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/billingupdate', 'User\ProfileController@billingupdate')->name('user.billingupdate');

	// Checkout Routes
	Route::get('/checkout', 'User\CheckoutController@index')->name('user.checkout.index')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/coupon/apply', 'User\CheckoutController@applycoupon')->name('user.checkout.applycoupon');
	Route::post('/placeorder', 'User\CheckoutController@placeorder')->name('user.checkout.placeorder');
	Route::get('/checkout/success', 'User\CheckoutController@success')->name('user.checkout.success')->middleware('emailVerification', 'smsVerification', 'bannedUser');

	// Gateway Routes
	Route::match(['get', 'post'], '/gateways/{orderid?}', 'User\GatewayController@gateways')->name('user.gateways')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/paymentDataInsert', 'User\GatewayController@paymentDataInsert')->name('user.paymentDataInsert');
	Route::get('/paymentPreview', 'User\GatewayController@paymentPreview')->name('user.paymentPreview')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/payment-confirm', 'User\GatewayController@paymentConfirm')->name('payment.confirm');

	// favorit
	Route::get('/wishlist', 'User\ProfileController@wishlist')->name('user.wishlist')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/favorit', 'ProductController@favorit')->name('user.favorit');

	// orders
	Route::get('/orders', 'User\ProfileController@orders')->name('user.orders')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::get('/{orderid}/orderdetails', 'User\ProfileController@orderdetails')->name('user.orderdetails')->middleware('emailVerification', 'smsVerification', 'bannedUser');
	Route::post('/complain', 'User\ProfileController@complain')->name('user.complain');
	Route::post('/refund', 'User\ProfileController@refund')->name('user.refund');

});

#=========== Vendor Routes =============#
Route::get('/vendor','Vendor\LoginController@login')->name('vendor.login')->middleware('guest:vendor');

Route::group(['prefix' => 'vendor', 'middleware' => 'guest:vendor'], function () {
	Route::post('/authenticate', 'Vendor\LoginController@authenticate')->name('vendor.authenticate');

	Route::get('/register', 'Vendor\RegController@showRegForm')->name('vendor.showRegForm');
	Route::post('/register', 'Vendor\RegController@register')->name('vendor.reg');

	// Password Reset Routes
	Route::get('/showEmailForm', 'Vendor\ForgotPasswordController@showEmailForm')->name('vendor.showEmailForm');
	Route::post('/sendResetPassMail', 'Vendor\ForgotPasswordController@sendResetPassMail')->name('vendor.sendResetPassMail');
	Route::get('/reset/{code}', 'Vendor\ForgotPasswordController@resetPasswordForm')->name('vendor.resetPasswordForm');
	Route::post('/resetPassword', 'Vendor\ForgotPasswordController@resetPassword')->name('vendor.resetPassword');
});

Route::group(['prefix' => 'vendor', 'middleware' => ['auth:vendor']], function () {
	Route::get('/dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard')->middleware('bannedVendor');

	Route::get('/logout/{id?}', 'Vendor\LoginController@logout')->name('vendor.logout');


	// transaction log
	Route::get('/transactions', 'Vendor\VendorController@transactions')->name('vendor.transactions');

	// Coupon log
	Route::get('/couponlog', 'Vendor\VendorController@couponlog')->name('vendor.couponlog');

	// Password Routes
	Route::get('/changepassword', 'Vendor\VendorController@changePassword')->name('vendor.changePassword')->middleware('bannedVendor');
	Route::post('/updatepassword', 'Vendor\VendorController@updatePassword')->name('vendor.updatePassword');


	// All deposit methods...
	Route::match(['get', 'post'], '/depositMethods', 'Vendor\DepositController@showDepositMethods')->name('vendor.showDepositMethods')->middleware('bannedVendor');
	Route::post('/depositDataInsert', 'Vendor\DepositController@depositDataInsert')->name('vendor.depositDataInsert');
	Route::get('/deposit-preview', 'Vendor\DepositController@depositPreview')->name('vendor.deposit.preview')->middleware('bannedVendor');
	Route::post('/deposit-confirm', 'Vendor\DepositController@depositConfirm')->name('deposit.confirm');


	// All withdraw routes...
	Route::get('/withdrawMoney', 'Vendor\WithdrawMoneyController@withdrawMoney')->name('vendor.withdrawMoney')->middleware('bannedVendor');
	Route::post('/withdrawRequest/store', 'Vendor\WithdrawMoneyController@store')->name('vendor.withdrawRequest.store');


	// Package Routes
  Route::get('/packages', 'Vendor\PackageController@index')->name('package.index')->middleware('bannedVendor');
  Route::post('/package/buy', 'Vendor\PackageController@buy')->name('package.buy');
	Route::get('/validitycheck', 'Vendor\PackageController@validitycheck')->name('package.validitycheck');



	// Settings Routes
	Route::get('/settings', 'Vendor\SettingController@settings')->name('vendor.setting')->middleware('bannedVendor');
	Route::post('/settings/update', 'Vendor\SettingController@update')->name('vendor.setting.update');



	// Product Routes
	Route::get('/product/create', 'Vendor\ProductController@create')->name('vendor.product.create')->middleware('bannedVendor');
	Route::post('/product/store', 'Vendor\ProductController@store')->name('vendor.product.store');
	Route::get('/product/getsubcategories', 'Vendor\ProductController@getsubcats')->name('vendor.product.getsubcats');
	Route::get('/product/getattributes', 'Vendor\ProductController@getattributes')->name('vendor.product.getattributes');
	Route::get('/product/manage', 'Vendor\ProductController@manage')->name('vendor.product.manage')->middleware('bannedVendor');
	Route::get('/product/{id}/edit', 'Vendor\ProductController@edit')->name('vendor.product.edit')->middleware('bannedVendor');
	Route::post('/product/update', 'Vendor\ProductController@update')->name('vendor.product.update');
	Route::get('/product/{id}/getimgs', 'Vendor\ProductController@getimgs')->name('vendor.product.getimgs');
	Route::post('/delete', 'Vendor\ProductController@delete')->name('vendor.product.delete');


	// Order Routes
	Route::get('/orders', 'Vendor\OrderController@orders')->name('vendor.orders')->middleware('bannedVendor');
	Route::get('/{orderid}/orderdetails', 'Vendor\OrderController@orderdetails')->name('vendor.orderdetails')->middleware('bannedVendor');
});




	#=========== Admin Routes =============#
	Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
		Route::get('/','Admin\AdminLoginController@index')->name('admin.loginForm');
		Route::post('/authenticate', 'Admin\AdminLoginController@authenticate')->name('admin.login');
	});
  	Route::get('/import/download/sample', 'Admin\ShopkeeperController@download_sample_dealer_import_file')->name('download_sample_dealer_import_file');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin','set_user_permission','set_global_config']], function () {
  		
  	/* New Web Changes Added */
  	Route::get('/attachment/delete/{attachment}', 'AttachmentController@destroy')->name('remove_attachment');
    Route::post('/attachment/profile/photo', 'AttachmentController@change_profile_photo')->name('change_profile_photo');
  	/* New Web Changes Added */
    

  	Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('admin.dashboard');
	Route::get('/logout', 'Admin\AdminController@logout')->name('admin.logout');

	// Coupon Routes
	Route::get('/coupon/index', 'Admin\CouponController@index')->name('admin.coupon.index');
	Route::get('/coupon/create', 'Admin\CouponController@create')->name('admin.coupon.create');
	Route::post('/coupon/store', 'Admin\CouponController@store')->name('admin.coupon.store');
	Route::get('/coupon/{id}/edit', 'Admin\CouponController@edit')->name('admin.coupon.edit');
	Route::post('/coupon/update', 'Admin\CouponController@update')->name('admin.coupon.update');
	Route::post('/coupon/delete', 'Admin\CouponController@delete')->name('admin.coupon.delete');

	// Report Routes
  	Route::get('/reports', 'Admin\crm\ReportController@reports')->name('admin.reports');

  	// Reports
    Route::group(['prefix' => 'reports',  'middleware' => 'perm:reports_view'], function() {
        Route::get('/sales', 'Admin\crm\ReportController@sales')->name('report_sales_page');
        Route::get('/expenses', 'Admin\crm\ReportController@expenses')->name('report_expenses_page');
        Route::get('/expenses/download', 'Admin\crm\ReportController@download_expense_report')->name('report_expenses_download');
        Route::get('/activity-log', 'Admin\crm\ReportController@activity_log')->name('report_activity_log');
        Route::post('/activity-log', 'Admin\crm\ReportController@activity_log_paginate')->name('datatable_activity_log');
        Route::get('/timesheet', 'TimeSheetController@report_page')->name('report_timesheet_page');
        Route::get('/leads', 'Admin\crm\LeadController@report_page')->name('lead_report_page');
    });


  	// Profile Routes
  	Route::get('/changePassword', 'Admin\AdminController@changePass')->name('admin.changePass');
  	Route::post('/profile/updatePassword', 'Admin\AdminController@updatePassword')->name('admin.updatePassword');
  	Route::get('/profile/edit/{adminID}', 'Admin\AdminController@editProfile')->name('admin.editProfile');
	Route::post('/profile/update/{adminID}', 'Admin\AdminController@updateProfile')->name('admin.updateProfile');

  	// Website Control Routes...
  	Route::get('/generalSetting', 'Admin\GeneralSettingController@GenSetting')->name('admin.GenSetting');
	Route::post('/generalSetting', 'Admin\GeneralSettingController@UpdateGenSetting')->name('admin.UpdateGenSetting');
	Route::get('/EmailSetting', 'Admin\EmailSettingController@index')->name('admin.EmailSetting');
	Route::post('/EmailSetting', 'Admin\EmailSettingController@updateEmailSetting')->name('admin.UpdateEmailSetting');
  	Route::get('/SmsSetting', 'Admin\SmsSettingController@index')->name('admin.SmsSetting');
  	Route::post('/SmsSetting', 'Admin\SmsSettingController@updateSmsSetting')->name('admin.UpdateSmsSetting');
	Route::get('/facebook/index', 'Admin\FacebookController@index')->name('admin.facebook.index');
	Route::post('/facebook/update', 'Admin\FacebookController@update')->name('admin.facebook.update');

	// Charge Setting Routes
	Route::get('/charge/index', 'Admin\ChargeController@index')->name('admin.charge.index');
	Route::post('/shipping/update', 'Admin\ChargeController@shippingupdate')->name('admin.shipping.update');
	Route::post('/tax/update', 'Admin\ChargeController@taxupdate')->name('admin.tax.update');

	// Package Routes
	Route::get('/packages', 'Admin\PackageController@index')->name('admin.package');
	Route::post('/packages/store', 'Admin\PackageController@store')->name('admin.package.store');
	Route::post('/packages/update', 'Admin\PackageController@update')->name('admin.package.update');

	// Product Attribute Management...
	Route::get('/productattr/index', 'Admin\ProductattrController@index')->name('admin.productattr.index');
	Route::post('/productattr/store', 'Admin\ProductattrController@store')->name('admin.productattr.store');
	Route::post('/productattr/update', 'Admin\ProductattrController@update')->name('admin.productattr.update');

	// Option Management...
	Route::get('/options/{id}/index', 'Admin\OptionController@index')->name('admin.options.index');
	Route::post('/options/store', 'Admin\OptionController@store')->name('admin.options.store');
	Route::post('/options/update', 'Admin\OptionController@update')->name('admin.options.update');

	// Category Management...
	Route::get('/category/index', 'Admin\CategoryController@index')->name('admin.category.index')->middleware('perm:categorys_view');
	Route::post('/category/store', 'Admin\CategoryController@store')->name('admin.category.store')->middleware('perm:categorys_create');
	Route::post('/category/update', 'Admin\CategoryController@update')->name('admin.category.update')->middleware('perm:categorys_edit');

	// Subcategory Management...
	Route::get('/subcategory/{id}/index', 'Admin\SubcategoryController@index')->name('admin.subcategory.index')->middleware('perm:categorys_view');
	Route::post('/subcategory/store', 'Admin\SubcategoryController@store')->name('admin.subcategory.store')->middleware('perm:categorys_create');
	Route::post('/subcategory/update', 'Admin\SubcategoryController@update')->name('admin.subcategory.update')->middleware('perm:categorys_edit');

	// Vendor management Routes...
	Route::get('/vendorManagement/allVendors', 'Admin\VendorManagementController@allVendors')->name('admin.allVendors');
	Route::get('/vendorManagement/allVendorsSearchResult', 'Admin\VendorManagementController@allVendorsSearchResult' )->name('admin.allVendorsSearchResult');
	Route::get('/vendorManagement/bannedVendors', 'Admin\VendorManagementController@bannedVendors')->name('admin.bannedVendors');
	Route::get('/vendorManagement/bannedVendorsSearchResult', 'Admin\VendorManagementController@bannedVendorsSearchResult' )->name('admin.bannedVendorsSearchResult');
	Route::get('/vendorManagement/vendorDetails/{vendorID}', 'Admin\VendorManagementController@vendorDetails')->name('admin.vendorDetails');
	Route::post('/vendorManagement/updateVendorDetails', 'Admin\VendorManagementController@updateVendorDetails')->name('admin.updateVendorDetails');
	Route::get('/vendorManagement/addSubtractBalance/{vendorID}', 'Admin\VendorManagementController@addSubtractBalance')->name('admin.vendor.addSubtractBalance');
	Route::post('/vendorManagement/updateVendorBalance', 'Admin\VendorManagementController@updateVendorBalance')->name('admin.updateVendorBalance');
	Route::get('/vendorManagement/emailToVendor/{vendorID}', 'Admin\VendorManagementController@emailToVendor')->name('admin.emailToVendor');
	Route::post('/vendorManagement/sendEmailToVendor', 'Admin\VendorManagementController@sendEmailToVendor')->name('admin.sendEmailToVendor');

	// User management Routes...
	Route::get('/userManagement/allUsers', 'Admin\UserManagementController@allUsers')->name('admin.allUsers')->middleware('perm:users_view');

	Route::get('/userManagement/allUsersSearchResult', 'Admin\UserManagementController@allUsersSearchResult' )->name('admin.allUsersSearchResult')->middleware('perm:users_view');

	Route::get('/userManagement/bannedUsers', 'Admin\UserManagementController@bannedUsers')->name('admin.bannedUsers')->middleware('perm:users_view');

	Route::get('/userManagement/bannedUsersSearchResult', 'Admin\UserManagementController@bannedUsersSearchResult' )->name('admin.bannedUsersSearchResult')->middleware('perm:users_view');

	Route::get('/userManagement/verifiedUsers', 'Admin\UserManagementController@verifiedUsers')->name('admin.verifiedUsers')->middleware('perm:users_view');

	Route::get('/userManagement/verUsersSearchResult', 'Admin\UserManagementController@verUsersSearchResult' )->name('admin.verUsersSearchResult')->middleware('perm:users_view');

	Route::get('/userManagement/mobileUnverifiedUsers', 'Admin\UserManagementController@mobileUnverifiedUsers')->name('admin.mobileUnverifiedUsers')->middleware('perm:users_view');

	Route::get('/userManagement/mobileUnverifiedUsersSearchResult', 'Admin\UserManagementController@mobileUnverifiedUsersSearchResult' )->name('admin.mobileUnverifiedUsersSearchResult')->middleware('perm:users_view');

	Route::get('/userManagement/emailUnverifiedUsers', 'Admin\UserManagementController@emailUnverifiedUsers')->name('admin.emailUnverifiedUsers')->middleware('perm:users_view');

	Route::get('/userManagement/emailUnverifiedUsersSearchResult', 'Admin\UserManagementController@emailUnverifiedUsersSearchResult' )->name('admin.emailUnverifiedUsersSearchResult')->middleware('perm:users_view');

	Route::get('/userManagement/userDetails/{userID}', 'Admin\UserManagementController@userDetails')->name('admin.userDetails')->middleware('perm:users_view');

	Route::post('/userManagement/updateUserDetails', 'Admin\UserManagementController@updateUserDetails')->name('admin.updateUserDetails');

	Route::get('/userManagement/addSubtractBalance/{userID}', 'Admin\UserManagementController@addSubtractBalance')->name('admin.addSubtractBalance');
	Route::post('/userManagement/updateUserBalance', 'Admin\UserManagementController@updateUserBalance')->name('admin.updateUserBalance');
	Route::get('/userManagement/emailToUser/{userID}', 'Admin\UserManagementController@emailToUser')->name('admin.emailToUser');
	Route::post('/userManagement/sendEmailToUser', 'Admin\UserManagementController@sendEmailToUser')->name('admin.sendEmailToUser');
	Route::get('/userManagement/ads/{userID}', 'Admin\UserManagementController@ads')->name('admin.userManagement.ads');

	// Subscriber Management Routes
	Route::get('/subscribers', 'Admin\SubscManageController@subscribers')->name('admin.subscribers');
	Route::post('/mailtosubsc', 'Admin\SubscManageController@mailtosubsc')->name('admin.mailtosubsc');

	// Gateway Routes...
	Route::get('/gateways', 'Admin\GatewayController@index')->name('admin.gateways')->middleware('perm:gateways_view');
	Route::post('/gateway/update', 'Admin\GatewayController@update')->name('update.gateway')->middleware('perm:gateways_edit');
	Route::post('/gateway/store', 'Admin\GatewayController@store')->name('store.gateway')->middleware('perm:gateways_create');

	// Vendor Routes...
	Route::get('/vendors/all', 'Admin\VendorController@all')->name('admin.vendors.all');
	Route::get('/vendors/pending', 'Admin\VendorController@pending')->name('admin.vendors.pending');
	Route::get('/vendors/accepted', 'Admin\VendorController@accepted')->name('admin.vendors.accepted');
	Route::get('/vendors/rejected', 'Admin\VendorController@rejected')->name('admin.vendors.rejected');
	Route::post('/vendors/accept', 'Admin\VendorController@accept')->name('admin.vendors.accept');
	Route::post('/vendors/reject', 'Admin\VendorController@reject')->name('admin.vendors.reject');

	// Flash Sale setups
	Route::get('/flashsale/times', 'Admin\FlashsaleController@times')->name('admin.flashsale.times');
	Route::post('/flashsale/updatetimes', 'Admin\FlashsaleController@updatetimes')->name('admin.flashsale.updatetimes');
	Route::get('/flashsale/all', 'Admin\FlashsaleController@all')->name('admin.flashsale.all');
	Route::get('/flashsale/pending', 'Admin\FlashsaleController@pending')->name('admin.flashsale.pending');
	Route::get('/flashsale/accepted', 'Admin\FlashsaleController@accepted')->name('admin.flashsale.accepted');
	Route::get('/flashsale/rejected', 'Admin\FlashsaleController@rejected')->name('admin.flashsale.rejected');
	Route::post('/flashsale/changestatus', 'Admin\FlashsaleController@changestatus')->name('admin.flashsale.changestatus');

	// Order Routes...
	Route::get('/orders/all/{shopkeeper_id?}', 'Admin\OrderController@all')->name('admin.orders.all');
	Route::get('/orders/confirmation/pending', 'Admin\OrderController@cPendingOrders')->name('admin.orders.cPendingOrders');
	Route::get('/orders/confirmation/accepted', 'Admin\OrderController@cAcceptedOrders')->name('admin.orders.cAcceptedOrders');
	Route::get('/orders/confirmation/rejected', 'Admin\OrderController@cRejectedOrders')->name('admin.orders.cRejectedOrders');
	Route::get('/orders/delivery/pending', 'Admin\OrderController@pendingDelivery')->name('admin.orders.pendingDelivery');
	Route::get('/orders/delivery/inprocess', 'Admin\OrderController@pendingInprocess')->name('admin.orders.pendingInprocess');
	Route::get('/orders/delivered', 'Admin\OrderController@delivered')->name('admin.orders.delivered');
	Route::get('/orders/cashondelivery', 'Admin\OrderController@cashOnDelivery')->name('admin.orders.cashOnDelivery');
	Route::get('/orders/advance', 'Admin\OrderController@advance')->name('admin.orders.advance');
	Route::get('/{orderid}/orderdetails', 'Admin\OrderController@orderdetails')->name('admin.orderdetails');

	Route::post('/orders/paymentstatus', 'Admin\OrderController@paymentStatus')->name('admin.orders.paymentstatus');

	// Comment routes..
	Route::get('/comments', 'Admin\CommentController@all')->name('admin.comments.all');
	Route::get('/complains', 'Admin\CommentController@complains')->name('admin.complains');
	Route::get('/suggestions', 'Admin\CommentController@suggestions')->name('admin.suggestions');

	// Refund routes..
	Route::get('/refunds/all', 'Admin\RefundController@all')->name('admin.refunds.all');
	Route::get('/refunds/pending', 'Admin\RefundController@pending')->name('admin.refunds.pending');
	Route::get('/refunds/accepted', 'Admin\RefundController@accepted')->name('admin.refunds.accepted');
	Route::get('/refunds/rejected', 'Admin\RefundController@rejected')->name('admin.refunds.rejected');
	Route::post('/refunds/accept', 'Admin\RefundController@accept')->name('admin.refunds.accept');
	Route::post('/refunds/reject', 'Admin\RefundController@reject')->name('admin.refunds.reject');
	Route::post('/shippingchange', 'Admin\OrderController@shippingchange')->name('admin.shippingchange');
	Route::post('/cancelOrder', 'Admin\OrderController@cancelOrder')->name('admin.cancelOrder');
	Route::post('/acceptOrder', 'Admin\OrderController@acceptOrder')->name('admin.acceptOrder');

	// Deposit Routes...
  	Route::get('/deposit/pending','Admin\DepositController@pending')->name('admin.deposit.pending');
	Route::get('/deposit/showReceipt', 'Admin\DepositController@showReceipt')->name('admin.deposit.showReceipt');
	Route::post('/deposit/accept', 'Admin\DepositController@accept')->name('admin.deposit.accept');
	Route::post('/deposit/rejectReq','Admin\DepositController@rejectReq')->name('admin.deposit.rejectReq');
	Route::get('/deposit/acceptedRequests','Admin\DepositController@acceptedRequests')->name('admin.deposit.acceptedRequests');
	Route::get('/deposit/depositLog','Admin\DepositController@depositLog')->name('admin.deposit.depositLog');
	Route::get('/deposit/rejectedRequests','Admin\DepositController@rejectedRequests')->name('admin.deposit.rejectedRequests');

	// Withdraw method CRUD routes...
	Route::get('/withdrawMethod', 'Admin\withdrawMoney\withdrawMethodController@withdrawMethod')->name('admin.withdrawMethod');
	Route::post('/withdrawMethod/store', 'Admin\withdrawMoney\withdrawMethodController@store')->name('withdrawMethod.store');
	Route::get('/withdrawMethod/edit', 'Admin\withdrawMoney\withdrawMethodController@edit')->name('withdrawMethod.edit');
	Route::post('/withdrawMethod/update', 'Admin\withdrawMoney\withdrawMethodController@update')->name('withdrawMethod.update');
	Route::post('/withdrawMethod/delete', 'Admin\withdrawMoney\withdrawMethodController@destroy')->name('withdrawMethod.destroy');
	Route::post('/withdrawMethod/enable', 'Admin\withdrawMoney\withdrawMethodController@enable')->name('withdrawMethod.enable');
	
	// Withdraw Money Routes
	Route::get('/withdrawLog', 'Admin\withdrawMoney\withdrawLogController@withdrawLog')->name('admin.withdrawLog');
	Route::get('/successLog', 'Admin\withdrawMoney\successLogController@successLog')->name('admin.withdrawMoney.successLog');
	Route::get('/refundedLog', 'Admin\withdrawMoney\refundedLogController@refundedLog')->name('admin.withdrawMoney.refundedLog');
	Route::get('/pendingLog', 'Admin\withdrawMoney\pendingLogController@pendingLog')->name('admin.withdrawMoney.pendingLog');

	Route::get('/withdrawLog/{wID}', 'Admin\withdrawMoney\withdrawLogController@show')->name('withdrawLog.show');
	Route::post('/withdrawLog/message/store', 'Admin\withdrawMoney\withdrawLogController@storeMessage')->name('withdrawLog.message.store');

	// Ad Routes...
	Route::get('/Ad/index', 'Admin\AdController@index')->name('admin.ad.index');
	Route::get('/Ad/create', 'Admin\AdController@create')->name('admin.ad.create');
	Route::post('/Ad/store', 'Admin\AdController@store')->name('admin.ad.store');
	Route::get('/Ad/showImage', 'Admin\AdController@showImage')->name('admin.ad.showImage');
	Route::post('/Ad/delete', 'Admin\AdController@delete')->name('admin.ad.delete');


	// Refund Policy Routes
	Route::get('policy/refund/index', 'Admin\PolicyController@refund')->name('admin.refund.index');
	Route::post('policy/refund/update', 'Admin\PolicyController@refundupdate')->name('admin.refund.update');

	// Refund Policy Routes
	Route::get('policy/replacement/index', 'Admin\PolicyController@replacement')->name('admin.replacement.index');
	Route::post('policy/replacement/update', 'Admin\PolicyController@replacementupdate')->name('admin.replacement.update');


	// Menu Manager Routes
	Route::get('/menuManager/index', 'Admin\menuManagerController@index')->name('admin.menuManager.index');
  	Route::get('/menuManager/add', 'Admin\menuManagerController@add')->name('admin.menuManager.add');
	Route::post('/menuManager/store', 'Admin\menuManagerController@store')->name('admin.menuManager.store');
	Route::get('/menuManager/{menuID}/edit', 'Admin\menuManagerController@edit')->name('admin.menuManager.edit');
	Route::post('/menuManager/{menuID}/update', 'Admin\menuManagerController@update')->name('admin.menuManager.update');
	Route::post('/menuManager/{menuID}/delete', 'Admin\menuManagerController@delete')->name('admin.menuManager.delete');


	// Terms & COndition Routes
	Route::get('/tos/index', 'Admin\TosController@index')->name('admin.tos.index');
	Route::post('/tos/update', 'Admin\TosController@update')->name('admin.tos.update');

	// Privacy Policy Routes
	Route::get('/privacy/index', 'Admin\PrivacyController@index')->name('admin.privacy.index');
	Route::post('/privacy/update', 'Admin\PrivacyController@update')->name('admin.privacy.update');

	// Interface Control Routes
  	Route::get('/interfaceControl/logoIcon/index', 'Admin\InterfaceControl\LogoIconController@index')->name('admin.logoIcon.index');
	Route::post('/interfaceControl/logoIcon/update', 'Admin\InterfaceControl\LogoIconController@update')->name('admin.logoIcon.update');
	Route::get('/interfaceControl/slider/index', 'Admin\InterfaceControl\SliderController@index')->name('admin.slider.index');
	Route::post('/interfaceControl/slider/store', 'Admin\InterfaceControl\SliderController@store')->name('admin.slider.store');
	Route::post('/interfaceControl/slider/delete', 'Admin\InterfaceControl\SliderController@delete')->name('admin.slider.delete');
	Route::get('/interfaceControl/partner/index', 'Admin\InterfaceControl\PartnerController@index')->name('admin.partner.index');
	Route::post('/interfaceControl/partner/store', 'Admin\InterfaceControl\PartnerController@store')->name('admin.partner.store');
	Route::post('/interfaceControl/partner/delete', 'Admin\InterfaceControl\PartnerController@delete')->name('admin.partner.delete');
	Route::get('/interfaceControl/contact/index', 'Admin\InterfaceControl\ContactController@index')->name('admin.contact.index');
	Route::post('/interfaceControl/contact/update', 'Admin\InterfaceControl\ContactController@update')->name('admin.contact.update');
	Route::get('/interfaceControl/support/index', 'Admin\InterfaceControl\SupportController@index')->name('admin.support.index');
	Route::post('/interfaceControl/support/update', 'Admin\InterfaceControl\SupportController@update')->name('admin.support.update');
	Route::get('/interfaceControl/footer/index', 'Admin\InterfaceControl\FooterController@index')->name('admin.footer.index');
	Route::post('/interfaceControl/footer/update', 'Admin\InterfaceControl\FooterController@update')->name('admin.footer.update');


	Route::get('/interfaceControl/logintext/index', 'Admin\InterfaceControl\LogintextController@index')->name('admin.logintext.index');
	Route::post('/interfaceControl/logintext/update', 'Admin\InterfaceControl\LogintextController@update')->name('admin.logintext.update');
	Route::get('/interfaceControl/registertext/index', 'Admin\InterfaceControl\RegistertextController@index')->name('admin.registertext.index');
	Route::post('/interfaceControl/registertext/update', 'Admin\InterfaceControl\RegistertextController@update')->name('admin.registertext.update');



	Route::get('/interfaceControl/social/index', 'Admin\InterfaceControl\SocialController@index')->name('admin.social.index');
	Route::post('/interfaceControl/social/store', 'Admin\InterfaceControl\SocialController@store')->name('admin.social.store');
  	Route::post('/interfaceControl/social/delete', 'Admin\InterfaceControl\SocialController@delete')->name('admin.social.delete');


	// Transactions Route...
	Route::get('/trxlog/{vendorid?}', 'Admin\TrxController@index')->name('admin.trxLog');

	// Ad Routes...
  	Route::get('/Ad/index', 'Admin\AdController@index')->name('admin.ad.index');
	Route::get('/Ad/create', 'Admin\AdController@create')->name('admin.ad.create');
	Route::post('/Ad/store', 'Admin\AdController@store')->name('admin.ad.store');
	Route::get('/Ad/showImage', 'Admin\AdController@showImage')->name('admin.ad.showImage');
	Route::post('/Ad/delete', 'Admin\AdController@delete')->name('admin.ad.delete');

	/* Product Create , Edit, Update, List */
	Route::prefix('product')->group(function (){

		Route::get('/index', 'Admin\ProductController@index')->name('admin.product.index');
		Route::get('/create', 'Admin\ProductController@create')->name('admin.product.create');
		Route::get('/{product_id}/edit', 'Admin\ProductController@edit')->name('admin.product.edit');
		Route::post('/store', 'Admin\ProductController@store')->name('admin.product.store');
		Route::post('/update', 'Admin\ProductController@update')->name('admin.product.update');
		Route::get('/{product_id}/delete', 'Admin\ProductController@destroy')->name('admin.product.delete');
		Route::post('/product_datatable', 'Admin\ProductController@paginate')->name('product_datatable');

		Route::get('/{id}/getimgs', 'Vendor\ProductController@getimgs')->name('admin.product.getimgs');

		Route::post('/getsubcategories', 'Vendor\ProductController@getsubcats')->name('admin.product.getsubcats');

		Route::get('/getattributes', 'Vendor\ProductController@getattributes')->name('admin.product.getattributes');

		Route::get('/import_page', 'Admin\ProductController@import_page')->name('admin.product.import_page');

		Route::get('/download_sample_product_import_file', 'Admin\ProductController@download_sample_product_import_file')->name('download_sample_product_import_file');

		Route::post('/import', 'Admin\ProductController@import')->name('admin.product.import');

	});
	/* Product Create , Edit, Update, List */

	/* Wholeseller / Distributor Create , Edit, Update, List */
  	Route::get('/shopkeeper', 'Admin\ShopkeeperController@index')->name('admin.shopkeeper.index');
	
	Route::get('/shopkeeper/create/{convert_id?}', 'Admin\ShopkeeperController@create')->name('admin.shopkeeper.create');

	Route::post('/shopkeeper/store', 'Admin\ShopkeeperController@store')->name('admin.shopkeeper.store');
	Route::get('/shopkeeper/{user_id}/show', 'Admin\ShopkeeperController@show')->name('admin.shopkeeper.show');
	Route::get('/shopkeeper/{customer_id}/edit', 'Admin\ShopkeeperController@edit')->name('admin.shopkeeper.edit');
	Route::post('/shopkeeper/{shop_id}/update', 'Admin\ShopkeeperController@update')->name('admin.shopkeeper.update');
	Route::get('/shopkeeper/{shop_id}/delete', 'Admin\ShopkeeperController@destroy')->name('admin.shopkeeper.delete');
	Route::post('/shopkeeper/{shop_id}/admin_verify', 'Admin\ShopkeeperController@adminVerify')->name('admin_verify');

	Route::post('/document/verify', 'Admin\ShopkeeperController@documentVerification')->name('admin.shopkeeper.doc_verify');
	Route::get('/shopkeeper/document/{shop_id}/{document_id}/delete', 'Admin\ShopkeeperController@documentDelete')->name('admin.document.delete');

	Route::get('/shopkeeper/{id}/{status_id}/change_status', 'Admin\ShopkeeperController@changeStatus')->name('admin.shopkeeper.status');
	Route::post('/shopkeeper/account_status', 'Admin\ShopkeeperController@changeAccountStatus')->name('admin.shopkeeper.account_status');
	
	Route::get('/shopkeeper/import', 'Admin\ShopkeeperController@import_page')->name('admin.shopkeeper.import_page');
	Route::post('/shopkeeper/import', 'Admin\ShopkeeperController@import')->name('admin.shopkeeper.import');
	
	Route::post('/dealerslist', 'Admin\ShopkeeperController@paginate')->name('datatables_dealers');

	



	/* Transaction Details */
	Route::get('/transactions/{client_id?}/{client_type_id?}', 'Admin\ShopkeeperController@transactions')->name('admin.shopkeeper.transaction');

	Route::post('/transactions/add', 'Admin\ShopkeeperController@addPayment')->name('admin.transaction.add');
	/* Transaction Details */
	
	

	/* Wholeseller / Distributor Create , Edit, Update, List */

	/* JobCardTemplate Create , Edit, Update , Show */
	Route::get('/jobcardtemplate/index', 'Admin\JobCardTemplateController@index')->name('admin.jobcardtemplate.index');
	Route::get('/jobcardtemplate/create', 'Admin\JobCardTemplateController@create')->name('admin.jobcardtemplate.create');
	Route::get('/jobcardtemplate/{jobcardtemplate_id}/edit', 'Admin\JobCardTemplateController@edit')->name('admin.jobcardtemplate.edit');
	Route::get('/jobcardtemplate/{jobcardtemplate_id}/show', 'Admin\JobCardTemplateController@show')->name('admin.jobcardtemplate.show');
	Route::post('/jobcardtemplate/store', 'Admin\JobCardTemplateController@store')->name('admin.jobcardtemplate.store');
	Route::post('/jobcardtemplate/update', 'Admin\JobCardTemplateController@update')->name('admin.jobcardtemplate.update');
	Route::get('/jobcardtemplate/{jobcardtemplate_id}/destroy', 'Admin\JobCardTemplateController@destroy')->name('admin.jobcardtemplate.delete');
	Route::post('/jobcardtemplate/saveform', 'Admin\JobCardTemplateController@saveJobCardForm')->name('admin.jobcardtemplate.save');
	/* JobCardTemplate Create , Edit, Update , Show */



	/*Start*/
	/**
	|---------------------------------------------------------------
	| Zip Codes Routes... (130)
	|---------------------------------------------------------------
	**/	
	Route::get('/zipcodes/index', 'Admin\ZipcodeController@index')->name('admin.zipcodes.index');
	Route::get('/zipcodes/create', 'Admin\ZipcodeController@create')->name('admin.zipcodes.create');
	Route::post('/zipcodes/store', 'Admin\ZipcodeController@store')->name('admin.zipcodes.store');
	Route::get('/zipcodes/{id}/edit', 'Admin\ZipcodeController@edit')->name('admin.zipcodes.edit');
	Route::get('/zipcodes/{id}/delete', 'Admin\ZipcodeController@delete')->name('admin.zipcodes.delete');
	Route::post('/zipcodes/update', 'Admin\ZipcodeController@update')->name('admin.zipcodes.update');

	/**
	|---------------------------------------------------------------
	| Taxes Routes... (130)
	|---------------------------------------------------------------
	**/
	Route::patch('salesman/{id}/visibility', 'Admin\SalesmanController@visibility')->name('admin.salesman.visibility');
	Route::resource('salesman', 'Admin\SalesmanController',[
	    'names' => [
			'index' 	=> 'admin.salesman.index',
			'create' 	=> 'admin.salesman.create',
			'store' 	=> 'admin.salesman.store',
			'show' 		=> 'admin.salesman.show',
			'edit'  	=> 'admin.salesman.edit',
			'destroy'	=> 'admin.salesman.destroy',
		]
	]);
	Route::post('/salesman/{id}/update', 'Admin\SalesmanController@update')->name('admin.salesman.update');

	/*Add Sales Score*/
	Route::post('/addscore', 'Admin\SalesmanController@addSalesScore')->name('admin.salesman.addsalesscore');
	/*Add Sales Score*/

	/*Sales Score List*/
	Route::get('/salesscores', 'Admin\SalesmanController@salesscore')->name('admin.salesman.salesscore');
	/*Sales Score List*/

	
	//Ajax Price
	Route::post('/salesscores/orderidamount','Admin\SalesmanController@checkOrderidAmount')->name('admin.check.orderidamount');
	/*Check Order ID Sales Score  */

	// User Group Management...
	Route::get('/usergroup/index', 'Admin\UserGroupController@index')->name('admin.usergroup.index');
	Route::post('/usergroup/store', 'Admin\UserGroupController@store')->name('admin.usergroup.store');
	Route::post('/usergroup/update', 'Admin\UserGroupController@update')->name('admin.usergroup.update');
	Route::post('/usergrouplist', 'Admin\UserGroupController@paginate')->name('datatable_usergroup');

	





/*Leads Section*/
	
	Route::prefix('leads')->group(function (){
	 	Route::get('/create', 'Admin\crm\LeadController@create')->name('add_lead_page');
	 	Route::post('/create', 'Admin\crm\LeadController@store')->name('post_lead');


	  	Route::get('/index', 'Admin\crm\LeadController@index')->name('leads_list');
	  	Route::post('/list', 'Admin\crm\LeadController@paginate')->name('datatables_leads');

	    Route::get('/show/{lead}', 'Admin\crm\LeadController@show')->name('show_lead_page');
	    Route::get('/edit/{lead}', 'Admin\crm\LeadController@edit')->name('edit_lead_page');
	    Route::patch('/edit/{lead}', 'Admin\crm\LeadController@update')->name('patch_lead');
	 	Route::get('/remove/{lead}', 'Admin\crm\LeadController@destroy')->name('delete_lead');

	 	Route::get('/mark/junk/{lead}', 'Admin\crm\LeadController@mark_as_junk')->name('mark_as_junk')->middleware('perm:leads_edit');
        Route::get('/mark/lost/{lead}', 'Admin\crm\LeadController@mark_as_lost')->name('mark_as_lost')->middleware('perm:leads_edit');
        Route::post('/mark/important/{lead}', 'Admin\crm\LeadController@mark_as_important')->name('mark_as_important')->middleware('perm:leads_edit');

        Route::get('/import', 'Admin\crm\LeadController@import_page')->name('import_lead_page')->middleware('perm:leads_create');
        Route::post('/import', 'Admin\crm\LeadController@import')->name('import_lead')->middleware('perm:leads_create');
        Route::get('/import/download/sample', 'Admin\crm\LeadController@download_sample_lead_import_file')->name('download_sample_lead_import_file');
        Route::post('/note/create/{lead}', 'Admin\crm\LeadController@add_note')->name('lead_add_note')->middleware('perm:[leads_view|leads_view_own],is_multiple');
        Route::post('/log/touch/{lead}', 'Admin\crm\LeadController@log_touch')->name('post_log_touch');
        Route::post('/save/social-link/{lead}', 'Admin\crm\LeadController@save_social_link')->name('post_social_link');
        Route::post('/remove/social-link/{lead}', 'Admin\crm\LeadController@remove_social_link')->name('remove_social_link');
        Route::post('/save/smart-summary/{lead}', 'Admin\crm\LeadController@save_smart_summary')->name('post_smart_summary');
        Route::post('/remove/smart-summary/{lead}', 'Admin\crm\LeadController@remove_smart_summary')->name('remove_smart_summary');
        Route::post('/report/conversion/by/month', 'LeadController@get_report_conversion_by_month_for_graph')->name('get_report_conversion_by_month_for_graph');
        Route::post('/report/salesscores', 'Admin\SalesmanController@get_salesscore_report')->name('get_salesscore_report');


	});

	Route::post('/notifications/unread', 'Admin\AdminController@get_unread_notifications')->name('get_unread_notifications');

 	Route::get('/members/profile/{member}', 'UserController@profile')->name('member_profile');

 	Route::post('/proposal/accept/{proposal}', 'Admin\crm\ProposalController@accept_proposal')->name('accept_proposal');
	Route::post('/proposal/decline/{proposal}', 'Admin\crm\ProposalController@decline_proposal')->name('decline_proposal');
	Route::get('/proposals/view/{proposal?}/{url_slug}', 'Admin\crm\ProposalController@customer_view')->name('proposal_customer_view');
	Route::get('/proposals/download/{proposal?}', 'Admin\crm\ProposalController@download_proposal')->name('download_proposal');

 	
	Route::get('/products/list', 'Admin\crm\ProposalController@search_product')->name('proposal_search_product');

  	Route::post('/customer/unbilled/tasks', 'InvoiceController@get_unbilled_timesheets_and_expenses_by_customer_id')->name('get_unbilled_tasks_by_customer_id');









/*Settings Routs Start*/
 // Settings
    Route::prefix('settings')->group(function (){     

        Route::get('/', 'Admin\crm\SettingsController@general_information')->name('settings_main_page');
        Route::patch('/', 'Admin\crm\SettingsController@update_general_information')->name('patch_company_information');
        Route::get('/email', 'Admin\crm\SettingsController@email')->name('settings_email_page');
        Route::patch('/email', 'Admin\crm\SettingsController@update_email')->name('patch_settings_email');
        Route::post('/email/test', 'Admin\crm\SettingsController@send_test_email')->name('send_test_email');

        Route::get('/email/templates', 'Admin\crm\SettingsController@email_template_home')->name('settings_email_template_home_page');

        Route::get('/email/templates/{template_name}', 'Admin\crm\SettingsController@email_template_page')->name('settings_email_template_page');
        Route::patch('/email/templates', 'Admin\crm\SettingsController@update_email_template')->name('patch_settings_email_template');

        Route::get('/pusher', 'Admin\crm\SettingsController@pusher_page')->name('settings_pusher_page');
        Route::patch('/pusher', 'Admin\crm\SettingsController@update_pusher')->name('patch_settings_pusher');

        Route::get('/invoice', 'Admin\crm\InvoiceController@settings')->name('settings_invoice_page');
        Route::patch('/invoice', 'Admin\crm\InvoiceController@update_settings')->name('patch_settings_invoice');

        Route::get('/estimate', 'Admin\crm\EstimateController@settings')->name('settings_estimate_page');
        Route::patch('/estimate', 'Admin\crm\EstimateController@update_settings')->name('patch_settings_estimate');

        Route::get('/proposal', 'Admin\crm\ProposalController@settings')->name('settings_proposal_page');
        Route::patch('/proposal', 'Admin\crm\ProposalController@update_settings')->name('patch_settings_proposal');

        // Support Configurations
        Route::get('support/configuration', 'TicketController@configuration_page')->name('support_configuration_page');
        Route::patch('support/configuration', 'TicketController@update_configuration_page')->name('patch_support_configuration');

        // Department
        Route::prefix('support/department')->group(function (){
            Route::get('/', 'Admin\crm\DepartmentController@index')->name('department_list');
            Route::post('/list', 'Admin\crm\DepartmentController@paginate')->name('datatables_departments');
            Route::post('/create', 'Admin\crm\DepartmentController@store')->name('post_department');
            Route::post('/edit', 'Admin\crm\DepartmentController@edit')->name('get_information_department');
            Route::post('/update', 'Admin\crm\DepartmentController@update')->name('patch_department');
            Route::get('/remove/{department}', 'Admin\crm\DepartmentController@destroy')->name('delete_department');
            Route::post('/check/imap', 'Admin\crm\DepartmentController@check_imap_connection')->name('check_imap_connection');
        });

         // Ticket Services
        Route::prefix('support/tickets/services')->group(function (){
            Route::get('/', 'Admin\crm\TicketServiceController@index')->name('ticket_service_list');
            Route::post('/list', 'Admin\crm\TicketServiceController@paginate')->name('datatables_ticket_services');
            Route::post('/create', 'Admin\crm\TicketServiceController@store')->name('post_ticket_service');
            Route::post('/edit', 'Admin\crm\TicketServiceController@edit')->name('get_information_ticket_services');
            Route::post('/update', 'Admin\crm\TicketServiceController@update')->name('patch_ticket_service');
            Route::get('/remove/{obj}', 'Admin\crm\TicketServiceController@destroy')->name('delete_ticket_service');
        });


         // Ticket Priorities
        Route::prefix('support/tickets/priorities')->group(function (){
            Route::get('/', 'Admin\crm\TicketPriorityController@index')->name('ticket_priority_list');
            Route::post('/list', 'Admin\crm\TicketPriorityController@paginate')->name('datatables_ticket_priorities');
            Route::post('/create', 'Admin\crm\TicketPriorityController@store')->name('post_ticket_priority');
            Route::post('/edit', 'Admin\crm\TicketPriorityController@edit')->name('get_information_ticket_priorities');
            Route::post('/update', 'Admin\crm\TicketPriorityController@update')->name('patch_ticket_priority');
            Route::get('/remove/{obj}', 'Admin\crm\TicketPriorityController@destroy')->name('delete_ticket_priority');
        });

       


         // Ticket Status
        Route::prefix('support/tickets/statuses')->group(function (){
            Route::get('/', 'Admin\crm\TicketStatusController@index')->name('ticket_status_list');
            Route::post('/list', 'Admin\crm\TicketStatusController@paginate')->name('datatables_ticket_statuses');
            Route::post('/create', 'Admin\crm\TicketStatusController@store')->name('post_ticket_status');
            Route::post('/edit', 'Admin\crm\TicketStatusController@edit')->name('get_information_ticket_status');
            Route::post('/update', 'Admin\crm\TicketStatusController@update')->name('patch_ticket_status');
            Route::get('/remove/{obj}', 'Admin\crm\TicketStatusController@destroy')->name('delete_ticket_status');
        });

         // Ticket Pre Defined Replies
        Route::prefix('support/tickets/predefined-replies')->group(function (){
            Route::get('/', 'PreDefinedReplyController@index')->name('ticket_pre_defined_replies_list');
            Route::post('/list', 'PreDefinedReplyController@paginate')->name('datatables_ticket_pre_difined_replies');
            Route::post('/create', 'PreDefinedReplyController@store')->name('post_ticket_pre_difined_reply');
            Route::post('/edit', 'PreDefinedReplyController@edit')->name('get_information_ticket_pre_difined_reply');
            Route::post('/update', 'PreDefinedReplyController@update')->name('patch_ticket_pre_difined_reply');
            Route::get('/remove/{obj}', 'PreDefinedReplyController@destroy')->name('delete_ticket_pre_difined_reply');
        });

        

        
        // Tags
        Route::prefix('tags')->group(function (){ 

            Route::get('/', 'Admin\crm\TagController@index')->name('tags_list');
            Route::post('/list', 'Admin\crm\TagController@paginate')->name('datatables_tags');
            Route::get('/create', 'Admin\crm\TagController@create')->name('add_tag_page');
            Route::post('/save', 'Admin\crm\TagController@store')->name('post_tag');
            Route::post('/get/information/{tag}', 'Admin\crm\TagController@edit')->name('get_information_tag');
            Route::patch('/edit/{tag}', 'Admin\crm\TagController@update')->name('patch_tag');
            Route::get('/remove/{tag}', 'Admin\crm\TagController@destroy')->name('delete_tag');

        });

         // Lead Status
        Route::prefix('leads')->group(function (){ 
       
        Route::get('/status', 'Admin\crm\LeadStatusController@index')->name('leads_statuses_list');
        Route::post('/status/create', 'Admin\crm\LeadStatusController@store')->name('post_lead_status');
        Route::post('/status/list', 'Admin\crm\LeadStatusController@paginate')->name('datatables_leads_status');
        Route::patch('/status/{status}', 'Admin\crm\LeadStatusController@update')->name('patch_lead_status');
        Route::get('/status/remove/{status}', 'Admin\crm\LeadStatusController@destroy')->name('delete_leads_status');


        // Lead Sources
        Route::get('/sources', 'Admin\crm\LeadSourceController@index')->name('lead_sources_list');     
        Route::post('/lsource/create', 'Admin\crm\LeadSourceController@store')->name('post_lead_source');
        Route::post('/sources/list', 'Admin\crm\LeadSourceController@paginate')->name('datatables_leads_source');
        Route::patch('/source/{source}', 'Admin\crm\LeadSourceController@update')->name('patch_lead_source');
        Route::get('/source/remove/{source}', 'Admin\crm\LeadSourceController@destroy')->name('delete_leads_source');

        });


        // Customer
        Route::prefix('customer')->group(function (){

            // Groups
            Route::get('/groups', 'Admin\crm\CustomerGroupController@index')->name('customer_groups_list');
            Route::post('/groups', 'Admin\crm\CustomerGroupController@paginate')->name('datatables_customer_groups');
            Route::post('/groups/add', 'Admin\crm\CustomerGroupController@store')->name('post_customer_group');
            Route::post('/groups/edit', 'Admin\crm\CustomerGroupController@edit')->name('get_information_customer_group');
            Route::post('/groups/update', 'Admin\crm\CustomerGroupController@update')->name('patch_customer_group');
            Route::get('/groups/remove/{group}', 'Admin\crm\CustomerGroupController@destroy')->name('delete_customer_group');
            Route::get('/transactions/{client_id?}/{client_type_id?}', 'Admin\CustomerController@transactions')->name('admin.customer.transaction');

            // Support Configurations
        Route::get('configuration', 'CustomerController@configuration_page')->name('customer_configuration_page');
        Route::patch('configuration', 'CustomerController@update_configuration_page')->name('customer_support_configuration');

        });


        // Finance
        Route::prefix('finance')->group(function (){


            // Taxes
            Route::prefix('taxes')->group(function (){
                Route::get('/', 'Admin\crm\TaxController@index')->name('tax_list');
                Route::post('/list', 'Admin\crm\TaxController@paginate')->name('datatables_taxes');
                Route::post('/add', 'Admin\crm\TaxController@store')->name('post_tax');
                Route::post('/edit', 'Admin\crm\TaxController@edit')->name('get_information_tax');
                Route::post('/update', 'Admin\crm\TaxController@update')->name('patch_tax');
                Route::get('/remove/{obj}', 'Admin\crm\TaxController@destroy')->name('delete_tax');
            });

            // Expense Category
            Route::get('expense/categories', 'Admin\crm\ExpenseCategoryController@index')->name('expense_categories_list');
            Route::post('expense/categories', 'Admin\crm\ExpenseCategoryController@paginate')->name('datatables_expense_categories');
            Route::post('expense/categories/add', 'Admin\crm\ExpenseCategoryController@store')->name('post_expense_category');
            Route::post('expense/categories/edit', 'Admin\crm\ExpenseCategoryController@edit')->name('get_information_expense_category');
            Route::post('expense/categories/update', 'Admin\crm\ExpenseCategoryController@update')->name('patch_expense_category');
            Route::get('expense/categories/remove/{obj}', 'Admin\crm\ExpenseCategoryController@destroy')->name('delete_expense_category');



            Route::prefix('payment/modes')->group(function (){

                Route::get('offline', 'Admin\crm\PaymentModeController@offline_modes_index')->name('payment_modes_list');
                Route::post('offline', 'Admin\crm\PaymentModeController@offline_modes_paginate')->name('datatables_payment_modes');

                Route::post('offline/create', 'Admin\crm\PaymentModeController@offline_mode_store')->name('post_payment_mode');
                Route::post('offline/get', 'Admin\crm\PaymentModeController@offline_mode_edit')->name('get_information_payment_mode');
                Route::post('offline/update', 'Admin\crm\PaymentModeController@offline_mode_update')->name('patch_payment_mode');
                 Route::get('offline/{mode}', 'Admin\crm\PaymentModeController@offline_mode_destroy')->name('delete_payment_mode');

                 Route::post('offline/change/status', 'Admin\crm\PaymentModeController@offline_change_mode_status')->name('change_mode_status');

         
                Route::get('online', 'Admin\crm\PaymentModeController@online_modes_main')->name('payment_modes_online_page');
                Route::post('online', 'Admin\crm\PaymentModeController@store_online_payment_mode')->name('post_payment_modes_online');
              

            });



            Route::get('currencies', 'Admin\crm\CurrencyController@index')->name('currency_list');
            Route::post('currencies', 'Admin\crm\CurrencyController@paginate')->name('datatables_currencies');
            Route::post('currencies/create', 'Admin\crm\CurrencyController@store')->name('post_currency');
            Route::post('currencies/get', 'Admin\crm\CurrencyController@edit')->name('get_information_currency');
            Route::post('currencies/update', 'Admin\crm\CurrencyController@update')->name('patch_currency');
            Route::get('/currencies/{currency}', 'Admin\crm\CurrencyController@destroy')->name('delete_currency');

             Route::post('/currencies/set/default', 'Admin\crm\CurrencyController@change_default_currency')->name('change_default_currency');




        });

        

        // Team Members
        Route::prefix('team')->group(function (){

            // Roles
            Route::get('/user/roles', 'Admin\crm\RoleController@index')->name('role_list');
            Route::post('/user/roles', 'Admin\crm\RoleController@paginate')->name('datatables_roles');
            Route::get('/user/roles/add', 'Admin\crm\RoleController@create')->name('create_role_page');
            Route::post('/user/roles/add', 'Admin\crm\RoleController@store')->name('post_role');
            Route::get('/user/roles/edit/{role}', 'Admin\crm\RoleController@edit')->name('edit_role_page');
            Route::patch('/user/roles/update/{id}', 'Admin\crm\RoleController@update')->name('patch_role');
            Route::get('/user/roles/remove/{role}', 'Admin\crm\RoleController@destroy')->name('delete_role');

        });

     	// Payment
	    Route::prefix('payments')->group(function (){
	        Route::get('/', 'Admin\crm\PaymentController@index')->name('payment_list');
	        Route::post('/list', 'Admin\crm\PaymentController@paginate')->name('datatables_payment')->middleware('perm:[payments_view|invoices_view|invoices_view_own],is_multiple');
	        Route::get('/create', 'Admin\crm\PaymentController@create')->name('add_payment')->middleware('perm:payments_create');
	        Route::post('/create', 'Admin\crm\PaymentController@store')->name('post_payment')->middleware('perm:payments_create');
	        Route::get('/edit/{payment}', 'Admin\crm\PaymentController@edit')->name('edit_payment_page')->middleware('perm:payments_edit');
	        Route::get('/edit', 'Admin\crm\PaymentController@edit')->name('edit_payment_page_js_link')->middleware('perm:payments_edit');
	        Route::patch('/{payment}', 'Admin\crm\PaymentController@update')->name('patch_payment')->middleware('perm:payments_edit');
	        Route::get('/remove/{payment}', 'Admin\crm\PaymentController@destroy')->name('delete_payment_page')->middleware('perm:payments_delete');
	        Route::get('/receipt/download/pdf/{payment}', 'Admin\crm\PaymentController@download_receipt_pdf')->name('download_receipt');
	        Route::get('/{payment}', 'Admin\crm\PaymentController@show')->name('show_payment_page')->middleware('perm:[payments_view|invoices_view_own],is_multiple');
	        Route::post('/report', 'Admin\crm\PaymentController@report_paginate')->name('report_payment');
	    });


    });
/*Setting Routes End*/


/*Expenses Section */
// Expenses
    Route::prefix('expenses')->group(function (){
    
        Route::get('/', 'Admin\crm\ExpenseController@index')->name('expense_list');
        Route::post('/', 'Admin\crm\ExpenseController@paginate')->name('datatables_expense');
        Route::get('/create', 'Admin\crm\ExpenseController@create')->name('add_expense_page');
        Route::post('/create', 'Admin\crm\ExpenseController@store')->name('post_expense');
        Route::get('/edit/{expense?}', 'Admin\crm\ExpenseController@edit')->name('edit_expense_page');
        Route::patch('edit/{expense}', 'Admin\crm\ExpenseController@update')->name('patch_expense');
        Route::get('/remove/{expense?}', 'Admin\crm\ExpenseController@destroy')->name('delete_expense');

        Route::get('/details', 'Admin\crm\ExpenseController@get_expense_details_ajax')->name('get_expense_details_ajax');
        Route::get('/download/receipt/{filename}', 'Admin\crm\ExpenseController@download_attachment')->name('download_attachment_expense');


        Route::get('?id={expense}', 'ExpenseController@index')->name('show_expense_page');


    });


/*Expenses Section */







	// Teams
    Route::prefix('teams')->group(function (){

        Route::get('/', 'Admin\crm\TeamController@index')->name('teams_list')->middleware('perm:teams_view');
        Route::post('', 'Admin\crm\TeamController@paginate')->name('datatables_teams')->middleware('perm:teams_view');

        Route::post('create', 'Admin\crm\TeamController@store')->name('post_team')->middleware('perm:teams_create');
        Route::post('edit', 'Admin\crm\TeamController@edit')->name('get_information_team')->middleware('perm:teams_edit');
        Route::post('update', 'Admin\crm\TeamController@update')->name('patch_team')->middleware('perm:teams_edit');
        Route::get('remove/{team}', 'Admin\crm\TeamController@destroy')->name('delete_team')->middleware('perm:teams_delete');

        Route::get('/members', 'Admin\crm\UserController@index')->name('team_members_list');
        Route::post('/members', 'Admin\crm\UserController@paginate')->name('datatables_team_members');

        Route::get('/members/create', 'Admin\crm\UserController@create')->name('add_team_member_page');
        Route::post('/members/create', 'Admin\crm\UserController@store')->name('post_team_member')->middleware('perm:team_members_create');

        Route::get('/members/edit/{member}', 'Admin\crm\UserController@edit')->name('edit_team_member_page')->middleware('perm:team_members_edit');
        Route::patch('/members/edit/{member}', 'Admin\crm\UserController@update')->name('patch_team_member')->middleware('perm:team_members_edit');

        Route::post('/members/remove', 'Admin\crm\UserController@destroy')->name('delete_team_member')->middleware('perm:team_members_delete');

        Route::get('/members/suggestion/list', 'Admin\crm\UserController@get_members_for_suggestion_list')->name('get_members_for_suggestion_list');

        Route::get('/members/profile/{member}', 'Admin\crm\UserController@profile')->name('member_profile');
        Route::post('/members/change/photo/{member}', 'Admin\crm\UserController@change_photo')->name('team_member_change_photo');

        Route::get('/members/profile/{member}?group=notifications', 'Admin\crm\UserController@notifications')->name('member_view_all_notifications');
        Route::post('/members/notifications', 'Admin\crm\UserController@notification_paginate')->name('datatable_member_notifications');

        Route::get('/notifications/redirect/{id}', 'Admin\crm\UserController@notification_redirect_url')->name('notification_redirect_url');
        Route::get('/notifications/mark/read/all', 'Admin\crm\UserController@mark_all_notification_as_read')->name('notification_all_mark_as_read');

        Route::get('/members/search', 'Admin\crm\UserController@search_team_member')->name('search_team_member');

        // Team member skills
        Route::get('/skills', 'Admin\crm\SkillController@index')->name('skills_list');     
        Route::post('/skills/create', 'Admin\crm\SkillController@store')->name('post_skills');
        Route::post('/skills/list', 'Admin\crm\SkillController@paginate')->name('datatables_skills');
        Route::patch('/skills/{skill}', 'Admin\crm\SkillController@update')->name('patch_skills');
        Route::get('/skills/remove/{skill}', 'Admin\crm\SkillController@destroy')->name('delete_skills');

        Route::patch('/users/account/update', 'UserController@update_account')->name('update_user_account');



    });


    // Customer
    Route::prefix('customers')->group(function (){

        Route::get('/', 'CustomerController@index')->name('customers_list');

        Route::post('/paginated/list', 'CustomerController@paginate')->name('datatables_customers');

        Route::get('/create/{lead?}', 'CustomerController@create')->name('add_customer_page');
        Route::post('/save', 'CustomerController@store')->name('post_customer');
        Route::get('/edit/{customer}', 'CustomerController@edit')->name('edit_customer_page');

        Route::get('/profile/{customer}', 'CustomerController@profile')->name('view_customer_page');

        Route::patch('/save/{customer}', 'CustomerController@update')->name('patch_customer');
        Route::get('/remove/{customer}', 'CustomerController@destroy')->name('delete_customer');
        Route::get('/search', 'CustomerController@search_customer')->name('search_customer');
        Route::get('/search/contacts', 'CustomerController@search_customer_contact')->name('search_customer_contact');

        Route::post('/contacts', 'CustomerController@all_contacts')->name('datatables_customer_contacts_all');
        Route::get('/contacts', 'CustomerController@contacts_show')->name('customer_contacts');

         Route::get('/profile/{customer}?group=contacts&id={contact_id}', 'CustomerController@profile')->name('edit_customer_contact');

        Route::post('/contacts/fetch/emails/{customer}', 'CustomerController@contact_emails_by_customer_id')->name('get_contact_emails_by_customer_id');

        Route::post('/get/contacts', 'CustomerController@contacts_paginate')->name('datatables_customer_contacts');
        Route::post('/contacts/{customer}/add', 'CustomerController@add_contact')->name('add_customer_contact');
        Route::post('/contacts/{customer}/update', 'CustomerController@update_contact')->name('update_customer_contact');
        Route::get('/contacts/remove/{contact}', 'CustomerController@destroy_contact')->name('delete_customer_contact');
        Route::get('/contacts/?id={contacts}', 'CustomerController@all_contacts')->name('show_customer_contact');


        Route::post('/change/status', 'CustomerController@change_customer_status')->name('change_customer_status');

        Route::post('/contacts/details', 'CustomerController@get_contact_details')->name('get_customer_contact_details_for_displaying');
        Route::post('/contacts/edit', 'CustomerController@edit_contact_details')->name('get_customer_contact_details');
        Route::post('/contacts/change/status', 'CustomerController@change_contact_status')->name('change_customer_contact_status');
        Route::post('/contacts/change/primary', 'CustomerController@change_primary_contact')->name('change_customer_primary_contact');


        Route::get('/import', 'CustomerController@import_page')->name('import_customer_page');
        Route::post('/import', 'CustomerController@import')->name('import_customer');

        Route::get('/import/download/sample', 'CustomerController@download_sample_customer_import_file')->name('download_sample_customer_import_file');

        Route::post('/report', 'CustomerController@report_paginate')->name('customer_report');


    });

	/*Customer Section*/

    Route::prefix('customers')->group(function (){

        Route::get('/', 'Admin\crm\CustomerController@index')->name('customers_list');

        Route::post('/paginated/list', 'Admin\crm\CustomerController@paginate')->name('datatables_customers');

        Route::get('/create/{lead?}', 'Admin\crm\CustomerController@create')->name('add_customer_page');
        Route::post('/save', 'Admin\crm\CustomerController@store')->name('post_customer');
        Route::get('/edit/{customer}', 'Admin\crm\CustomerController@edit')->name('edit_customer_page');

        Route::get('/profile/{customer}', 'Admin\crm\CustomerController@profile')->name('view_customer_page');

        Route::patch('/save/{customer}', 'Admin\crm\CustomerController@update')->name('patch_customer');
        Route::get('/remove/{customer}', 'Admin\crm\CustomerController@destroy')->name('delete_customer');
        Route::get('/search', 'Admin\crm\CustomerController@search_customer')->name('search_customer');
        Route::get('/search/contacts', 'Admin\crm\CustomerController@search_customer_contact')->name('search_customer_contact');

        Route::post('/contacts', 'Admin\crm\CustomerController@all_contacts')->name('datatables_customer_contacts_all');
        Route::get('/contacts', 'Admin\crm\CustomerController@contacts_show')->name('customer_contacts');

         Route::get('/profile/{customer}?group=contacts&id={contact_id}', 'Admin\crm\CustomerController@profile')->name('edit_customer_contact');

        Route::post('/contacts/fetch/emails/{customer}', 'Admin\crm\CustomerController@contact_emails_by_customer_id')->name('get_contact_emails_by_customer_id');

        Route::post('/get/contacts', 'Admin\crm\CustomerController@contacts_paginate')->name('datatables_customer_contacts');
        Route::post('/contacts/{customer}/add', 'Admin\crm\CustomerController@add_contact')->name('add_customer_contact');
        Route::post('/contacts/{customer}/update', 'Admin\crm\CustomerController@update_contact')->name('update_customer_contact');
        Route::get('/contacts/remove/{contact}', 'Admin\crm\CustomerController@destroy_contact')->name('delete_customer_contact');
        Route::get('/contacts/?id={contacts}', 'Admin\crm\CustomerController@all_contacts')->name('show_customer_contact');


        Route::post('/change/status', 'Admin\crm\CustomerController@change_customer_status')->name('change_customer_status');

        Route::post('/contacts/details', 'Admin\crm\CustomerController@get_contact_details')->name('get_customer_contact_details_for_displaying');
        Route::post('/contacts/edit', 'Admin\crm\CustomerController@edit_contact_details')->name('get_customer_contact_details');
        Route::post('/contacts/change/status', 'Admin\crm\CustomerController@change_contact_status')->name('change_customer_contact_status');
        Route::post('/contacts/change/primary', 'Admin\crm\CustomerController@change_primary_contact')->name('change_customer_primary_contact');


        Route::get('/import', 'Admin\crm\CustomerController@import_page')->name('import_customer_page')->middleware('perm:customers_create');
        Route::post('/import', 'Admin\crm\CustomerController@import')->name('import_customer');

        Route::get('/import/download/sample', 'Admin\crm\CustomerController@download_sample_customer_import_file')->name('download_sample_customer_import_file');


        Route::post('/report', 'Admin\crm\CustomerController@report_paginate')->name('customer_report');

    });




		
	





 // Invoice
    Route::prefix('invoices')->group(function (){


	    Route::get('/', 'Admin\crm\InvoiceController@index')->name('invoice_list');
	    Route::post('/list', 'Admin\crm\InvoiceController@paginate')->name('datatables_invoice')->middleware('perm:[invoices_view|invoices_view_own],is_multiple');
	    Route::get('/create', 'Admin\crm\InvoiceController@create')->name('add_invoice_page')->middleware('perm:invoices_create');
	    Route::post('/create', 'Admin\crm\InvoiceController@store')->name('post_invoice')->middleware('perm:invoices_create');
	    Route::get('/edit/{invoice?}', 'Admin\crm\InvoiceController@edit')->name('edit_invoice_page')->middleware('perm:invoices_edit');
	    Route::patch('/edit/{invoice}', 'Admin\crm\InvoiceController@update')->name('patch_invoice')->middleware('perm:invoices_edit');
	    Route::get('/remove/{invoice?}', 'Admin\crm\InvoiceController@destroy')->name('delete_invoice')->middleware('perm:invoices_delete');

	    Route::get('/details', 'Admin\crm\InvoiceController@get_invoice_details_ajax')->name('get_invoice_details_ajax');
	    Route::post('/status/change', 'Admin\crm\InvoiceController@change_status')->name('ajax_change_invoice_status');

	    

	    Route::get('?id={invoice}', 'Admin\crm\InvoiceController@index')->name('show_invoice_page')->middleware('perm:invoices_view');
	        
	    Route::post('/send/email', 'Admin\crm\InvoiceController@send_to_email')->name('invoice_send_to_email');

	    Route::post('/payment/receive', 'Admin\crm\InvoiceController@receive_payment')->name('receive_payment');
	    Route::get('?id={invoice?}', 'Admin\crm\InvoiceController@index')->name('invoice_link');

	    Route::get('/get/payment/', 'Admin\crm\InvoiceController@get_invoice_payments')->name('get_invoice_payments');

	    Route::get('/convert/proposal/{proposal_id?}', 'Admin\crm\InvoiceController@convert_to_invoice_from_proposal')->name('convert_to_invoice_from_proposal')->middleware('perm:invoices_create');
	    Route::get('/convert/estimate/{estimate_id?}', 'Admin\crm\InvoiceController@convert_to_invoice_from_estimate')->name('convert_to_invoice_from_estimate')->middleware('perm:invoices_create');

	    Route::get('/convert/expense/{expense_id?}', 'Admin\crm\InvoiceController@convert_to_invoice_from_expense')->name('convert_to_invoice_from_expense');

	    Route::post('/customer/unbilled/tasks', 'Admin\crm\InvoiceController@get_unbilled_timesheets_and_expenses_by_customer_id')->name('get_unbilled_tasks_by_customer_id');

	    Route::post('/report', 'Admin\crm\InvoiceController@report_paginate')->name('report_invoice');
	    Route::post('/item/report', 'Admin\crm\InvoiceController@report_item_paginate')->name('report_item');

	    Route::post('/update/recurring/details', 'Admin\crm\InvoiceController@update_recurring_invoice_setting')->name('update_recurring_invoice_setting');


	    Route::post('/create/for/project/{project}', 'Admin\crm\InvoiceController@create_invoice_for_a_project')->name('create_invoice_for_a_project');

	    Route::post('/children', 'Admin\crm\InvoiceController@get_child_invoices')->name('get_child_invoices');

	    Route::get('/recurring', 'Admin\crm\InvoiceController@recurring_invoices')->name('recurring_invoices_list');
	    Route::post('/recurring', 'Admin\crm\InvoiceController@paginate_recurring_invoices')->name('datatable_recurring_invoices');
   
    
    });




    // Credit Note
    Route::prefix('credit-notes')->group(function (){
    
	    Route::get('/', 'CreditNoteController@index')->name('credit_note_list');
	    Route::post('/list', 'CreditNoteController@paginate')->name('datatables_credit_note')->middleware('perm:[credit_notes_view|credit_notes_view_own],is_multiple');
	    Route::get('/create', 'CreditNoteController@create')->name('add_credit_note_page')->middleware('perm:credit_notes_create');
	    Route::post('/create', 'CreditNoteController@store')->name('post_credit_note')->middleware('perm:credit_notes_create');
	    Route::get('/edit/{credit_note?}', 'CreditNoteController@edit')->name('edit_credit_note_page')->middleware('perm:credit_notes_edit');
	    Route::patch('/edit/{credit_note}', 'CreditNoteController@update')->name('patch_credit_note')->middleware('perm:credit_notes_edit');
	    Route::get('/remove/{credit_note?}', 'CreditNoteController@destroy')->name('delete_credit_note')->middleware('perm:credit_notes_delete');

	    Route::get('/details', 'CreditNoteController@get_credit_note_details_ajax')->name('get_credit_note_details_ajax');
	    Route::post('/status/change', 'CreditNoteController@change_status')->name('ajax_change_credit_note_status');

	    Route::post('/send/email', 'CreditNoteController@send_to_email')->name('credit_note_send_to_email');

	    Route::get('/?id={credit_note}', 'CreditNoteController@index')->name('show_credit_note_page');


	    
	    Route::post('/by/customer', 'CreditNoteController@get_available_credit_notes_by_customer_id')->name('get_available_credit_notes_by_customer_id');

		Route::post('/apply', 'CreditNoteController@apply_credit_to_invoice')->name('apply_credit_to_invoice');

	    Route::post('/invoices', 'CreditNoteController@get_invoices_applied_to')->name('credit_note_get_invoices');
    
    });


    //Payment

    Route::prefix('payments')->group(function (){

	    Route::get('/', 'Admin\crm\PaymentController@index')->name('payment_list');
	    Route::post('/list', 'Admin\crm\PaymentController@paginate')->name('datatables_payment')->middleware('perm:[payments_view|invoices_view|invoices_view_own],is_multiple');

	    Route::get('/create', 'Admin\crm\PaymentController@create')->name('add_payment')->middleware('perm:payments_create');
	    Route::post('/create', 'Admin\crm\PaymentController@store')->name('post_payment')->middleware('perm:payments_create');
	    Route::get('/edit/{payment}', 'Admin\crm\PaymentController@edit')->name('edit_payment_page')->middleware('perm:payments_edit');
	    Route::get('/edit', 'Admin\crm\PaymentController@edit')->name('edit_payment_page_js_link')->middleware('perm:payments_edit');

	    Route::patch('/{payment}', 'Admin\crm\PaymentController@update')->name('patch_payment')->middleware('perm:payments_edit');
	    Route::get('/remove/{payment}', 'Admin\crm\PaymentController@destroy')->name('delete_payment_page')->middleware('perm:payments_delete');

	    Route::get('/receipt/download/pdf/{payment}', 'Admin\crm\PaymentController@download_receipt_pdf')->name('download_receipt');

	    Route::get('/{payment}', 'Admin\crm\PaymentController@show')->name('show_payment_page')->middleware('perm:[payments_view|invoices_view_own],is_multiple');

	    Route::post('/report', 'Admin\crm\PaymentController@report_paginate')->name('report_payment');
    
    });





  // Projects Section

    Route::prefix('projects')->group(function (){

        Route::get('/', 'ProjectsController@index')->name('projects_list');
        Route::post('/paginated/list', 'ProjectsController@paginate')->name('datatables_projects');
        Route::get('/create', 'ProjectsController@create')->name('add_projects')->middleware('perm:projects_create');
        Route::post('/save', 'ProjectsController@store')->name('post_project')->middleware('perm:projects_create');

        Route::get('/edit/{project}', 'ProjectsController@edit')->name('edit_project_page')->middleware('perm:projects_edit');
        Route::patch('/save/{project}', 'ProjectsController@update')->name('patch_project')->middleware('perm:projects_edit');

        Route::get('/remove/{project}', 'ProjectsController@destroy')->name('delete_project')->middleware('perm:projects_delete');
        Route::get('/view/{project}', 'ProjectsController@show')->name('show_project_page');
        


        Route::get('/milestones', 'ProjectsController@get_milestones_by_project_id')->name('get_milestones_by_project_id');
        Route::post('/milestones/{project}', 'MilestoneController@paginate')->name('get_project_milestones');
        Route::post('/change/status/{project}', 'ProjectsController@change_status')->name('change_project_status');


        Route::post('/attachment/create/{project}', 'ProjectsController@add_attachment')->name('project_add_attachment');
        Route::post('/attachments/get/{project}', 'ProjectsController@get_attachments')->name('project_attachment_datatable');


        Route::get('/details/by/customer', 'ProjectsController@get_project_by_customer_id')->name('get_project_by_customer_id');
        Route::get('/details/by/customer/contact', 'ProjectsController@get_project_by_customer_contact_id')->name('get_project_by_customer_contact_id');



        // Milestone
        
        Route::post('/milestone/add', 'MilestoneController@store')->name('add_project_milestone');
        Route::post('/milestone/edit', 'MilestoneController@edit')->name('get_milestone_information');
        Route::post('/milestone/save', 'MilestoneController@update')->name('update_project_milestone');
        Route::get('/project/milestone/{milestone}', 'MilestoneController@destroy')->name('delete_project_milestone');


        Route::get('/invoice/modal/{project}', 'ProjectsController@invoice_project_modal_content')->name('get_invoice_project_modal_content');

    });

 // End Projects Section

 // Proposal
    Route::prefix('proposals')->group(function (){

        Route::get('/', 'Admin\crm\ProposalController@index')->name('proposal_list');
        Route::post('/list', 'Admin\crm\ProposalController@paginate')->name('datatables_proposal');

        Route::get('/create', 'Admin\crm\ProposalController@create')->name('add_proposal_page');
        Route::post('/create', 'Admin\crm\ProposalController@store')->name('post_proposal');

        Route::get('/edit/{proposal?}', 'Admin\crm\ProposalController@edit')->name('edit_proposal_page');
        Route::patch('/edit/{proposal}', 'Admin\crm\ProposalController@update')->name('patch_proposal');
        Route::get('/remove/{proposal?}', 'Admin\crm\ProposalController@destroy')->name('delete_proposal');

        Route::get('/?id={proposal}', 'Admin\crm\ProposalController@index')->name('show_proposal_page');


        Route::get('/related', 'Admin\crm\ProposalController@related_component')->name('related_component');
        Route::get('/products/list', 'Admin\crm\ProposalController@search_product')->name('proposal_search_product');
        Route::get('/details', 'Admin\crm\ProposalController@get_proposal_details_ajax')->name('get_proposal_details_ajax');
        Route::get('/items', 'Admin\crm\ProposalController@get_proposal_items_ajax')->name('get_proposal_items_ajax');
        
        Route::post('/save/content', 'Admin\crm\ProposalController@save_proposal_content')->name('save_proposal_content');

        Route::post('/status/change', 'Admin\crm\ProposalController@change_status')->name('ajax_change_proposal_status');

        Route::post('/send/email', 'Admin\crm\ProposalController@send_to_email')->name('proposal_send_to_email');


    });

    


 // Estimate
    Route::prefix('estimates')->group(function (){

	    Route::get('/', 'Admin\crm\EstimateController@index')->name('estimate_list');
	    Route::post('/list', 'Admin\crm\EstimateController@paginate')->name('datatables_estimate');
	    Route::get('/create', 'Admin\crm\EstimateController@create')->name('add_estimate_page');
	    Route::post('/create', 'Admin\crm\EstimateController@store')->name('post_estimate');
	    Route::get('/edit/{estimate?}', 'Admin\crm\EstimateController@edit')->name('edit_estimate_page');
	    Route::patch('/edit/{estimate}', 'Admin\crm\EstimateController@update')->name('patch_estimate');
	    Route::get('/remove/{estimate?}', 'Admin\crm\EstimateController@destroy')->name('delete_estimate');
	    Route::get('/details', 'Admin\crm\EstimateController@get_estimate_details_ajax')->name('get_estimate_details_ajax');
	    Route::post('/status/change', 'Admin\crm\EstimateController@change_status')->name('ajax_change_estimate_status');
	    Route::post('/send/email', 'Admin\crm\EstimateController@send_to_email')->name('estimate_send_to_email');
	    Route::get('/?id={estimate}', 'Admin\crm\EstimateController@index')->name('show_estimate_page');
	    Route::get('/convert/proposal/{proposal_id?}', 'Admin\crm\EstimateController@convert_to_estimate_from_proposal')->name('convert_to_estimate_from_proposal');

    });


   //Ticket
    //Route::group(['prefix' => 'tickets', 'middleware' => ['feature_enabled:support'] ] , function (){ 
   Route::group(['prefix' => 'tickets'] , function (){         
   

        Route::get('/', 'Admin\crm\TicketController@index')->name('ticket_list')->middleware('perm:[tickets_view|tickets_view_own|tickets_create|tickets_edit],is_multiple');
        Route::post('/list', 'Admin\crm\TicketController@paginate')->name('datatables_tickets');
        Route::get('/create', 'Admin\crm\TicketController@create')->name('add_ticket_page')->middleware('perm:tickets_create');
        Route::post('/create', 'Admin\crm\TicketController@store')->name('post_ticket')->middleware('perm:tickets_create');
        Route::get('/view/{ticket}?group=settings', 'Admin\crm\TicketController@show')->name('edit_ticket_page')->middleware('perm:[tickets_view|tickets_view_own|tickets_edit],is_multiple');
        Route::patch('/edit/{ticket}', 'Admin\crm\TicketController@update')->name('patch_ticket')->middleware('perm:tickets_edit');
        Route::get('/remove/{ticket}', 'Admin\crm\TicketController@destroy')->name('delete_ticket')->middleware('perm:tickets_delete');

        Route::get('/view/{ticket}', 'Admin\crm\TicketController@show')->name('show_ticket_page');
        Route::post('/predefined-reply', 'Admin\crm\TicketController@get_predefined_reply')->name('get_ticket_predefined_reply');
        Route::post('/reply/{ticket}', 'Admin\crm\TicketController@add_reply')->name('ticket_add_reply');
        Route::post('/change/status', 'Admin\crm\TicketController@change_status')->name('ticket_change_status');
        Route::post('/note/create/{ticket}', 'Admin\crm\TicketController@add_note')->name('ticket_add_note'); 
    
    });
		
     // Task
    Route::prefix('tasks')->group(function (){
        
        Route::get('/', 'TaskController@index')->name('task_list');
        Route::get('/kanban', 'TaskController@kanban_view')->name('task_canban_view');

        Route::post('/upload/attachment', 'TaskController@upload_attachment')->name('upload_task_attachment');
        
        Route::post('/list', 'TaskController@paginate')->name('datatables_tasks');
        Route::get('/create', 'TaskController@create')->name('add_task_page')->middleware('perm:tasks_create');
        Route::post('/create', 'TaskController@store')->name('post_task')->middleware('perm:tasks_create');

        Route::get('/edit/{task}', 'TaskController@edit')->name('edit_task_page')->middleware('perm:tasks_edit');
        Route::patch('/edit/{task?}', 'TaskController@update')->name('patch_task')->middleware('perm:tasks_edit');
        Route::get('/remove/{task}', 'TaskController@destroy')->name('delete_task')->middleware('perm:tasks_delete');

        Route::get('/show/{task}', 'TaskController@show')->name('show_task_page');
        Route::post('/comment/{task}', 'TaskController@post_task_comment')->name('post_task_comment');
        Route::patch('/comment/{task}/{comment}', 'TaskController@update_task_comment')->name('patch_task_comment');

        Route::get('/show/{task}/change/status/{status_id}', 'TaskController@change_status')->name('task_change_status');
        Route::post('/change/status', 'TaskController@change_status')->name('task_change_status_ajax');


        Route::get('/related', 'TaskController@task_related')->name('task_related');
        Route::get('/parent/list', 'TaskController@parent_tasks')->name('get_parent_tasks');


        Route::get('/component', 'TaskController@tasks_by_component_id')->name('tasks_by_component_id');

        Route::post('/component/{component}/{id}', 'TaskController@tasks_by_component_id_paginate')->name('datatable_tasks_by_component_id');

        Route::post('/comments/{task}', 'TaskController@comments')->name('datatable_tasks_comments');

        Route::get('/comment/delete/{comment}', 'CommentController@destroy')->name('delete_comment');

        Route::post('/update/milestone', 'TaskController@update_task_milestone')->name('task_update_milestone');

        Route::post('/assign/{task}', 'TaskController@assign_task')->name('assign_task');

        Route::get('/show/{task}?comment={comment_id}', 'TaskController@show')->name('show_task_comment');


        Route::get('/convert/ticket/{ticket_comment_thread_id}', 'TaskController@convert_ticket_to_task')->name('convert_ticket_to_task');
   	
   		/*Tasks Management Section Or To Do List*/
		Route::get('', 'Admin\TasksController@index')->name('admin.tasks.index');
		Route::get('/create', 'Admin\TasksController@create')->name('admin.tasks.create');
		//Route::get('/{id}/show', 'Admin\TasksController@show')->name('admin.tasks.show');
		Route::get('/{id}/{client_type_id}/{client_id}/show', 'Admin\TasksController@show')->name('admin.tasks.show');
		Route::get('/{id}/edit', 'Admin\TasksController@edit')->name('admin.tasks.edit');
		Route::post('/store', 'Admin\TasksController@store')->name('admin.tasks.store');
		Route::post('/update', 'Admin\TasksController@update')->name('admin.tasks.update');

		Route::get('/salesman/', 'Admin\TasksController@salesmanList')->name('admin.tasks.salesmanlist');
		Route::get('/salesman/{id}', 'Admin\TasksController@salesmanTasklist')->name('admin.salesmans.task');
    	Route::get('/arrival/{salesman_id?}/', 'Admin\TasksController@myarrivals')->name('admin.tasks.arrivals');
    	Route::get('/json', 'Admin\TasksController@jsonView')->name('admin.json');



			/*Tasks Management Section Or To Do List END*/
        
    });



	// -------------------------------- Publicly Accesable Routes (Anyone with the URL can view) ------------------------------------------

	Route::post('/proposal/accept/{proposal}', 'Admin\crm\ProposalController@accept_proposal')->name('accept_proposal');
	Route::post('/proposal/decline/{proposal}', 'Admin\crm\ProposalController@decline_proposal')->name('decline_proposal');
	Route::get('/proposals/view/{proposal?}/{url_slug}', 'Admin\crm\ProposalController@customer_view')->name('proposal_customer_view');
	Route::get('/proposals/download/{proposal?}', 'Admin\crm\ProposalController@download_proposal')->name('download_proposal');
	Route::get('/attachment/download/{encrypted_url}', 'Admin\crm\AttachmentController@download')->name('attachment_download_link');
	Route::get('/invoice/view/{invoice?}/{url_slug}', 'Admin\crm\InvoiceController@customer_view')->name('invoice_customer_view');
	Route::get('/invoices/download/{invoice?}', 'Admin\crm\InvoiceController@download_invoice')->name('download_invoice');
	Route::get('/invoices/make/payment/', 'Admin\crm\InvoiceController@process_payment_request')->name('process_payment_request');

	Route::get('/process/payment', 'Admin\crm\InvoiceController@process_payment_request')->name('process_payment_request');

	Route::get('/estimate/view/{estimate}/{url_slug}', 'Admin\crm\EstimateController@customer_view')->name('estimate_customer_view');
	Route::get('/estimates/download/{estimate?}', 'Admin\crm\EstimateController@download_estimate')->name('download_estimate');
	Route::post('/estimate/accept/{estimate}', 'Admin\crm\EstimateController@accept_estimate')->name('accept_estimate');
	Route::post('/estimate/decline/{estimate}', 'Admin\crm\EstimateController@decline_estimate')->name('decline_estimate');
	Route::get('/credit-note/download/{credit_note}', 'Admin\crm\CreditNoteController@download_credit_note')->name('download_credit_note');

	Route::post('/invoices/payment/process/stripe', 'Admin\crm\InvoiceController@process_stripe_payment')->name('process_stripe_payment');

	// -------------------------------- Publicly Accessible Routes ------------------------------------------

	// Reminders
    Route::prefix('reminders')->group(function (){
        Route::get('/', 'Admin\crm\ReminderController@index')->name('reminder_list');
        Route::post('/list', 'Admin\crm\ReminderController@paginate')->name('datatables_reminders');
        Route::post('/create', 'Admin\crm\ReminderController@store')->name('post_reminder');
        Route::post('/information/', 'Admin\crm\ReminderController@edit')->name('get_reminder_information');
        Route::post('/edit/{reminder?}', 'Admin\crm\ReminderController@update')->name('patch_reminder');
        Route::get('/remove/{reminder}', 'Admin\crm\ReminderController@destroy')->name('delete_reminder');
    });





/**
|---------------------------------------------------------------
| Country Management Section    E.ID 106 
|---------------------------------------------------------------
**/
	/*Route::resource('countries', 'Admin\CountryController',[
	   'names' => [ 
	   		'index' => 'countries.index',
	   		'store' => 'countries.store',
	   		'create'=> 'countries.create',
	   		'show'=> 'countries.show',
	   		'edit'  => 'countries.edit',
	   		'update'=> 'countries.update',
	   		'destroy'=> 'countries.destroy',
	      ]
	]);*/

	Route::get('countries', 'Admin\CountryController@index')->name('countries.index');
	Route::post('countries/store', 'Admin\CountryController@store')->name('countries.store');
	Route::post('countries/update', 'Admin\CountryController@update')->name('countries.update');
	Route::get('countries/{id?}/edit', 'Admin\CountryController@index')->name('countries.edit');

	Route::resource('area', 'Admin\AreaController',[
	   'names' => [ 
	   		'index' 	=> 'area.index',
	   		'store' 	=> 'area.store',
	   		'create'	=> 'area.create',
	   		'show'		=> 'area.show',
	   		'edit'  	=> 'area.edit',
	   		'update'	=> 'area.update',
	      ]
	]);
	
    Route::get('/delete/{id}', 'Admin\AreaController@destroy')->name('area.delete');

    Route::get('/import_page', 'Admin\AreaController@import_page')->name('area.import_page');
	Route::get('/import/download/area/', 'Admin\AreaController@download_sample_area_import_file')->name('download_sample_area_import_file');

    Route::post('/', 'Admin\AreaController@import')->name('area.import');



    /*Update Country Status Status(Active/Inactive)*/
    Route::get('/updatecountrystatus/{id}/{status}', 'Admin\CountryController@updatestatus');

/*=====  End of  Country Management Section     ======*/

	/**
	|---------------------------------------------------------------
	| States Routes... (130)
	|---------------------------------------------------------------
	**/
	Route::patch('states/{id}/visibility', 'Admin\StateController@visibility')->name('states.visibility');
	Route::resource('states', 'Admin\StateController',[
	    'names' => [
			'index' => 'states.index',
			'create' => 'states.create',
			'store' => 'states.store',
			'show' => 'states.show',
			'edit'  => 'states.edit',
			'update' => 'states.update',
			'destroy'=> 'states.destroy',
		]
	]);

 	/*Country Dropdown List*/
    Route::get('/countrydropdown', 'Admin\StateController@CountryDropdownList');
    /*Edit country Dropdown List*/
    Route::get('/editcountrydropdown/{token}', 'Admin\StateController@EditCountryDropdownList');
    /*States Active/ Inactive*/
    Route::get('/updatestatestatus/{id}/{status}', 'Admin\StateController@updatestatus');

    Route::get('/citydropdown', 'Admin\CityController@CityDropdownList');
    /*Edit Cities Dropdown List*/
    Route::get('/editcitydropdown/{token}', 'Admin\CityController@EditCityDropdownList');
     /* City Active/Inactive*/
	 Route::get('/updatecitystatus/{id}/{status}', 'Admin\CityController@UpdateStatus');


    /**
	|---------------------------------------------------------------
	| Cities Routes... (130)
	|---------------------------------------------------------------
	**/
	Route::patch('cities/{id}/visibility', 'Admin\CityController@visibility')->name('cities.visibility');
	Route::resource('cities', 'Admin\CityController',[
		    'names' => [
				'index' => 'cities.index',
				'create' => 'cities.create',
				'store' => 'cities.store',
				'show' => 'cities.show',
				'edit'  => 'cities.edit',
				'update' => 'cities.update',
				'destroy'=> 'cities.destroy',
		]
	]);



    /**
	|---------------------------------------------------------------
	| Payment Collection... (130)
	|---------------------------------------------------------------
	**/
	Route::patch('collection/{id}/visibility', 'Admin\PaymentCollectionController@visibility')->name('collection.visibility');

	Route::resource('collection', 'Admin\PaymentCollectionController',[
		    'names' => [
				'index' => 'collection.index',
				'create' => 'collection.create',
				'store' => 'collection.store',
				'show' => 'collection.show',
				'edit'  => 'collection.edit',
				'destroy'=> 'collection.destroy',
		]
	]);
	/* Import Collection Route */
	Route::get('collection/add/{create_id}', 'Admin\PaymentCollectionController@collectionForward')->name('collection.forward');
	
	Route::get('import', 'Admin\PaymentCollectionController@import_page')->name('payment_collection_import_page');

	Route::post('collection/import', 'Admin\PaymentCollectionController@import')->name('payment_collection_import');

	Route::get('/import/download/sample', 'Admin\PaymentCollectionController@download_sample_collection_import_file')->name('download_sample_collection_import_file');

	/* Import Collection Route */

	Route::post('/paginate', 'Admin\PaymentCollectionController@paginate')->name('datatable_payment_collection');
	
	/* Payment Collection  Active/Inactive*/
	Route::get('/updatepaymentstatus/{id}/{status}', 'Admin\PaymentCollectionController@UpdateStatus');

	Route::post('/collection/update/{id}/', 'Admin\PaymentCollectionController@update')->name('collection.update');

	  /* Payment Collection  Delete Section*/
	Route::get('/destroy/{id}', 'Admin\PaymentCollectionController@destroy');
	 
	Route::post('{collection_id?}/paymentdescription/{thread_id?}', 'Admin\PaymentCollectionController@createPaymentThread')->name('admin.payment.adddescription');

	/*Datatable*/
	Route::post('/attributelist', 'Admin\ProductattrController@paginate')->name('datatable_attribute');
	Route::post('/brandlist', 'Admin\OptionController@paginate')->name('datatable_option');
	Route::post('/couponlist', 'Admin\CouponController@paginate')->name('datatable_coupon');
	Route::post('/categorylist', 'Admin\CategoryController@paginate')->name('datatable_category');
	Route::post('/subcategorylist', 'Admin\SubcategoryController@paginate')->name('datatable_subcategory');
	/*Datatable*/


});// Admin Routes End




/**
|---------------------------------------------------------------
| Global Route For User And Admin Section ..........
|---------------------------------------------------------------
 **/
	/**
	|---------------------------------------------------------------
	| Get Location Route ..........
	|---------------------------------------------------------------
	 **/
	//Route::post('get/land/units', 'GetLocationController@units')->name('get.land.units');
	Route::post('get/states', 'GetLocationController@states')->name('get.states');
	Route::post('get/cities', 'GetLocationController@cities')->name('get.cities');
	Route::post('get/countries', 'GetLocationController@countries')->name('get.countries');
	Route::post('get/zipcode', 'GetLocationController@zipcode')->name('get.zipcode');
	Route::post('get/shop', 'GetLocationController@shop')->name('get.shop');

	Route::post('/location/getlatlong', 'GetLocationController@getLatLong')->name('location.getlatlong');

	/*Salesman List*/
	Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin','set_user_permission']], function () {
		Route::post('get/salesman', 'GetLocationController@getSalesman')->name('get.salesman');
	});
	Route::post('get/salesman/lavel/one', 'GetLocationController@getSalesmanLavel')->name('get.salesman.lavelone');
	Route::post('get/salesmandata', 'GetLocationController@getSalesmanData')->name('get.salesmandata');
	Route::post('get/usergroup', 'GetLocationController@getUsergroup')->name('get.usergroup');
/*Check Order ID Sales Score  */
	Route::get('/salesscores/orderid','Admin\SalesmanController@checkOrderid')->name('admin.check.orderid');
	
	Route::get('permission_denied','Admin\AdminController@permission_denied')->name('permission_denied');

	