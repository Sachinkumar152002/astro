<?php
// General & Common Routes

// home route
Route::post('verify-user', 'GeneralController@getPhoneOTP')->name('verifyusergetphoneotp');
Route::post('verify-user/otp', 'GeneralController@verifyPhoneOtp')->name('verifyuserphoneotp');

Route::get('/', 'PagesController@index')->name('home');
Route::get('/nk-janam-patrika', 'LandingPageController@index')->name('landing.nkJanam');
Route::get('/ng-software', 'LandingPageController@ngSoftware')->name('landing.ngSoftware');
Route::get('/vastu-consultation', 'LandingPageController@vastu')->name('landing.vastu');
Route::get('/astro-consultation', 'LandingPageController@astro')->name('landing.astro');
Route::post('lp/form/submit', 'LandingPageController@submitForm')->name('landing.page.submit');
Route::get('lp/{slug}', 'LandingPageController@page')->name('landing.page');
Route::get('page/{slug}', 'LandingPageController@oldpage')->name('landing.old.page');
Route::post('/submit-consultation', 'LandingPageController@submitConsultation')->name('consultation.submit');
Route::post('/payment/callback', 'LandingPageController@handlePayment')->name('payment.callback');
Route::get('/payment/success', 'LandingPageController@paymentSuccess')->name('payment.success');
Route::get('/api/cities', 'LandingPageController@search')->name('city.search');

Route::get('/result/{api_token}/{module_id}/{type}', 'AccountController@resultWebView')->name('result.webview');
Route::get('/certificate/{api_token}/{course_slug}', 'AccountController@certificateApp')->name('certificate.app');
// Admin Routes
include "admin.php";
// Astrologer Routes
include "astrologers.php";

Route::get('locations/{city_state?}', function(){
    $table = request()->city_state;
    $locations = DB::table($table)->orderBy('name', 'desc');
    if($table=='master_states'){
        $locations->where("country_id",request()->country_id);
        $states = request()->states;
        $states = is_array($states) ? $states : json_decode($states);
        if(request()->id && request()->states && count($states)>0 ) {
            $locations->whereIn('id', $states);
        }
    }
    if($table=='master_cities'){
        $locations->where("state_id",request()->state_id);
        $cities = request()->cities;
        $cities = is_array($cities) ? $cities : json_decode($cities);
        if(request()->id && request()->cities && count($cities)>0 ) {
            $locations->whereIn('id', $cities);
        }
    }
    $locations = $locations->pluck("name","id")->toArray();
    krsort($locations);
    return response()->json($locations, 200);
})->name('locations');

Route::get('location', 'GeneralController@locations')->name('location');

Route::get('/coming-soon',function(){
  return view(themeView().'comingsoon.index');
})->name('comingsoon');

Route::post('/verify-urgent-payment-razorpay/{user_id}/{user_type}/{table_name}/{table_id}/{order_id}','PagesController@verifyUrgentPayment')->name('verify.urgent.payment.razorpay');
Route::get('/clicktocall','ExotelController@previewpage');
Route::post('/apply-coupon', 'ProductController@applyCouponCode')->name('apply.coupon');
Route::post('/click-to-call', 'ExotelController@clickToCall')->name('click-to-call');
Route::post('call/{appointment_id}/status-callback', 'ExotelController@statusCallback')->name('call.statusCallback');
Route::post('/verify-payment/{id}/{order_id}', 'ProductController@verifyPayment')->name('payment.verify');
Route::get('/send-mail/{order_id}', 'ProductController@sendOrderMail');
Route::get('/payment-response', 'ProductController@paymentResponse')->name('paymentResponse');
// Front Routes

/* Course page */
Route::get('courses', 'CourseController@index')->name('courses');
// Route::get('course/{slug}', 'CourseController@detail')->name('course.detail');
Route::post('course/check-coupon-avail',  'CourseController@checkCouponAvail')->name('check.coupon.avail');
Route::get('courses/{slug}', 'CourseController@handleCourse')->name('course.handle');

Route::post('wishlist', 'WishlistController@addToWishlist')->name('wishlist');
Route::post('delete-wishlist', 'WishlistController@removeFromWishlist')->name('wishlist.remove');
// proudcts
Route::get('collections/{category_slug?}', 'ProductController@handleProducts')->name('product.handle');
Route::get('products/{product_slug}', 'ProductController@detailProduct')->name('product.detail');
// pujas
Route::get('pujas-collections/{category_slug?}', 'ProductController@handlePujas')->name('puja.handle');
Route::get('pujas/{product_slug}', 'ProductController@detailPuja')->name('product.detailpuja');
// one-to-one puja - listing route comes first, then slug handler
Route::get('one-to-one-puja', 'ProductController@handleOneToOnePujas')->name('one.to.one.puja.handle');
Route::get('one-to-one-puja/{slug}', 'ProductController@handleOneToOnePujaSlug')->name('one.to.one.puja.slug');
// Keep detail route for backward compatibility with views
Route::get('one-to-one-puja-detail/{product_slug}', 'ProductController@detailOneToOnePuja')->name('product.detailonetoonepuja');
// services
Route::get('reports-collections/{category_slug?}', 'ProductController@handleServices')->name('service.handle');
Route::get('reports/{product_slug}', 'ProductController@detailService')->name('product.detailservice');
// product search suggestions (for category list filter)
Route::get('product-search-suggestions', 'ProductController@productSearchSuggestions')->name('product.search.suggestions');
// vairations
Route::post('variation/products', 'ProductController@handleVariation')->name('product.variation');
Route::post('variation/form', 'ProductController@getVariationForm')->name('product.variation.form');
Route::get('/import/fb-leads', function () {
    return view('site.pages.import');
});
// Route::post('/import/fb-leads','ImportController@fblead')->name('fblead.import');
// Route::get('/import/read-auto-fb-lead','ImportController@readAutoFbLead')->name('readAutoFbLead.import');
// Route::get('/import/googledata','ImportController@readCustomDataView')->name('import.googledata');
// Route::get('/import/read-google-sheet-custom-data/{sheet_name}','ImportController@readGoogleSheetCustomData')->name('readGoogleSheetCustomData.import');

Route::get('/auth/google', 'Auth\LoginController@redirectToGoogle')->name('redirect.to.google');
Route::get('/auth/google/callback', 'Auth\LoginController@handleGoogleCallback')->name('handle.google.callback');

Route::get('/auth/facebook', 'Auth\LoginController@redirectToFacebook')->name('redirect.to.facebook');
Route::get('/auth/facebook/callback', 'Auth\LoginController@handleFacebookCallback')->name('handle.facebook.callback');

Route::get('/auth/apple', 'Auth\LoginController@redirectToFacebook')->name('redirect.to.apple');
Route::get('/auth/apple/callback', 'Auth\LoginController@handleFacebookCallback')->name('handle.apple.callback');

Route::post('/redirect-url/save', 'Auth\LoginController@handleRedirectUlr')->name('handle.redirect.url');
include "theme.php";

Route::fallback(function () {
    abort(404);
});
