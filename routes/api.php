<?php
// use Illuminate\Http\Request;
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
include('kundali.php');
//register user
Route::post('register/user', 'Api\UserController@register');
//get otp for user
Route::post('otp/user', 'Api\UserController@getOTP');
//verify otp for user
Route::post('otp/user/verify', 'Api\UserController@verifyOtp');
//countries
Route::post('countries','Api\GeneralController@countries');
//states
Route::post('states','Api\GeneralController@states');
//cities
Route::post('cities','Api\GeneralController@cities');
//banners
Route::post('banners', 'Api\GeneralController@banners') ;
//schedule for later date
Route::post('available-date','Api\AstrologersControler@scheduledLaterDate');
//schedule for later time
Route::post('available-time','Api\AstrologersControler@scheduledLaterTime');
//gifts
Route::post('gifts','Api\GeneralController@gifts');
// payment types
Route::post('payment-types','Api\OrderController@paymentTypes');
// order cancel reasons
Route::post('order-cancel-reasons','Api\AccountController@orderCancelRequestReasons')->name('order.cancelrequestreason');
// order return reasons
Route::post('order-return-reasons','Api\AccountController@orderReturnRequestReasons')->name('order.returnrequestreason');
// search
Route::post('search','Api\GeneralController@searchdata')->name('searchdata');
// terms and conditions
Route::post('term-and-conditions','Api\GeneralController@termAndConditions');

// social media links (for app footer/header icons)
Route::post('social-links','Api\SocialController@index')->name('api.social.links');

// CMS pages for apps (list allowed pages, get page detail - no webview)
Route::post('pages/list','Api\CmsController@list')->name('api.pages.list');
Route::post('pages/detail','Api\CmsController@detail')->name('api.pages.detail');

Route::group(['prefix'=>'user','middleware'=>'auth:api','as'=>'api.user.'],function(){
  //user data
  Route::post('data', 'Api\AccountController@userData');
  //user data save
  Route::post('data/save', 'Api\AccountController@save');
  //addresses
  Route::post('addresses', 'Api\AccountController@addresses');
  //add edit addresses
  Route::post('addresses-submit', 'Api\AccountController@addressesSave');
  //delete addresses
  Route::post('addresses-delete', 'Api\AccountController@addressesDelete');

  // book astrologer consultation
  Route::post('book-consultaion', 'Api\AstrologersControler@callscheduled');

  // book ai astrologer consultation
  Route::post('book-ai-consultaion', 'Api\AstrologersControler@bookAiAstrologerAppointment');

  // can chat
  Route::post('can-chat', 'Api\ConsultationController@canChat');
  Route::post('chat-logs', 'Api\ConsultationController@fetchChatLogs');
  Route::post('send-message', 'Api\ConsultationController@sendMessage');

  // call
  Route::post('can-phone-call', 'Api\ConsultationController@canPhoneCall');
  Route::post('update-phone-call-status', 'Api\ConsultationController@updatePhoneCallStatus');

  // video
  Route::post('can-video-call', 'Api\ConsultationController@canVideoCall');
  Route::post('update-video-call-status', 'Api\ConsultationController@updateVideoCallStatus');

  // astrologer consultations list
  Route::post('consultations', 'Api\ConsultationController@index');

  // ai chat
  Route::post('chat-logs-ai', 'Api\ConsultationController@fetchAiChatLogs');
  Route::post('send-message-ai', 'Api\ConsultationController@sendMessageToAi');
  Route::post('update-chat-time-ai', 'Api\ConsultationController@updateAiChatTime');

  // ai astrologer consultations list
  Route::post('ai-consultations', 'Api\ConsultationController@aiConsultatins');

  //coupon
  Route::post('coupon', 'Api\CouponController@apply');
  //package data
  Route::post('recharge-plans','Api\AccountController@allPackagesData');
  //recharge
  Route::post('recharge', 'Api\AccountController@makeRecharge');
  //verify recharge payment
  Route::post('verify-recharge-payment/{user_recharge_id}/{payment_type}','Api\AccountController@paymentResponse')->name('verify.recharge.payment');

  // recharge histories
  Route::post('recharge-histories', 'Api\AccountController@rechargeHistory');

  // wallet transition histories
  Route::post('wallet-transition-history', 'Api\AccountController@walletTransitionHistory');

  // wishlist
  Route::post('wishlist/list','Api\WishlistController@list');
  Route::post('wishlist/add','Api\WishlistController@add');
  Route::post('wishlist/remove','Api\WishlistController@remove');

  // checkout
  Route::post('checkout', 'Api\CartController@checkout')->name('checkout');
  //place order
  Route::post('place-order','Api\OrderController@placeOrder')->name('place.order');
  //verify order payment
  Route::post('verify-order-payment/{order_id}/{payment_type}','Api\OrderController@verifyOrderPayment')->name('verify.order.payment');

  // submit review
  Route::post('submit-review','Api\GeneralController@submitReview')->name('submit.review');

  // orders
  Route::post('orders','Api\AccountController@orders')->name('order.list');
  Route::post('order-detail','Api\AccountController@orderItems')->name('order.detail');
  Route::post('order-request','Api\AccountController@orderRequest')->name('order.request');

  //support
  Route::group(['prefix'=>'support', 'as'=>'support.'], function(){
    Route::post('types', 'Api\SupportController@types')->name('type');
    Route::post('list', 'Api\SupportController@list')->name('list');
    Route::post('submit-ticket', 'Api\SupportController@submitTicket')->name('submittickett');
    Route::post('messages', 'Api\SupportController@messages')->name('messages');
    Route::post('submit-message', 'Api\SupportController@submitMessage')->name('submitmessage');
  });
  //live astrologers
  Route::post('live/astrologers','Api\AstrologersControler@liveAstrologers')->name('live.astrologers');
  //join live astrologer
  Route::post('join/live/astrologer','Api\AccountController@joinLiveAstrologer')->name('join.live.astrologer');
  //update joined user status
  Route::post('update/joined/user','Api\AccountController@liveStreamUpdate')->name('updated.joined.user');
  //send gifts
  Route::post('gifts/send','Api\AccountController@sendGift');
  //send gifts
  Route::post('live-chat/send','Api\AccountController@astrologerLiveUserChat');
  // save notify to user
  Route::post('notify-user/save','Api\AccountController@userNotification');
  // save notify to user
  Route::post('notifications','Api\GeneralController@getNotification');

  // logout
  Route::post('logout','Api\AccountController@logout')->name('logout');
  // //lms
  // Route::post('courses','Api\AccountController@lms')->name('course.lists');
  // //course explore
  // Route::post('course-explore','Api\AccountController@exploreCourse')->name('course.explore');
  // //course assignments
  // Route::post('course-assignments','Api\AccountController@assignments')->name('course.assignment');
  // //course quizzes
  // Route::post('course-quizzes','Api\AccountController@quizzes')->name('course.quizz');
  // //course fetch question_id
  // Route::post('course-first-question','Api\AccountController@firstQuestions')->name('course.first.question');
  // //course fetch question details
  // Route::post('course-question','Api\AccountController@fetchQuestion')->name('course.question');
  // //course submit question
  // Route::post('course-question-submit','Api\AccountController@submitQuestion')->name('course.question.submit');
  // //course result define in web.php
  // //course certificate
  // Route::post('course-certificate','Api\AccountController@certificate')->name('course.certificate');
  // //course meeting
  // Route::post('course-meeting','Api\AccountController@meetings')->name('course.meeting');
});



//check can order or not
Route::post('can-order','Api\OrderController@canOrder');
Route::post('verify-payment','Api\OrderController@verifyOrderPayment');

Route::group(['prefix'=>'astrologer', 'as'=>'astrologer.'], function(){
  // types
  Route::post('types','Api\AstrologersControler@types');
  // experiences
  Route::post('experiences','Api\AstrologersControler@experiences');
  // prices
  Route::post('prices','Api\AstrologersControler@prices');
  // ratings
  Route::post('ratings','Api\AstrologersControler@ratings');
  // services
  Route::post('services','Api\AstrologersControler@services');
  // languages
  Route::post('languages','Api\AstrologersControler@languages');
  // sort_by
  Route::post('sort-by','Api\AstrologersControler@sortBy');
  // list
  Route::post('list/{astroologerType?}', 'Api\AstrologersControler@list');
  // detail
  Route::post('detail', 'Api\AstrologersControler@detail');
  // status
  Route::post('update-status', 'Api\AstrologersControler@updateStatus');
  // review
  Route::post('review', 'Api\AstrologersControler@review');
  // verify user mobile number
  Route::post('verify-astrologer-mobile-number', 'Api\AstrologerController@getPhoneOTP');
  // verify user otp
  Route::post('verify-astrologer-otp', 'Api\AstrologerController@verifyPhoneOtp');
  // register options (languages, services, countries, states for Flutter app)
  Route::get('register/options', 'Api\AstrologerController@registerOptions');
  // cities by state (e.g. GET /api/astrologer/register/cities?state_id=1)
  Route::get('register/cities', 'Api\AstrologerController@registerCities');
  // IFSC lookup for bank details (e.g. GET /api/astrologer/ifsc/YESB0DNB002)
  Route::get('ifsc/{ifsc}', 'Api\AstrologerController@ifscLookup')->where('ifsc', '[A-Za-z0-9]{11}');
  // Step-wise registration (app): step 1 → returns draft_token; steps 2–3 require draft_token; final register uses draft_token + step 4
  Route::post('register/step/1', 'Api\AstrologerController@registerStep1');
  Route::post('register/step/2', 'Api\AstrologerController@registerStep2');
  Route::post('register/step/3', 'Api\AstrologerController@registerStep3');
  // register: single-shot (all fields) OR step-wise (draft_token + step 4 fields only)
  Route::post('register', 'Api\AstrologerController@register');
  // otp
  Route::post('otp','Api\AstrologerController@otp');
  // verify otp
  Route::post('verify-otp','Api\AstrologerController@verifyOtp');
  // data
  Route::post('data','Api\AstrologerController@data');
  // profile (GET = fetch, POST = update; requires api_token)
  Route::get('profile', 'Api\AstrologerController@profile');
  Route::post('profile', 'Api\AstrologerController@profileUpdate');
  // earnings (wallet summary + transaction history; requires api_token)
  Route::post('earnings', 'Api\AstrologerController@earnings');
  Route::get('earnings', 'Api\AstrologerController@earnings');
  // puja (one-to-one and group pujas assigned to astrologer; requires api_token)
  Route::get('puja', 'Api\AstrologerController@puja');
  Route::post('puja', 'Api\AstrologerController@puja');
  // booked-puja (booked puja assignments; requires api_token)
  Route::get('booked-puja', 'Api\AstrologerController@bookedPuja');
  Route::post('booked-puja', 'Api\AstrologerController@bookedPuja');
  // puja-earnings (puja commission history; requires api_token)
  Route::get('puja-earnings', 'Api\AstrologerController@pujaEarnings');
  Route::post('puja-earnings', 'Api\AstrologerController@pujaEarnings');
  // referral-listing (users/astrologers referred by this astrologer; requires api_token)
  Route::get('referral-listing', 'Api\AstrologerController@referralListing');
  Route::post('referral-listing', 'Api\AstrologerController@referralListing');
  // products-for-astrologer (Top Selling products for astrologer profile; URL includes referral code)
  Route::get('products-for-astrologer', 'Api\AstrologerController@productsForAstrologer');
  Route::post('products-for-astrologer', 'Api\AstrologerController@productsForAstrologer');
  // logout
  Route::post('logout','Api\AstrologerController@logout');

});

//products
Route::group(['prefix'=>'product', 'as'=>'api.product.'], function(){
  Route::post('categories', 'Api\ProductController@categories')->name('categories');
  Route::post('types', 'Api\ProductController@types')->name('types');
  Route::post('subtypes', 'Api\ProductController@subTypes')->name('subtypes');
  Route::post('sortby', 'Api\ProductController@sortBy')->name('sortby');
  Route::post('list', 'Api\ProductController@list')->name('list');
  Route::post('detail', 'Api\ProductController@detail')->name('detail');
  Route::post('review', 'Api\ProductController@review')->name('review');
});

//cart
Route::group(['prefix'=>'cart', 'as'=>'api.cart.'], function(){
  Route::post('list','Api\CartController@list')->name('list');
  Route::post('add','Api\CartController@add')->name('add');
  Route::post('update','Api\CartController@updateCartItemFormData')->name('update');
  Route::post('remove','Api\CartController@remove')->name('remove');
  Route::post('increase','Api\CartController@increase')->name('increase');
  Route::post('decrease','Api\CartController@decrease')->name('decrease');
});

//products
Route::group(['prefix'=>'blog', 'as'=>'api.blog.'], function(){
  Route::post('categories', 'Api\BlogController@categories')->name('categories');
  Route::post('list', 'Api\BlogController@list')->name('list');
  Route::post('detail', 'Api\BlogController@detail')->name('detail');
});
//horoscope
Route::group(['prefix'=>'zodiac', 'as'=>'api.zodiac.'], function(){
  Route::post('list', 'Api\HoroscopeController@zodiacList')->name('list');
  Route::post('time-frames', 'Api\HoroscopeController@timeFrames')->name('timeframes');
  Route::post('detail', 'Api\HoroscopeController@zodiacDetail')->name('detail');
});
//panchang
Route::group(['prefix'=>'panchang', 'as'=>'api.panchang.'], function(){
  Route::post('detail', 'Api\PanchangController@detail')->name('detail');
});
//panchang
Route::group(['prefix'=>'location', 'as'=>'api.location.'], function(){
  Route::post('/', 'Api\KundaliController@locations')->name('detail');
});
/*
//horoscope
Route::group(['prefix'=>'kundali', 'as'=>'api.kundali.'], function(){
  Route::post('detail', 'Api\KundaliController@detail')->name('detail');
  Route::post('kundali', 'Api\KundaliController@kundali')->name('kundali');
  Route::post('kp', 'Api\KundaliController@kp')->name('kp');
  Route::post('ashtakvarga', 'Api\KundaliController@ashtakvarga')->name('ashtakvarga');
  Route::post('charts', 'Api\KundaliController@divisionCharts')->name('charts');
  Route::post('dasha', 'Api\KundaliController@dasha')->name('dasha');
  Route::post('report/general', 'Api\KundaliController@reportGeneral')->name('report.general');
  Route::post('report/dosha', 'Api\KundaliController@reportDosha')->name('report.dosha');
});
*/
// kundali matching
Route::group(['prefix'=>'kundali-matching', 'as'=>'api.kundalimatching.'], function(){
  Route::post('detail', 'Api\KundaliController@kundaliMatchingDetail')->name('detail');
});

//course
Route::group(['prefix'=>'course', 'as'=>'course.'], function(){
    Route::post('categories', 'Api\CourseController@categories')->name('categories');
    Route::post('types', 'Api\CourseController@types')->name('types');
    Route::post('package-types', 'Api\CourseController@packageTypes')->name('packagetypes');
    Route::post('sortby', 'Api\CourseController@sortBy')->name('sortby');
    Route::post('list', 'Api\CourseController@list')->name('list');
    Route::post('detail/{slug}', 'Api\CourseController@detail')->name('detail');
    Route::post('{slug}/review', 'Api\CourseController@review');
});
// Route::post('courses','Api\CoursesController@courseAll');
// Route::post('courses/{type}','Api\CoursesController@courseAll');
// Route::post('course/{slug}','Api\CoursesController@courseDetail');
// Route::post('course/{slug}/modules','Api\CoursesController@courseDetailModules');
//package data
Route::get('packages/','Api\AccountController@allPackagesData');
//blogs
Route::post('blogs','Api\GeneralController@blogAll');
//start chat
Route::get('{id}/chat/{user_token}','Api\ConsultationController@makechat')->name('chat');
Route::get('/chat-logs/{chat_id}', 'Api\ConsultationController@fetchChatLogs');
Route::post('/send-message', 'Api\ConsultationController@sendMessage');
Route::get('consultation/{user_token}','Api\ConsultationController@allconsultation');
// search location
Route::get('/search-location/{title?}', 'Api\ConsultationController@search');
Route::post('zodiac-signs','Api\GeneralController@zodiacSigns');

//testimonials
Route::group(['prefix'=>'testimonials', 'as'=>'testimonial.'], function (){
  Route::post('text','Api\GeneralController@textTestimonial')->name('text');
  Route::post('video','Api\GeneralController@videoTestimonial')->name('video');
});

//faqs
Route::post('faqs','Api\GeneralController@faqs')->name('api.faqs');

// advertisements
Route::post('ads','Api\GeneralController@advertisements')->name('api.advertisements');

// can review
Route::post('can-review','Api\GeneralController@canReview')->name('api.can.review');

//submit get in touch form (course detail)
Route::post('get-in-touch','Api\GeneralController@getInTouch')->name('getInTouch');
//vastu
Route::group(['prefix'=>'vastu', 'as'=>'vastu.'], function(){
  Route::post('detail','Api\VastuController@detail')->name('detail');
  Route::post('service/detail','Api\VastuController@serviceDetail')->name('service.detail');
});
//astrology
Route::post('astrology/detail','Api\PagesController@astrology')->name('astrology.detail');
//ngsoftware
Route::post('software/detail','Api\PagesController@ngSoftware')->name('software.detail');
