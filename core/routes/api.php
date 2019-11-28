<?php
use Illuminate\Http\Request;
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

/* Customer Login & Registration*/
Route::group(['prefix'=>'shopkeeper'], function(){
	Route::post('login', 'API\ShopkeeperController@login');
	Route::post('register', 'API\ShopkeeperController@register');
	Route::post('forgetpassword', 'API\ShopkeeperController@forgotPassword');
});

/* Country Dropdown List*/
Route::post('countrylist', 'API\LocationController@countryDropdown');

/* Country State List*/
Route::get('statelist', 'API\LocationController@statesDropdown');

/* City Dropdown List*/
Route::get('citylist', 'API\LocationController@citiesDropdown');

/* Zipcode Dropdown List*/
Route::get('zipcodelist', 'API\LocationController@zipcodeDropdown');
Route::get('/shopkeeper/pushnotification', 'API\OrderController@duplicatenotification');


/* Verification */
Route::get('/shopkeeper/emailverification', 'API\ShopkeeperController@emailVerification');
Route::get('/shopkeeper/phoneverification', 'API\ShopkeeperController@phoneVerification');

/*Instamojo Credentials & Success URL*/
Route::post('instamojoCredentials', 'API\ShopkeeperController@instamojoCredentials');
Route::get('pay-success/{order_id}', 'API\ShopkeeperController@success');
Route::get('pay', 'API\ShopkeeperController@pay');
/*Instamojo Success URL*/

Route::get('paytmcredentials','API\ProductController@paytmCredentials');
Route::group(['prefix'=>'shopkeeper','middleware' => 'auth:shopkeeper-api'], function(){
	/* User Detail and Update Detail and Change Password */
	Route::get('details', 'API\ShopkeeperController@details');
	Route::post('changepassword', 'API\ShopkeeperController@updatePassword');

	/* Category - SubCategory - Attributes */
	Route::get('categories/{category_id?}', 'API\ProductController@categories');
	Route::get('subcategories/{category_id?}/{subcategory_id?}', 'API\ProductController@subcategories');
	Route::get('products/{category_id?}/{subcategory_id?}/{product_id?}', 'API\ProductController@products');
	Route::get('productdetail/{product_id?}', 'API\ProductController@productDetail');
	/* Category - SubCategory - Attributes */

	/* Orders */
	Route::post('placeorder', 'API\OrderController@placeOrder');
	Route::post('cartdetail', 'API\OrderController@cartDetail');
	Route::get('orderhistory/{order_id?}','API\OrderController@orderHistory');
	Route::post('cancelorder', 'API\OrderController@orderCancel');

	/* Orders */

	/* Notification */
	Route::get('notificationlist', 'API\OrderController@NotificationList');
	/* Notification */

	/* Qr - Code */
	Route::get('myqrcode', 'API\ShopkeeperController@myQrcode');
	/* Qr - Code */


	/* Shopkeeper Order History && Details */
	// Route::get('paymentdetail/{client_id?}', 'API\SalesmanController@paymentDetail');
	// Route::post('transactions/{shopkeeper_id?}', 'API\SalesmanController@paymentDetailList');
	/* Shopkeeper Order History && Details */


});

/* Sales Man Login - Register */
Route::group(['prefix'=>'salesman'], function(){
	Route::post('salesmanlogin', 'API\SalesmanController@login');
	/*Salesman Offer and Coupons List*/
	Route::post('couponslist', 'API\SalesmanController@getValidCoupons');
	/*Order Amount according to Order ID*/
	Route::post('orderprice', 'API\OrderController@orderAmount');
	/*Payment Mode List*/
	Route::get('paymentmodelist', 'API\CollectPaymentController@paymentMode');
	/*Test Firebase Notification*/
	Route::get('/salesman/pushnotification', 'API\CollectPaymentController@duplicatenotification');
});

Route::group(['prefix'=>'salesman','middleware' => 'auth:salesman-api'], function(){
	Route::post('salesmanprofile', 'API\SalesmanController@details');

	/* Shopkeeper Order History && Details */
	Route::get('paymentdetail/{client_id?}/{client_type_id?}', 'API\SalesmanController@paymentDetail');
	Route::post('transactions/{shopkeeper_id?}/{client_type_id?}', 'API\SalesmanController@paymentDetailList');
	/* Shopkeeper Order History && Details */

	/* Shopkeeper Registeration */
	// Route::post('shopkeeper/registration', 'API\SalesmanController@salesmanRegistration');
	/* Shopkeeper Registeration */

	Route::post('changepassword', 'API\SalesmanController@updatePassword');

	/* Documents Upload and update */
	Route::post('uploadimage', 'API\SalesmanController@uploadImage');
	/* Documents Upload and update */

	/* Shopkeeper List */
	Route::post('shopkeepers/{shopkeeper_id?}', 'API\SalesmanController@shopkeepersList');
	/* Shopkeeper List */

	/* Shopkeeper Order History && Details */
	Route::post('orderhistory/{shopkeeper_id?}/{order_id?}', 'API\SalesmanController@OrderHistory');
	/* Shopkeeper Order History && Details */


	/* To do Task Schedule Management */
	Route::group(['prefix'=>'tasks'], function(){
		Route::post('index', 'API\TaskController@index');
		Route::post('store', 'API\TaskController@store');
		Route::post('update', 'API\TaskController@update');
		Route::get('usertype', 'API\ShopkeeperController@userType');
		Route::get('task_status', 'API\TaskController@taskStatus');
		Route::post('updatestatus', 'API\TaskController@updateTaskStatus');
		Route::post('update_arrival/{shopkeeper_id?}', 'API\TaskController@taskArrival');		
	});
	/* To Do Task Schedule Management */

	/* Expense Management */
	Route::group(['prefix'=>'expense'], function(){
		Route::get('categories', 'API\ExpenseController@expenseCategoryList');
		Route::post('store', 'API\ExpenseController@store');
		Route::post('list', 'API\ExpenseController@index');
		Route::get('totalmonthlyexpenses', 'API\ExpenseController@monthlyExpense');
		
	});
	/* Expense Management */





	/*Lead Management*/
	Route::group(['prefix'=>'leads'], function(){
		Route::post('list', 'API\LeadController@index');
	});
	/*Lead Management*/

	/*Customer Management*/
	Route::group(['prefix'=>'customer'], function(){
		Route::post('list', 'API\CustomerController@index');
	});
	/*Customer Management*/

	/*Customer Management*/
	Route::group(['prefix'=>'salesmanscore'], function(){
		Route::post('add', 'API\SalesmanController@addSalesScore');
		Route::post('list', 'API\SalesmanController@salesScore');
	});
	/*Customer Management*/

	/*Collect Payment Management*/
	Route::group(['prefix'=>'collectpayment'], function(){
		Route::post('addpayment', 'API\CollectPaymentController@AddPayment');
	});
	/*Collect Payment Management*/

	/* Notification [Salesman] */
	Route::post('notificationlist', 'API\SalesmanController@NotificationList');
	
	/* Notification  [Salesman]*/

	/*=============================================
	=            Lead Section START           =
	=============================================*/
	// Lead Status
	//Route::get('leadstatus', 'API\LeadController@leadStatuslist');
	Route::post('leadstatus', 'API\LeadController@leadStatuslist');
	// Lead Sources
	//Route::get('leadsources', 'API\LeadController@leadSourceslist');
	Route::post('leadsources', 'API\LeadController@leadSourceslist');
	// Tag List
	Route::get('taglist', 'API\LeadController@tagList');
	// Add Lead
	Route::post('addlead', 'API\LeadController@store');
	// Edit/Update Lead
	Route::post('editlead', 'API\LeadController@update');
	// Add Lead Note/Feedback
	Route::post('addleadfeedback', 'API\LeadController@add_note');
	// Note/Feedback List
	Route::post('feedbacklist', 'API\LeadController@leadFeedbackList');
	/*=====  End of Lead Section block  ======*/

	/*=============================================
	= Salesman Todays Collection Section START           =
	=============================================*/
	/*Salesman Todays Collection List */
	Route::post('todaycollection', 'API\CollectPaymentController@todaySalesmanCollectionList');


	/*Collection thred List */
	Route::post('collectionthred', 'API\CollectPaymentController@salesmanCollectionThred');
	/*=====  End of Salesman Todays Collection Section block  ======*/

	Route::post('collection/addfeedback/{collect_payment_id?}', 'API\CollectPaymentController@addfeedback');
	/*Salesman Todays Collection*/

});



/*=============================================
=         E - Commerce  Section Start         =
=============================================*/

/* Customer Login & Registration*/
Route::post('userlogin', 'API\UserController@login');
Route::post('sociallogin', 'API\UserController@socialLogin');

Route::post('userregister', 'API\UserController@register');

/*Instamojo Credentials & Success URL*/
Route::post('user/instamojoCredentials', 'API\UserController@instamojoCredentials');
Route::get('user/pay-success/{order_id}', 'API\UserController@success');
Route::get('user/pay', 'API\UserController@pay');

/*Latest Product*/
Route::post('latestproduct', 'API\ProductController@latestProduct');


/*Home Slider*/
Route::post('/slider/user', 'API\UserController@slider');

/**
|---------------------------------------------------------------
|  User (Customer)
|---------------------------------------------------------------
**/
	Route::group(['prefix'=>'user','middleware' => 'auth:api'], function(){
		Route::post('smsverify', 'API\UserController@verifyOTP');
		Route::get('resendotp', 'API\UserController@resendOTP');

		/* User Detail and Update Detail and Change Password */
		Route::post('details', 'API\UserController@details');
		Route::post('infoupdate', 'API\UserController@infoupdate');
	    Route::post('changepassword', 'API\UserController@updatePassword');
	    //Route::post('sendResetPassMail', 'API\UserController@sendResetPassMail');

	    /* Category - SubCategory - Attributes */
		Route::get('categories/{category_id?}', 'API\ProductController@categories');
		Route::get('subcategories/{category_id?}/{subcategory_id?}', 'API\ProductController@subcategories');

		Route::get('products/{category_id?}/{subcategory_id?}/{product_id?}', 'API\ProductController@products');

		Route::get('productdetail/{product_id?}', 'API\ProductController@productDetail');
		/* Category - SubCategory - Attributes */

		/*Product Search*/
		Route::get('productsearch/{product_search?}', 'API\ProductController@productSearch');

		/* Category and Sub-Category */
		//Route::post('itemcategories', 'API\CategoryController@itemCategories');

		/* Product List and Single-Product-Detail */
		//Route::post('vendorproduct/{vendor_id?}', 'API\ProductController@vendorProducts');

		//Route::post('productlist/{vendor_id?}/{category_id?}/{subcategory_id?}', 'API\ProductController@productList');

		//Route::post('productdetail/{product_id?}', 'API\ProductController@productDetail');

		/*----------  Order  ----------*/
	    /*Cart Details*/
		Route::post('cartdetail', 'API\UserController@cartDetail');
		//Place Order
		Route::post('placeorder', 'API\UserController@placeorder');
		Route::get('cartlist', 'API\UserController@cartList');
		Route::get('cartdelete/{product_id?}', 'API\UserController@cartDelete');
		Route::post('updatecart', 'API\ProductController@updateCart');
		
		
		/*----------  Order  ----------*/

		//Orders History
		//Route::get('orderhistory', 'API\UserController@orderHistory');
		Route::get('orderhistory/{order_id?}','API\UserController@orderHistory');
		//Order Cancel 
		Route::post('cancelorder', 'API\UserController@orderCancel');	
		//Orders Details
		Route::post('orderDetails', 'API\UserController@orderDetails');

		/*----------  Product  ----------*/
		/*Submit Review*/
		Route::post('submitreview', 'API\ProductController@reviewsubmit');
		/*Review List*/
		Route::get('reviewlist/{product_id?}', 'API\ProductController@reviewList');
		/*Add Customer Wishlist*/
		Route::post('wishlist', 'API\ProductController@favorit');
		/*Wishlist Product List*/
		Route::post('userwishlist', 'API\ProductController@userWishlist');
		/*----------  Product ----------*/
		/*Product Temporary Add in cart */
		Route::post('cartsection', 'API\ProductController@cartSection');
		/*----------  Coupon Code  ----------*/
		/*Get Valid Coupon Code*/
		Route::get('validcoupon/{coupon_code?}', 'API\UserController@getValidCoupons');
		/*----------  Coupon Code  ----------*/

		
		/* Test FCM */
	});
	//Route::post('cartdetail', 'API\ProductController@cartDetail');

	Route::get('order', 'API\UserController@order');




/*=====  End of  E - Commerce  Section block  ======*/



