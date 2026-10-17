<?php
// Auth::routes();
include('kundali.php');
Route::get('testing', 'PagesController@testing')->name('testing');
Route::get('terms-of-use', function (){
  return view(themeView().'astrologers.termsofuse');
})->name('terms.use');
Route::post('usernotification', 'PagesController@userNotification')->name('usernotification');

Route::post('login', 'UserLoginController@getOtpOnMobile')->name('check.phone');
Route::get('verify/{email}', 'UserLoginController@verifyEmail')->name('verify.email');
// Home Page
// Route::get('/old', 'PagesController@indexold')->name('homeold');
Route::get('/today-special', 'PagesController@todaySpecial')->name('today.special');
Route::get('/tag/{slug}', 'PagesController@tags')->name('tag');

Route::get('work-with-us', 'PagesController@workWithUs')->name('workwithus');
Route::get('horoscopes', 'PagesController@horoscopes')->name('horoscopes');
Route::get('horoscope/{zodiac}/{timeFrame}/detail', 'PagesController@horoscopedetail')->name('horoscopedetail');
Route::post('kundali/dasha/vimshottari', 'PagesController@kundaliDashaVimshottari')->name('kundali.dasha.vimshottari');
/*
Route::get('kundali', 'PagesController@kundali')->name('kundali');
Route::post('kundali-enquiry/submit', 'PagesController@kundaliEnquirySubmit')->name('kundalienquiry');
Route::get('kundali/{kundaliId}', 'PagesController@kundaliDetail')->name('kundalidetail');
Route::post('kundali/chart', 'PagesController@kundaliChart')->name('kundali.chart');
Route::post('kundali/mahadasha', 'PagesController@kundaliMahadasha')->name('kundali.mahadasha');
Route::post('kundali/antardasha', 'PagesController@kundaliAntardasha')->name('kundali.antardasha');
Route::post('kundali/pratyantardasha', 'PagesController@kundaliPratyantardasha')->name('kundali.pratyantardasha');
Route::post('kundali/sookshmadasha', 'PagesController@kundaliSookshmadasha')->name('kundali.sookshmadasha');
Route::post('kundali/kp/planet', 'PagesController@kundaliKpPlanet')->name('kundali.kp.planet');

Route::post('kundali/dasha/yogini', 'PagesController@kundaliDashaYogini')->name('kundali.dasha.yogini');
Route::post('kundali/akvarga/chart', 'PagesController@kundaliAkvargaChart')->name('kundali.akvarga.chart');
Route::post('kundali/report/general', 'PagesController@kundaliReportGeneral')->name('kundali.report.general');
Route::post('kundali/report/planetary', 'PagesController@kundaliReportPlanetary')->name('kundali.report.planetary');
Route::post('kundali/report/yoga', 'PagesController@kundaliReportYoga')->name('kundali.report.yoga');
Route::post('kundali/report/manglik', 'PagesController@kundaliReportManglik')->name('kundali.report.manglik');
Route::post('kundali/report/kalsarpa', 'PagesController@kundaliReportKalsarpa')->name('kundali.report.kalsarpa');
Route::post('kundali/report/sadesati', 'PagesController@kundaliReportSadesati')->name('kundali.report.sadesati');
Route::post('kundali/report/pitra', 'PagesController@kundaliReportPitra')->name('kundali.report.pitra');
*/
Route::get('kundali-matching', 'PagesController@kundalimatching')->name('kundalimatching');
Route::post('kundali-matching-enquiry/submit', 'PagesController@kundaliMatchingEnquiry')->name('kundalimatchingenquiry');
Route::get('kundali-matching-detail/{kundali_matching_enquiries_id}', 'PagesController@kundaliMatchingDetail')->name('kundalimatchingdetail');
Route::get('panchang', 'PagesController@panchang')->name('panchang');
Route::get('services', 'PagesController@services')->name('services');
// Route::get('kundali', 'PagesController@kundali')->name('kundali');
Route::get('pooja', 'PagesController@pooja')->name('pooja');
Route::get('reports', 'PagesController@reports')->name('reports');
Route::get('feedback','PagesController@feedback')->name('feedback');
Route::post('feeback-submit','PagesController@feedbackSubmit')->name('feedback-submit');

// Blog Url
Route::group(['prefix'=>'articles', 'as'=>'blog.'], function(){
  Route::get('/', 'BlogController@index')->name('index');
  Route::get('{slug}', 'BlogController@category')->name('category');
  Route::get('{slug}/{post}', 'BlogController@post')->name('post');
});
//course enroll
Route::post('courseenroll','UserCourseController@submit')->name('courseenroll');

// zodiac-signs
Route::group(['prefix'=>'zodiac-signs', 'as'=>'zodiac-signs.'], function(){
    Route::get('/', 'ZodiaSignController@index')->name('index');
    Route::get('{slug}', 'ZodiaSignController@signOrPage')->name('sign');
    Route::get('{parent_page}/{slug}', 'ZodiaSignController@signPage')->name('sign.page');
});

Route::group(['middleware'=>'verifiy.user'], function (){
  Route::get('{productTypeName}/checkout', 'ProductController@checkoutform')->name('checkout');
});

// astrologers
Route::group(['as'=>'astrologers.'], function(){
  Route::group(['prefix'=>'astrologers'], function(){
    Route::get('verify/{email}', 'AstrologerController@verifyEmail')->name('verify.email');
    Route::group(['middleware' => 'auth'], function(){
      Route::get('review/{slug}', 'AstrologerController@review')->name('review');
      Route::post('review/{slug}', 'AstrologerController@reviewSubmit')->name('astrologers.review');
      Route::get('{slug}/make/{option}/{approve?}','AstrologerController@makeCallChatRequest')->name('make.request');
      Route::post('{slug}/schdule/appointment','AstrologerController@schduleAppointment')->name('schdule.appointment');
    });
    Route::post('submitreport/{slug}', 'AstrologerController@submitOfflineReport')->name('offlinereport');
    Route::group(['middleware'=>'verifiy.user'], function (){
      Route::get('{astrologerType}/{slug}', 'AstrologerController@detail')->name('publicview');
    });
  });
  Route::get('/search-location/{title?}', 'AstrologerController@search')->name("searchlocation");
  Route::get('/search-data/{title?}', 'AstrologerController@searchdata');
  // For initial page load (using GET)
  Route::get('/astrologers/{type}', 'AstrologerController@index')->name('index');
  Route::get('/chat-with-astrologers', 'AstrologerController@chatWithAstrologers')->name('chatwithastrologers');
  Route::get('/talk-to-astrologers', 'AstrologerController@talkToAstrologers')->name('talktoastrologers');
  Route::get('/astrologers/{type?}', 'AstrologerController@index')->name('index');
  Route::post('/astrologers/filter', 'AstrologerController@filterData')->name('filterData');
  Route::get('/talk-to-guruji/{search?}', 'AstrologerController@index')->name('guruji');

  // For filtering and load more (using POST)
  Route::get('datatime' , 'AstrologerController@datachanges')->name('datatime');
  Route::post('book-consultation','AstrologerController@callscheduled')->name('bookconsultation');
  Route::post('book-ai-consultation','AstrologerController@bookAiAstrologerAppointment')->name('bookaiconsultation');
  Route::get('/rzorpay','AstrologerController@paymentresponse' )->name('payment.response');
  Route::get('/date','AstrologerController@date' )->name('date.response');
});

// horoscopes
Route::group(['prefix'=>'horoscopes', 'as'=>'horoscopes.'], function(){
  $year = date('Y');
  $month = date('m');
  Route::get('/{type?}', 'HoroscopeController@index')->name('index')->where('type', "weekly|monthly|daily|[0-9]{4}");
  Route::get('{type}/{sign}-{field}-horoscope', 'HoroscopeController@signDetail')->name('detail')->where('type', 'weekly|monthly|daily')->where('field', 'free|love|career|finance|health');
  Route::get('{type}/{sign}-{field}-horoscope-{year}', 'HoroscopeController@signDetail')->name('yearly')->where('field', 'free|love|career|finance|family');;
});

// vastu
// Route::group(['prefix'=>'vastu', 'as'=>'vastu.'], function(){
//     Route::get('/', 'VastuController@index')->name('index');
//     Route::get('services', 'VastuController@vastuService')->name('vastuservice');
//     Route::get('/{slug}', 'VastuController@page')->name('page');
// });

  // Route::get('vastu-shastra', 'VastuController@index')->name('vastu.index');
  Route::get('vastu-services', 'VastuController@vastuService')->name('vastu.vastuservice');
  Route::get('vastu-services/{slug}', 'VastuController@page')->name('vastu.page');
  Route::post('enquiry-vastu','VastuController@submitEnquiry')->name('vastu.enquiry');
  Route::get('thank-you','PagesController@thankYou')->name('thank.you');

  // Route::get('loader',function (){
  //   return view(themeView().'pages.loader');
  // })->name('loader');

// baby-names
Route::group(['prefix'=>'baby-names', 'as'=>'baby-names.'], function(){
    Route::get('/', function(){ return view(themeView().'common.soon'); })->name('index');
});
// planet
Route::group(['prefix'=>'planet', 'as'=>'planet.'], function(){
    Route::get('/', 'PlanetController@index')->name('index');
    Route::get('/{slug}', 'PlanetController@page')->name('page');
    Route::get('/{type}/{slug}', 'PlanetController@subPage')->name('subpage');
});
// palmreading
Route::group(['prefix'=>'palmreading', 'as'=>'palmreading.'], function(){
    Route::get('/{slug?}', 'PalmreadingController@index')->name('index');
});

//spirituality
Route::group(['prefix'=>'spirituality'], function(){
    // spirituality
    // Route::group([ 'as'=>'spirituality.'], function(){
    //     Route::get('/', function(){ return view(themeView().'pages.spirituality'); })->name('index');
    // });
    // pooja-vidhi
    Route::group([ 'prefix'=>'pooja-vidhi','as'=>'puja.'], function(){
        Route::get('/', 'PujaController@index')->name('index');
        Route::get('/{slug}', 'PujaController@page')->name('page');
    });
    // mantra
    Route::group([ 'prefix'=>'mantra','as'=>'mantra.'], function(){
        Route::get('/', 'MantraController@index')->name('index');
        Route::get('/{slug}','MantraController@page')->name('page');
    });
    // chakra
    Route::group([ 'prefix'=>'chakra','as'=>'chakra.'], function(){
        Route::get('/{slug?}', 'ChakraController@index')->name('index');
    });
    // color-therapy
    Route::group([ 'prefix'=>'color-therapy','as'=>'color.'], function(){
        Route::get('/{slug?}', 'ColorTherapyController@index')->name('index');
    });
});

//wedding
Route::group(['prefix'=>'wedding', 'as'=>'wedding.'], function(){
    Route::get('/{slug?}', 'WeddingController@index')->name('index');
});
// Remedies checkout route - must be BEFORE remedies group to avoid route conflict
Route::get('remedies/checkout/{token}', 'ProductController@remediesCheckout')->name('remedies.checkout');

//remedies
Route::group(['prefix'=>'remedies', 'as'=>'remedies.'], function(){
    Route::get('/', 'RemediesController@index')->name('index');
    Route::get('/{slug}', 'RemediesController@page')->name('page');
    Route::get('/{type}/{slug}', 'RemediesController@subPage')->name('subpage');
});
//chineseastrology
Route::group(['prefix'=>'chineseastrology', 'as'=>'chineseastrology.'], function(){
    Route::get('/', function(){ return view(themeView().'common.soon'); })->name('index');
});
//video
Route::group(['prefix'=>'video', 'as'=>'video.'], function(){
    Route::get('/', 'VideoController@index')->name('index');
    Route::get('/{category}', 'VideoController@categoryVideo')->name('category');
});
// meditation
Route::group([ 'prefix'=>'meditation','as'=>'meditation.'], function(){
    Route::get('/{slug?}', 'MeditationController@index')->name('index');
});
// yoga
Route::group([ 'prefix'=>'yoga','as'=>'yoga.'], function(){
    Route::get('/{slug?}', 'YogaController@index')->name('index');
});
// festival
Route::group([ 'prefix'=>'festival','as'=>'festival.'], function(){
    Route::get('/', 'FestivalController@index')->name('index');
    Route::get('/{slug}','FestivalController@page')->name('page');
});
// contact
Route::get('contact-us','PagesController@contactForm')->name('contactForm');
Route::post('contact',  'PagesController@contact')->name('contact');
//  Vedic Astrologty
// Route::get('vedic-astrology', function(){ return view(themeView().'pages.indianastrology'); })->name('indianastrology');

// Newsletter Subscribe
Route::get('newsletter',  'PagesController@newsletter')->name('newsletter');
Route::post('newsletter',  'PagesController@newsletterSubscribe');
Route::get('consult/{appointment_id}/video','VideoCallController@index')->name('consult.video');
Route::get('consult/{appointment_id}/update-appointment-status/{status}/{time}','VideoCallController@updateStatus')->name('appointment.status');

// User Registration
Route::get('user/register', 'Auth\RegisterController@showRegistrationForm')->name('user.register');
Route::post('user/register', 'Auth\RegisterController@register');

// User Login
Route::get('user/login', 'UserLoginController@showLoginForm')->name('login');
Route::post('user/login', 'UserLoginController@getOTP');
Route::post('verify/otp', 'UserLoginController@verifyOTP')->name('verifyOTP');

// User Logout
Route::get('user/logout', 'Auth\LoginController@userLogout')->name('logout');

Route::group(['middleware' => ['auth', 'verifiy.user'], 'prefix'=>'account', 'as'=>'user.'], function(){
    Route::get('switch', 'AccountController@switch')->name('switch');
    Route::get('dashboard', 'AccountController@index')->name('dashboard');
    Route::get('profile', 'AccountController@profile')->name('profile');
    Route::post('profile', 'AccountController@saveProfile');
    Route::post('get/verify/otp', 'UserLoginController@getOtpForVerify')->name('getotp');
    Route::post('user/verify/otp', 'UserLoginController@userVerifyOtp')->name('verifyotp');
    Route::get('verify/mobile/number', 'AccountController@profile')->name('verify.user.mobile');
    Route::get('addresses', 'AccountController@addresses')->name('addresses');
    Route::post('addresses', 'AccountController@addressesSave');
    Route::get('delete-address/{address_id}', 'AccountController@addressesDelete')->name('addresses.delete');
    Route::post('avatar', 'AccountController@avatar')->name('avatar');
    Route::post('additional', 'AccountController@additionalInformation')->name('additional');
    Route::get('recharge/{option?}', 'AccountController@recharge')->name('recharge');
    Route::post('recharge', 'AccountController@makeRecharge');
    Route::post('rzrresponse/{user_recharge_id}/{order_id}', 'AccountController@paymentResponse')->name('razorpay.response');
    Route::get('recharge-history', 'AccountController@rechargeHistory')->name('recharge-history');
    Route::get('wallet-histories', 'AccountController@walletHistory')->name('wallethistory');
    Route::get('invoice/{id}-view', 'AccountController@rechargeHistoryInvoice')->name('recharge-print');
    Route::get('calls/{id}-view', 'AccountController@callHistoryPrint')->name('call-print');
    Route::get('newsletter', 'AccountController@newsletter')->name('newsletter');
    Route::get('{status}/newsletter', 'AccountController@subscribe')->name('newsletter.status');
    Route::get('subscription', 'AccountController@subscription')->name('subscription');
    // Route::post('newsletter', 'AccountController@subscribe');
    Route::get('orders', 'AccountController@purchaseitem' )->name('purchase.item');
    Route::get('order/{order_id}/detail', 'AccountController@itemDetail' )->name('order.detail');
    Route::get('courses', 'AccountController@purchasecourses' )->name('purchasecourse');

    // astrologer consultations list
    Route::get('consultations', 'AccountController@consultations' )->name('consultations');

    // ai consultations list
    Route::get('ai-consultations', 'AccountController@aiConsultations' )->name('aiconsultations');

    // suggested remedies list
    Route::get('suggested-remedies', 'AccountController@suggestedRemedies' )->name('suggested-remedies');

    Route::get('wishlists', 'AccountController@wishlists' )->name('wishlist');
    Route::get('booked-puja', 'AccountController@bookedPuja')->name('booked-puja');
    Route::get('booked-puja/group/detail', 'AccountController@bookedGroupPujaDetail')->name('booked-puja.group.detail');
    Route::get('booked-puja/{assignment_id}/detail', 'AccountController@bookedPujaDetail')->name('booked-puja.detail');
    Route::post('booked-puja/cancel', 'AccountController@cancelBookedPuja')->name('booked-puja.cancel');
    Route::get('course/quiz', 'AccountController@quizzes' )->name('quiz');
    Route::get('support', 'AccountController@support' )->name('support');
    Route::post('support', 'AccountController@submitSupport' );
    Route::post('support/{support_id}/replies', 'AccountController@fetchSupportReplies' )->name('support.replies');
    Route::post('support/{support_id}/replies/submit', 'AccountController@supportReplySubmit' )->name('support.replies.submit');
    Route::get('notification', 'AccountController@notification' )->name('notification');
    Route::get('course/{slug}/explore', 'AccountController@exploreCourse')->name('course.explore');
    Route::post('course/{course_id}/module/{module_id}', 'AccountController@canWatchModule')->name('course.fetchTypeAndUrl');
    Route::post('course/{course_id}/module/{module_id}/content/{content_id}/video/{video_id}', 'AccountController@howMuchWatch')->name('course.watched');
    Route::post('course/{course_id}/module/{module_id}/passed', 'AccountController@passedModule')->name('course.module.passed');
    // Route::get('course/{course_slug}/assignments', 'AccountController@assignments' )->name('course.assignment');
    Route::get('course/{course_slug}/meeting', 'AccountController@meetings' )->name('course.meeting');
    Route::get('course/{course_slug}/{type}', 'AccountController@assignments' )->name('course.assignment');
    Route::get('course/{course_slug}/{type}/{module_id}', 'AccountController@assignmentQuestions' )->name('course.assignment.questions');
    Route::post('course/{course_slug}/{type}/{module_id}/question/{question_id}', 'AccountController@fetchAssignmentQuestion' )->name('course.assignment.question.fetch');
    Route::post('course/{course_slug}/{type}/{module_id}/submit', 'AccountController@submitAssignmentQuestion' )->name('course.assignment.submit');
    Route::get('course/{course_slug}/{type}/module/{module_id}/result', 'AccountController@courseResult' )->name('course.result');
    // Route::get('course/{course_slug}/module/{module_id}/certificate', 'AccountController@courseCertificate' )->name('course.certificate');
    // Route::get('course/financial', 'AccountController@financial' )->name('course.financial');
    Route::get('certificate/{course_slug}', 'AccountController@certificate' )->name('course.certificatee');
    Route::post('submit/consultation-request', 'AccountController@consultationRequest' )->name('consultation.request');
    Route::post('submit/order-request', 'AccountController@orderRequest' )->name('order.request');

    // astrologer review submit
    Route::post('astrologer-review/submit', 'AccountController@astrologerReviewSubmit')->name('astrologer.review.submit');
    // notify to user save
    Route::post('notify-user/save', 'AccountController@userNotification')->name('notify.save');

    // referral listing
    Route::get('referral-listing', 'AccountController@referralListing')->name('referral.listing');

    // New Referral Module Routes
    Route::get('refer-earn', 'ReferralController@referEarn')->name('refer.earn');
    Route::get('app-referrals', 'ReferralController@appReferrals')->name('referrals.app');
    Route::get('commissions', 'CommissionController@index')->name('commissions');
    Route::get('commissions/{id}', 'CommissionController@detail')->name('commissions.detail');
    Route::get('earnings', 'WalletController@earnings')->name('earnings');
    Route::get('wallet-ledger', 'WalletController@ledger')->name('wallet.ledger');
    Route::get('referral-rules', function() {
        return view(themeView().'account.referral-rules', ['nav' => 'referral-rules']);
    })->name('referral.rules');
});

Route::group(["prefix"=>"guest","as"=>"guest."],function(){
  Route::get('redirect-to-checkout', 'OrderController@redirectToCheckout')->name('redirecttocheckout');
  Route::get('redirect-to-buynow', 'OrderController@redirectToBuynow')->name('redirecttobuynow');
  Route::get('redirect-to-direct-checkout', 'OrderController@redirecttodirectcheckout')->name('redirecttodirectcheckout');
});


  //invoice
  Route::get('download/invoice/{type}/{id}', 'AccountController@invoice' )->name('user.invoice');

  //chat consultations
  Route::get('account/{room_id}/chat', 'AccountController@startchat')->name('user.startchat');
  Route::post('account/can-chat','AccountController@canChat')->name('user.canchat');
  Route::post('account/chat-logs','AccountController@fetchChatLogs')->name('user.chatlogs');
  Route::post('account/send-message','AccountController@sendMessage')->name('user.sendmessage');

  //call consultations
  Route::get('account/{room_id}/phone-call', 'AccountController@startPhoneCall')->name('user.startphonecall');
  Route::post('account/can-phone-call','AccountController@canPhoneCall')->name('user.canphonecall');
  Route::post('account/update-phone-call-status','AccountController@updatePhoneCallStatus')->name('user.updatephonecallstatus');

  //video consultations
  Route::get('account/{room_id}/video-call', 'AccountController@startVideoCall')->name('user.startvideocall');
  Route::post('account/can-video-call','AccountController@canVideoCall')->name('user.canvideocall');
  Route::post('account/update-video-call-status','AccountController@updateVideoCallStatus')->name('user.updatevideocallstatus');

  //ai chat consultations
  Route::get('account/{consultation_id}/ai-chat', 'AccountController@startaichat')->name('user.startaichat');
  Route::post('account/ai-chat-logs','AccountController@fetchAiChatLogs')->name('user.aichatlogs');
  Route::post('account/send-message-to-ai','AccountController@sendMessageToAi')->name('user.sendmessagetoai');
  Route::post('account/update -ai-chat-time','AccountController@updateAiChatTime')->name('user.updateaichattime');

// User Social Auth
Route::get('auth/{provide}', 'SocialAuthFacebookController@redirect')->name('social.login');
Route::get('auth/{provider}/callback', 'SocialAuthFacebookController@callback')->name('social.login.callback');

// Payment Response
// Route::post('rzrresponse', 'AccountController@paymentResponse')->name('razorpay.response');
Route::post('rzrresponse/{user_recharge_id}/{order_id}', 'AccountController@paymentResponse')->name('razorpay.response');

// Route::get('astrology', 'PagesController@astrology')->name('astrology');
// Route::get('nakshtra-gold-software', 'PagesController@nakshtra')->name('nakshtra');
Route::post('enquiry-cms2','PagesController@submitEnquiry')->name('cms_two.enquiry');
Route::post('enquiry-astrology','PagesController@submitAstrologyEnquiry')->name('astrology.enquiry');

// notification
Route::get('notification/{notification_id}/view','PagesController@redirectToNotificationLin')->name('notification.view');
// notification
Route::get('all-notifications','PagesController@allNotification')->name('all.notification');
// General All Pages
Route::get('{slug}', 'PagesController@pages')->name('pages');

//Products & services

/* Course page */
Route::get('courses', 'CourseController@index')->name('courses');
// Route::get('course/{slug}', 'CourseController@detail')->name('course.detail');
Route::post('course/check-coupon-avail',  'CourseController@checkCouponAvail')->name('check.coupon.avail');


Route::get('/import/fb-leads', function () {
    return view('site.pages.import');
});

//courses
Route::Post('addtocartcourse','ProductController@addtocartcourse')->name('addtocartcourse');

//Media
Route::group(['prefix' => 'media'], function(){
    Route::post('upload','MediaController@upload')->name('media.upload');
    Route::get('list', 'MediaController@all')->name('media.all');
    Route::delete('delete', 'MediaController@delete')->name('media.delete');
    Route::post('submit', 'MediaController@submit')->name('media.submit');
});
// Route::get('aboutus', 'PagesController@aboutus')->name('about-us');

Route::Post('addtocart','ProductController@addtocart')->name('addtocart');
// Route::get('cart/{user_id}', 'ProductController@cart')->name('cart.view');
Route::get('cart/view', 'ProductController@cart')->name('cart.view');
Route::delete('/cart/remove/{id}', 'ProductController@removeItem')->name('remove.cart');
Route::post('/show-cart', 'ProductController@showcart')->name('mini.cart');
Route::post('cart/add-form','ProductController@addToCartForm')->name('cart.addform');
Route::get('user/shipping-address', 'AccountController@getUserShippingAddress')->name('user.shipping.address');
Route::post('ashirwad/address/save', 'ProductController@saveAshirwadAddress')->name('ashirwad.address.save');
// web.php
Route::post('/place-order','ProductController@placeorder')->name('placeorder');

Route::post('user/cart/{user_id}', 'ProductController@cartt')->name('user.cart.view');
Route::post('/cart/increase-quantity/{id}', 'ProductController@increaseQuantity')->name('cart.increase');
Route::post('/cart/decrease-quantity/{id}', 'ProductController@decreaseQuantity')->name('cart.decrease');
Route::post('review', 'ProductController@reviewSubmit')->name('product.review');
Route::post('course-review/{slug}', 'CourseController@reviewSubmit')->name('course.review');
