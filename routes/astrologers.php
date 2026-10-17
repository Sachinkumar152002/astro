<?php
// use App\Http\Controllers\Astrologer\LoginController;
// Astrologer Routes
Route::group(['prefix'=>'astrologer', 'as'=>'astrologer.'], function(){
    // Registration
    Route::get('register', 'Astrologer\RegisterController@registrationForm')->name('register');
    Route::post('register/step/1', 'Astrologer\RegisterController@saveStep1')->name('register.step1');
    Route::post('register/step/2', 'Astrologer\RegisterController@saveStep2')->name('register.step2');
    Route::post('register/step/3', 'Astrologer\RegisterController@saveStep3')->name('register.step3');
    Route::post('register', 'Astrologer\RegisterController@register');
    Route::get('register/thank-you', 'Astrologer\RegisterController@thankYou')->name('register.thankyou');
    Route::get('ifsc/{ifsc}', 'Astrologer\RegisterController@ifscLookup')->name('ifsc.lookup')->where('ifsc', '[A-Za-z0-9]{11}');

    // Login
    Route::get('login', 'Astrologer\LoginController@loginForm')->name('login');
    Route::post('login', 'Astrologer\LoginController@getOTP');
    Route::post('verify/otp', 'Astrologer\LoginController@verifyOtp')->name('verifyotp');

    // Logout
    Route::get('logout', 'Astrologer\LoginController@logout')->name('logout');

    Route::post('upload', 'Astrologer\AccountController@upload')->name('upload');

    // Astrologer Account
    Route::group(['middleware' => 'astrologer.auth'], function(){
        Route::get('application-pending', 'Astrologer\AccountController@applicationPending')->name('pending');
        Route::get('switch', 'Astrologer\AccountController@switch')->name('switch');
        Route::get('dashboard', 'Astrologer\AccountController@index')->name('dashboard');
        Route::get('profile', 'Astrologer\AccountController@profile')->name('profile');
        Route::post('profile', 'Astrologer\AccountController@profileSave');
        Route::get('recharge/{option?}', 'Astrologer\AccountController@recharge')->name('recharge');
        Route::post('coupon/apply', 'Astrologer\AccountController@couponApply')->name('coupon.apply');
        Route::post('recharge', 'Astrologer\AccountController@makeRecharge');
        // Route::post('rzrresponse', 'Astrologer\AccountController@paymentResponse')->name('razorpay.response');
        Route::post('rzrresponse/{user_recharge_id}/{order_id}', 'Astrologer\AccountController@paymentResponse')->name('razorpay.response');
        Route::get('recharge-history', 'Astrologer\AccountController@rechargeHistory')->name('recharge-history');
        Route::get('invoice/{id}-view', 'Astrologer\AccountController@rechargeHistoryInvoice')->name('recharge-print');
        Route::get('orderdetail','Astrologer\AccountController@orderdetail')->name('orderdetail');
        // Route::get('courseexplore','Astrologer\AccountController@courseexplore')->name('courseexplore');
        // Route::get('assignment','Astrologer\AccountController@assignment')->name('assignment');
        Route::get('meeting','Astrologer\AccountController@meeting')->name('meeting');
        Route::get('quizzes','Astrologer\AccountController@quizzes')->name('quizzes');
        Route::get('financial','Astrologer\AccountController@financial')->name('financial');
        Route::get('question','Astrologer\AccountController@question')->name('question');
        Route::get('courses', 'Astrologer\AccountController@purchasecourses' )->name('purchasecourse');
        Route::get('course/{slug}/explore', 'Astrologer\AccountController@exploreCourse')->name('course.explore');
        Route::post('course/{course_id}/module/{module_id}', 'Astrologer\AccountController@canWatchModule')->name('course.fetchTypeAndUrl');
        Route::post('course/{course_id}/module/{module_id}/content/{content_id}/video/{video_id}', 'Astrologer\AccountController@howMuchWatch')->name('course.watched');
        Route::post('course/{course_id}/module/{module_id}/passed', 'Astrologer\AccountController@passedModule')->name('course.module.passed');
        Route::get('course/{course_slug}/meeting', 'Astrologer\AccountController@meetings' )->name('course.meeting');
        Route::get('course/{course_slug}/{type}', 'Astrologer\AccountController@assignments' )->name('course.assignment');
        Route::get('course/{course_slug}/{type}/{module_id}', 'Astrologer\AccountController@assignmentQuestions' )->name('course.assignment.questions');
        Route::post('course/{course_slug}/{type}/{module_id}/question/{question_id}', 'Astrologer\AccountController@fetchAssignmentQuestion' )->name('course.assignment.question.fetch');
        Route::post('course/{course_slug}/{type}/{module_id}/submit', 'Astrologer\AccountController@submitAssignmentQuestion' )->name('course.assignment.submit');
        Route::get('course/{course_slug}/{type}/module/{module_id}/result', 'Astrologer\AccountController@courseResult' )->name('course.result');
        Route::get('course/{course_slug}/module/{module_id}/certificate', 'Astrologer\AccountController@courseCertificate' )->name('course.certificate');
        Route::get('newsletter', 'Astrologer\AccountController@newsletterr')->name('newsletter');
        Route::get('{status}/newsletter', 'Astrologer\AccountController@subscribe')->name('newsletter.status');
        Route::get('ads','Astrologer\AccountController@ads')->name('ads');
        Route::post('ads/submit', 'Astrologer\AccountController@submitAd' )->name('ads.submit');
        Route::get('support','Astrologer\AccountController@support')->name('support');
        Route::get('notification','Astrologer\AccountController@notification')->name('notification');
        Route::post('support', 'Astrologer\AccountController@submitSupport' );
        Route::post('support/{support_id}/replies', 'Astrologer\AccountController@fetchSupportReplies' )->name('support.replies');
        Route::post('support/{support_id}/replies/submit', 'Astrologer\AccountController@supportReplySubmit')->name('support.replies.submit');

        Route::get('{field}/im-{status}','Astrologer\AccountController@updateStatus')->name('im.status');

        Route::get('wishlists', 'Astrologer\AccountController@wishlists' )->name('wishlist');
        Route::get('orders', 'Astrologer\AccountController@purchaseitem' )->name('purchase.item');
        Route::get('order/{order_id}/detail', 'Astrologer\AccountController@itemDetail' )->name('order.detail');
        Route::get('status', 'Astrologer\AccountController@status')->name('status');
        Route::get('chat', 'Astrologer\AccountController@chat')->name('chat');
        Route::get('earnings', 'Astrologer\AccountController@earnings')->name('earnings');
        Route::post('withdraw-request-submit', 'Astrologer\AccountController@submitWithrawRequest')->name('withdraw.request.submit');
        Route::get('payout-histories', 'Astrologer\AccountController@withdraws')->name('withdraws');
        Route::get('puja', 'Astrologer\AccountController@puja')->name('puja');
        Route::get('booked-puja', 'Astrologer\AccountController@bookedPuja')->name('booked.puja');
        Route::get('booked-puja/{id}/detail', 'Astrologer\AccountController@bookedPujaDetail')->name('booked.puja.detail');
        Route::get('booked-puja-media', 'Astrologer\AccountController@getPujaMedia')->name('booked.puja.media.get');
        Route::post('booked-puja-media/upload', 'Astrologer\AccountController@uploadPujaMedia')->name('booked.puja.media.upload');
        Route::post('booked-puja-media', 'Astrologer\AccountController@savePujaMedia')->name('booked.puja.media.save');
        Route::get('group-puja-families', 'Astrologer\AccountController@getGroupPujaFamilies')->name('group.puja.families');
        Route::post('group-puja-media', 'Astrologer\AccountController@saveGroupPujaMedia')->name('group.puja.media.save');
        Route::get('booked-puja-mahurat', 'Astrologer\AccountController@getPujaMahurat')->name('booked.puja.mahurat.get');
        Route::post('booked-puja-mahurat', 'Astrologer\AccountController@savePujaMahurat')->name('booked.puja.mahurat.save');
        Route::post('booked-puja-image/upload', 'Astrologer\AccountController@uploadPujaImage')->name('booked.puja.image.upload');
        Route::post('booked-puja-video/upload', 'Astrologer\AccountController@uploadPujaVideo')->name('booked.puja.video.upload');
        Route::delete('booked-puja-media/delete', 'Astrologer\AccountController@deletePujaMedia')->name('booked.puja.media.delete');
        Route::get('puja-earnings', 'Astrologer\AccountController@pujaEarnings')->name('puja.earnings');
        Route::get('puja-earnings/pdf', 'Astrologer\AccountController@pujaEarningsPdf')->name('puja.earnings.pdf');

        Route::get('current-appointment', 'Astrologer\AccountController@currentAppointment' )->name('currentappointment');
        Route::get('update-login-time', 'Astrologer\AccountController@updateLoginTime' )->name('updatelogintime');

        //booked consultation
        Route::get('consultations','Astrologer\AccountController@consultations')->name('consultations');

        //chat
        Route::get('/{room_id}/chat', 'Astrologer\AccountController@startchat')->name('startchat');
        Route::post('can-chat','Astrologer\AccountController@canChat')->name('canchat');
        Route::post('chat-logs','Astrologer\AccountController@fetchChatLogs')->name('chatlogs');
        Route::post('send-message','Astrologer\AccountController@sendMessage')->name('sendmessage');

        //call
        Route::get('/{room_id}/phone-call', 'Astrologer\AccountController@startPhoneCall')->name('startphonecall');
        Route::post('can-phone-call','Astrologer\AccountController@canPhoneCall')->name('canphonecall');
        Route::post('update-phone-call-status','Astrologer\AccountController@updatePhoneCallStatus')->name('updatephonecallstatus');

        //video
        Route::get('/{room_id}/video-call', 'Astrologer\AccountController@startVideoCall')->name('startvideocall');
        Route::post('can-video-call','Astrologer\AccountController@canVideoCall')->name('canvideocall');
        Route::post('update-video-call-status','Astrologer\AccountController@updateVideoCallStatus')->name('updatevideocallstatus');

        Route::post('{id}/chat/{slug}/{user_type}','CallChatController@chatrequest')->name('chat.initiate');
        Route::post('submit/consultation-request', 'Astrologer\AccountController@consultationRequest' )->name('consultation.request');

        // Remedies suggestion
        Route::get('remedies/get-products-reports', 'Astrologer\AccountController@getProductsAndReports')->name('remedies.get-products-reports');
        Route::post('remedies/suggest', 'Astrologer\AccountController@suggestRemedies')->name('remedies.suggest');

        //live
        Route::group(['prefix'=>'live', 'as'=>'live.'], function(){
          Route::get('/','Astrologer\AccountController@liveIndex')->name('index');
          Route::post('schedule-live-stream','Astrologer\AccountController@scheduleLiveStream')->name('schedulelivestream');
          Route::get('video','Astrologer\AccountController@liveVideo')->name('video');
          Route::post('update','Astrologer\AccountController@liveStreamUpdate')->name('update');
        });

        Route::get('deletion', 'Astrologer\AccountController@deletion')->name('deletion');
        Route::post('deletion', 'Astrologer\AccountController@deletionConfirm')->name('deletion.confirm');

        Route::get('referral-listing', 'Astrologer\AccountController@referralListing')->name('referral.listing');

        // New Referral Module Routes for Astrologers
        Route::get('withdrawal', 'WithdrawalController@index')->name('withdrawal');
        Route::post('withdrawal', 'WithdrawalController@submit')->name('withdrawal.submit');

    });
});

//google auth route
Route::get('auth/google', 'Astrologer\LoginController@redirectToGoogle')->name('auth.google');
Route::get('auth/google/callback', 'Astrologer\LoginController@handleGoogleCallback');
