<?php
// use App\Http\Controllers\Admin\{ProductController,CourseVideoController,CallMeetingController};
Route::prefix('astrovyraadmin')->group(function() {
  Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
  // Route::get('/', 'AdminController@index')->name('admin.dashboard');
  Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

  // Password reset routes
  Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
  Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
  Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
  Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
});

Route::group(['prefix' => 'astrovyraadmin',  'middleware' => 'auth:admin', 'as'=>'admin.'], function()
{
    Route::get('/', 'AdminController@index')->name('dashboard');
    Route::get('/online', 'AdminController@checkStatus')->name('check.status');
    Route::get('/search', 'AdminController@search')->name('search.list');
    Route::get('/transaction-report/download', 'AdminController@downloadTransactionReport')->name('transaction.report.download');

    Route::get('dashboard-revenue/{segment}/list', 'Admin\DashboardRevenueController@list')
      ->whereIn('segment', ['products', 'puja', 'reports'])
      ->name('dashboard.revenue.list');
    Route::get('dashboard-revenue/{segment}', 'Admin\DashboardRevenueController@index')
      ->whereIn('segment', ['products', 'puja', 'reports'])
      ->name('dashboard.revenue.index');
    Route::get('dashboard-free-kundali/list', 'Admin\DashboardRevenueController@freeKundaliList')
      ->name('dashboard.free_kundali.list');
    Route::get('dashboard-free-kundali', 'Admin\DashboardRevenueController@freeKundaliIndex')
      ->name('dashboard.free_kundali.index');
    Route::get('dashboard-free-kundali-matching/list', 'Admin\DashboardRevenueController@freeKundaliMatchingList')
      ->name('dashboard.free_kundali_matching.list');
    Route::get('dashboard-free-kundali-matching', 'Admin\DashboardRevenueController@freeKundaliMatchingIndex')
      ->name('dashboard.free_kundali_matching.index');

    //shippment
    Route::post('check-pincode-serviceabilty', 'Admin\OrderController@checkPincodeServiceability')->name('pincode.serviceabilty');
    Route::post('create-shippment', 'Admin\OrderController@createConsignment')->name('create.shippment');
    Route::post('cancel-shippment', 'Admin\OrderController@cancelShippment')->name('cancel.shippment');
    Route::post('track-order', 'Admin\OrderController@trackOrder')->name('track.order');
    //order request
    Route::post('re-pickup-request', 'Admin\OrderController@rePickupRequest')->name('order.repickuprequest');
    Route::post('order-request-response/submit', 'Admin\OrderController@submitRequestResponse')->name('order.request.response.submit');
    Route::post('order-return-received', 'Admin\OrderController@orderReturnReceived')->name('order.return.received');
    Route::post('order-payment-status', 'Admin\OrderController@handlePaymentStatus')->name('order.paymentstatus');
    Route::get('order-slip-download/{invoice_id}', 'Admin\OrderController@downloadSlip')->name('order.downloadslip');
    Route::get('order-export', 'Admin\OrderController@exportCsv')->name('order.export');


    //shippingweightprice
    // Route::get('shipping-price/weight','Admin\ShippingWeightPriceController@index')->name('shippingweightprice.index');
    // Route::get('shipping-price/weight/list', 'Admin\ShippingWeightPriceController@all')->name('shippingweightprice.all');
    // Route::get('shipping-price/weight/add', 'Admin\ShippingWeightPriceController@form')->name('shippingweightprice.add');
    // Route::post('shipping-price/weight/submit', 'Admin\ShippingWeightPriceController@submit')->name('shippingweightprice.submit');
    // Route::get('shipping-price/weight/{id}/edit', 'Admin\ShippingWeightPriceController@form')->name('shippingweightprice.edit');

    // import
    Route::group(['prefix' => 'import', 'as'=>'importfile.'], function(){
      Route::get('/','Admin\ImportFileController@index')->name('index');
      Route::get('list', 'Admin\ImportFileController@all')->name('all');
      Route::post('/submit','Admin\ImportFileController@submit')->name('submit');
      Route::delete('/delete', 'Admin\ImportFileController@delete')->name('delete');
    });

    // language
    Route::group(['prefix' => 'shipping-price/weight', 'as'=>'shippingweightprice.'], function(){
      Route::get('/','Admin\ShippingWeightPriceController@index')->name('index');
      Route::post('/','Admin\ShippingWeightPriceController@submit')->name('submit');
      Route::get('/list', 'Admin\ShippingWeightPriceController@all')->name('list');
      Route::delete('/', 'Admin\ShippingWeightPriceController@delete')->name('delete');
    });

    //shippingvolumeprice
    // Route::get('shipping-price/volume','Admin\ShippingVolumePriceController@index')->name('shippingvolumeprice.index');
    // Route::get('shipping-price/volume/list', 'Admin\ShippingVolumePriceController@all')->name('shippingvolumeprice.all');
    // Route::get('shipping-price/volume/add', 'Admin\ShippingVolumePriceController@form')->name('shippingvolumeprice.add');
    // Route::post('shipping-price/volume/submit', 'Admin\ShippingVolumePriceController@submit')->name('shippingvolumeprice.submit');
    // Route::get('shipping-price/volume/{id}/edit', 'Admin\ShippingVolumePriceController@form')->name('shippingvolumeprice.edit');
    Route::group(['prefix' => 'shipping-price/volume', 'as'=>'shippingvolumeprice.'], function(){
      Route::get('/','Admin\ShippingVolumePriceController@index')->name('index');
      Route::post('/','Admin\ShippingVolumePriceController@submit')->name('submit');
      Route::get('/list', 'Admin\ShippingVolumePriceController@all')->name('list');
      Route::delete('/', 'Admin\ShippingVolumePriceController@delete')->name('delete');
    });

    //localpincode
    // Route::get('pincode/local','Admin\LocalPincodeController@index')->name('localpincode.index');
    // Route::get('pincode/local/list', 'Admin\LocalPincodeController@all')->name('localpincode.all');
    // Route::get('pincode/local/add', 'Admin\LocalPincodeController@form')->name('localpincode.add');
    // Route::post('pincode/local/submit', 'Admin\LocalPincodeController@submit')->name('localpincode.submit');
    // Route::get('pincode/local/{id}/edit', 'Admin\LocalPincodeController@form')->name('localpincode.edit');

    Route::group(['prefix' => 'pincode/local', 'as'=>'localpincode.'], function(){
      Route::get('/','Admin\LocalPincodeController@index')->name('index');
      Route::post('/','Admin\LocalPincodeController@submit')->name('submit');
      Route::get('/list', 'Admin\LocalPincodeController@all')->name('list');
      Route::delete('/', 'Admin\LocalPincodeController@delete')->name('delete');
    });

    //domesticpincode
    // Route::get('pincode/domestic','Admin\DomesticPincodeController@index')->name('domesticpincode.index');
    // Route::get('pincode/domestic/list', 'Admin\DoemsticPincodeController@all')->name('domesticpincode.all');
    // Route::get('pincode/domestic/add', 'Admin\DomesticPincodeController@form')->name('domesticpincode.add');
    // Route::post('pincode/domestic/submit', 'Admin\DomesticPincodeController@submit')->name('domesticpincode.submit');
    // Route::get('pincode/domestic/{id}/edit', 'Admin\DomesticPincodeController@form')->name('domesticpincode.edit');

    Route::group(['prefix' => 'pincode/domestic', 'as'=>'domesticpincode.'], function(){
      Route::get('/','Admin\DomesticPincodeController@index')->name('index');
      Route::post('/','Admin\DomesticPincodeController@submit')->name('submit');
      Route::get('/list', 'Admin\DomesticPincodeController@all')->name('list');
      Route::delete('/', 'Admin\DomesticPincodeController@delete')->name('delete');
    });

    Route::group(['prefix' => '{lang}'], function(){
       // category
       Route::group(['prefix' => 'category', 'as'=>'category.'], function(){
          Route::get('/','Admin\CategoryController@index')->name('index');
          Route::get('list', 'Admin\CategoryController@all')->name('list');
          Route::post('submit', 'Admin\CategoryController@submit')->name('submit');
          Route::delete('delete', 'Admin\CategoryController@delete')->name('delete');
          Route::get('add', 'Admin\CategoryController@form')->name('add');
          Route::get('{id}/edit', 'Admin\CategoryController@form')->name('edit');
          Route::get('{id}/copy', 'Admin\CategoryController@duplicate')->name('copy');
      });

      // Articles
      Route::group(['prefix' => 'article', 'as'=>'article.'], function(){
          Route::get('/','Admin\ArticleController@index')->name('index');
          Route::get('list', 'Admin\ArticleController@all')->name('all');
          Route::get('add', 'Admin\ArticleController@form')->name('add');
          Route::post('submit', 'Admin\ArticleController@submit')->name('submit');
          Route::post('save', 'Admin\ArticleController@save')->name('save');
          Route::delete('delete', 'Admin\ArticleController@delete')->name('delete');
          Route::get('{id}/edit', 'Admin\ArticleController@form')->name('edit');
          Route::get('{id}/copy', 'Admin\ArticleController@duplicate')->name('copy');
      });

      // Course category
      Route::group(['prefix' => 'coursecategory', 'as'=>'coursecategory.'], function(){
        Route::get('/','Admin\CourseCategoryController@index')->name('index');

        Route::get('/','Admin\CourseCategoryController@index')->name('index');
        Route::get('list', 'Admin\CourseCategoryController@all')->name('list');
        Route::post('submit', 'Admin\CourseCategoryController@submit')->name('submit');
        Route::delete('delete', 'Admin\CourseCategoryController@delete')->name('delete');
        Route::get('add', 'Admin\CourseCategoryController@form')->name('add');
        Route::get('{id}/edit', 'Admin\CourseCategoryController@form')->name('edit');
        Route::get('{id}/copy', 'Admin\CourseCategoryController@duplicate')->name('copy');
    });

    // product category
    Route::group(['prefix' => 'productcategory', 'as'=>'productcategory.'], function(){
      Route::get('/all/{productTypeName?}','Admin\ProductCategoryController@index')->name('index');
      Route::get('list/{productTypeName?}', 'Admin\ProductCategoryController@all')->name('list');
      Route::get('add/{productTypeName?}', 'Admin\ProductCategoryController@form')->name('add');
      Route::get('{id}/edit/{productTypeName?}', 'Admin\ProductCategoryController@form')->name('edit');
      Route::post('submit/{productTypeName?}', 'Admin\ProductCategoryController@submit')->name('submit');
      Route::delete('delete', 'Admin\ProductCategoryController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\ProductCategoryController@duplicate')->name('copy');
      Route::group(['prefix'=>'/product/{productTypeName?}','as'=>'product.'], function(){
        Route::get('list', 'Admin\ProductCategoryController@handleCatgoryProductsList')->name('list');
        Route::get('update/product-and-category-id', 'Admin\ProductCategoryController@handleUpdateProductAndCategoryId')->name('categoryid.update');
      });
  });
    });

    // PanditCategory
    Route::group(['prefix' => 'panditcategory', 'as'=>'PanditCategory.'], function(){
      Route::get('/all','Admin\Pandit\PanditCategoryController@index')->name('index');
      Route::get('list', 'Admin\Pandit\PanditCategoryController@all')->name('list');
      Route::get('add', 'Admin\Pandit\PanditCategoryController@form')->name('add');
      Route::get('{id}/edit', 'Admin\Pandit\PanditCategoryController@form')->name('edit');
      Route::post('submit', 'Admin\Pandit\PanditCategoryController@submit')->name('submit');
      Route::delete('delete', 'Admin\Pandit\PanditCategoryController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\Pandit\PanditCategoryController@duplicate')->name('copy');
    });

    // Pandit
    Route::group(['prefix' => 'pandit', 'as'=>'Pandit.'], function(){
      Route::get('/all','Admin\Pandit\PanditController@index')->name('index');
      Route::get('list', 'Admin\Pandit\PanditController@all')->name('list');
      Route::get('add', 'Admin\Pandit\PanditController@form')->name('add');
      Route::get('{id}/edit', 'Admin\Pandit\PanditController@form')->name('edit');
      Route::post('submit', 'Admin\Pandit\PanditController@submit')->name('submit');
      Route::delete('delete', 'Admin\Pandit\PanditController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\Pandit\PanditController@duplicate')->name('copy');
    });

    // vendors
    Route::group(['prefix' => 'vendor', 'as'=>'vendors.'], function(){
      Route::get('/all','Admin\VendorController@index')->name('index');
      Route::get('list', 'Admin\VendorController@all')->name('list');
      Route::get('add', 'Admin\VendorController@form')->name('add');
      Route::get('{id}/edit', 'Admin\VendorController@form')->name('edit');
      Route::post('submit', 'Admin\VendorController@submit')->name('submit');
      Route::delete('delete', 'Admin\VendorController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\VendorController@duplicate')->name('copy');
    });

    // Media
   Route::group(['prefix' => 'media'], function(){
       Route::get('/','Admin\MediaController@index')->name('media');
       Route::post('upload','Admin\MediaController@upload')->name('media.upload');
       Route::get('list', 'Admin\MediaController@all')->name('media.all');
       Route::delete('delete', 'Admin\MediaController@delete')->name('media.delete');
       Route::post('submit', 'Admin\MediaController@submit')->name('media.submit');
       Route::post('fetch', 'Admin\MediaController@fetchByFolder')->name('media.fetch');
       Route::post('newfolder', 'Admin\MediaController@newfolder')->name('media.newfolder');
       Route::post('move', 'Admin\MediaController@moveToFolder')->name('media.move');
   });

   // Role manager routes
    Route::group(['prefix' => 'role', 'as'=>'role.'], function(){
      Route::get('/','Admin\RoleController@index')->name('index');
      Route::post('/','Admin\RoleController@submit')->name('submit');
      Route::get('/list', 'Admin\RoleController@all')->name('list');
      Route::delete('/', 'Admin\RoleController@delete')->name('delete');
    });

    //  Admin Users
    Route::group(['prefix' => 'user', 'as'=>'user.'], function(){
      Route::get('/', 'Admin\UserController@index')->name('index');
      Route::get('list', 'Admin\UserController@all')->name('list');
      Route::get('/add', 'Admin\UserController@form')->name('add');
      Route::get('/{user_id}/edit', 'Admin\UserController@form')->name('edit');
      Route::delete('/delete', 'Admin\UserController@delete')->name('delete');
      Route::post('/save', 'Admin\UserController@submit')->name('submit');
    });

    // module manager routes
    Route::group(['prefix' => 'module', 'as'=>'module.'], function(){
      Route::get('/','Admin\ModuleController@index')->name('index');
      Route::post('/','Admin\ModuleController@submit')->name('submit');
      Route::get('/list', 'Admin\ModuleController@all')->name('list');
      Route::delete('/', 'Admin\ModuleController@delete')->name('delete');
    });

    // Permission manager routes
    Route::group(['prefix' => 'permission', 'as'=>'permission.'], function(){
      Route::get('/','Admin\PermissionController@index')->name('index');
      Route::get('list', 'Admin\PermissionController@all')->name('all');
      Route::get('add', 'Admin\PermissionController@form')->name('add');
      Route::post('submit','Admin\PermissionController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\PermissionController@form')->name('edit');
      Route::delete('delete', 'Admin\PermissionController@delete')->name('delete');
    });


      // Banner
      Route::group(['prefix' => 'banner', 'as'=>'banner.'], function(){
        Route::get('/','Admin\BannerController@index')->name('index');
        Route::get('list', 'Admin\BannerController@all')->name('all');
        Route::post('submit', 'Admin\BannerController@submit')->name('submit');
        Route::delete('delete', 'Admin\BannerController@delete')->name('delete');
        Route::get('add', 'Admin\BannerController@form')->name('add');
        Route::get('{id}/edit', 'Admin\BannerController@form')->name('edit');
        Route::get('{id}/copy', 'Admin\BannerController@duplicate')->name('copy');
    });

    // Badge
    Route::group(['prefix' => 'badge', 'as'=>'badge.'], function(){
      Route::get('/','Admin\BadgeController@index')->name('index');
      Route::get('list', 'Admin\BadgeController@all')->name('all');
      Route::post('submit', 'Admin\BadgeController@submit')->name('submit');
      Route::delete('delete', 'Admin\BadgeController@delete')->name('delete');
      Route::get('add', 'Admin\BadgeController@form')->name('add');
      Route::get('{id}/edit', 'Admin\BadgeController@form')->name('edit');
      Route::get('{id}/copy', 'Admin\BadgeController@duplicate')->name('copy');
    });


    // New Referral Module Admin Routes
    // Referral Dashboard
    Route::group(['prefix' => 'referral_dashboard', 'as'=>'referral_dashboard.'], function(){
      Route::get('/','Admin\ReferralDashboardController@index')->name('index');
    });

    // App Referral Configuration
    Route::group(['prefix' => 'referral_configuration', 'as'=>'referral_configuration.'], function(){
      Route::get('/','Admin\ReferralConfigurationController@index')->name('index');
      Route::post('submit', 'Admin\ReferralConfigurationController@submit')->name('submit');
    });

    // Product Referral Configuration
    Route::group(['prefix' => 'product_referral_configuration', 'as'=>'product_referral_configuration.'], function(){
      Route::get('/','Admin\ProductReferralConfigurationController@index')->name('index');
      Route::post('submit', 'Admin\ProductReferralConfigurationController@submit')->name('submit');
    });

    // Category Commissions
    Route::group(['prefix' => 'category_commissions', 'as'=>'category_commissions.'], function(){
      Route::get('/','Admin\CategoryCommissionController@index')->name('index');
      Route::get('list', 'Admin\CategoryCommissionController@all')->name('all');
      Route::post('submit', 'Admin\CategoryCommissionController@submit')->name('submit');
      Route::delete('delete', 'Admin\CategoryCommissionController@delete')->name('delete');
      Route::get('add', 'Admin\CategoryCommissionController@form')->name('add');
      Route::get('{id}/edit', 'Admin\CategoryCommissionController@form')->name('edit');
    });

    // Referral Transactions
    Route::group(['prefix' => 'referral_transactions', 'as'=>'referral_transactions.'], function(){
      Route::get('/','Admin\ReferralTransactionController@index')->name('index');
      Route::get('list', 'Admin\ReferralTransactionController@all')->name('all');
    });

    // Referral Signups (Users registered using a referral code)
    Route::group(['prefix' => 'referral_signups', 'as'=>'referral_signups.'], function(){
      Route::get('/','Admin\ReferralSignupController@index')->name('index');
      Route::get('list', 'Admin\ReferralSignupController@all')->name('all');
    });

    // Commission Transactions
    Route::group(['prefix' => 'commission_transactions', 'as'=>'commission_transactions.'], function(){
      Route::get('/','Admin\CommissionTransactionController@index')->name('index');
      Route::get('list', 'Admin\CommissionTransactionController@all')->name('all');
    });

    // Referral Withdrawals
    Route::group(['prefix' => 'referral_withdrawals', 'as'=>'referral_withdrawals.'], function(){
      Route::get('/','Admin\ReferralWithdrawalController@index')->name('index');
      Route::get('list', 'Admin\ReferralWithdrawalController@all')->name('all');
      Route::get('astrologer-suggestions', 'Admin\ReferralWithdrawalController@getAstrologerSuggestions')->name('astrologer.suggestions');
      Route::post('approve', 'Admin\ReferralWithdrawalController@approve')->name('approve');
      Route::post('reject', 'Admin\ReferralWithdrawalController@reject')->name('reject');
      Route::post('process', 'Admin\ReferralWithdrawalController@process')->name('process');
    });



  // cms
    Route::group(['prefix' => 'cms', 'as'=>'cms.'], function(){
      Route::get('/','Admin\CmsController@index')->name('index');
      Route::get('list', 'Admin\CmsController@all')->name('list');
      Route::post('submit', 'Admin\CmsController@submit')->name('submit');
      Route::delete('delete', 'Admin\CmsController@delete')->name('delete');
      Route::get('add', 'Admin\CmsController@form')->name('add');
      Route::get('{id}/edit', 'Admin\CmsController@form')->name('edit');
      Route::get('{id}/copy', 'Admin\CmsController@duplicate')->name('copy');
  });
  Route::get('/settings', 'AdminController@settings')->name('settings');
  Route::get('clearcache', function(){
    echo "Please wait...";
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('optimize:clear');
    \Artisan::call('config:cache');
    \Artisan::call('view:cache');
    // \Artisan::call('route:cache');
    return redirect()->back();
  })->name('clearcache');
  Route::post('/settings', 'AdminController@settingsSave');
  Route::get('about-us', 'Admin\CmsController@about')->name('about');

   // Advertisement
   Route::group(['prefix' => 'advertisement', 'as'=>'advertisement.'], function(){
      Route::get('/','Admin\AdvertisementController@index')->name('index');
      Route::get('list', 'Admin\AdvertisementController@all')->name('all');
      Route::post('submit', 'Admin\AdvertisementController@submit')->name('submit');
      Route::delete('delete', 'Admin\AdvertisementController@delete')->name('delete');
      Route::get('add', 'Admin\AdvertisementController@form')->name('add');
      Route::get('{id}/edit', 'Admin\AdvertisementController@form')->name('edit');
      Route::get('{id}/copy', 'Admin\AdvertisementController@duplicate')->name('copy');
  });

   // Paid Advertisement
   Route::group(['prefix' => 'user-advertisement', 'as'=>'paid_advertisement.'], function(){
      Route::get('/','Admin\PaidAdvertisementController@index')->name('index');
      Route::get('list', 'Admin\PaidAdvertisementController@all')->name('all');
      Route::post('submit', 'Admin\PaidAdvertisementController@submit')->name('submit');
      Route::delete('delete', 'Admin\PaidAdvertisementController@delete')->name('delete');
      Route::get('add', 'Admin\PaidAdvertisementController@form')->name('add');
      Route::get('{id}/edit', 'Admin\PaidAdvertisementController@form')->name('edit');
      Route::get('{id}/copy', 'Admin\PaidAdvertisementController@duplicate')->name('copy');
  });

  // meta
  Route::group(['prefix' => 'meta', 'as'=>'meta.'], function(){
    Route::get('/','Admin\MetaController@index')->name('index');
    Route::get('list', 'Admin\MetaController@all')->name('list');
    Route::post('submit', 'Admin\MetaController@submit')->name('submit');
    Route::delete('delete', 'Admin\MetaController@delete')->name('delete');
    Route::get('add', 'Admin\MetaController@form')->name('add');
    Route::get('{id}/edit', 'Admin\MetaController@form')->name('edit');
  });

   // content
   Route::group(['prefix' => 'content', 'as'=>'content.'], function(){
    Route::get('/','Admin\ContentController@index')->name('index');
    Route::get('list', 'Admin\ContentController@all')->name('list');
    Route::post('submit', 'Admin\ContentController@submit')->name('submit');
    Route::delete('delete', 'Admin\ContentController@delete')->name('delete');
    Route::get('add', 'Admin\ContentController@form')->name('add');
    Route::get('{id}/edit', 'Admin\ContentController@form')->name('edit');
  });

  // faq
  Route::group(['prefix' => 'faq', 'as'=>'faq.'], function(){
    Route::get('/','Admin\FaqController@index')->name('index');
    Route::get('list', 'Admin\FaqController@all')->name('list');
    Route::post('submit', 'Admin\FaqController@submit')->name('submit');
    Route::delete('delete', 'Admin\FaqController@delete')->name('delete');
    Route::get('add', 'Admin\FaqController@form')->name('add');
    Route::get('{id}/edit', 'Admin\FaqController@form')->name('edit');
    Route::get('categories', 'Admin\FaqController@getCategory')->name('categories');
    Route::get('{id}/copy', 'Admin\FaqController@duplicate')->name('copy');
  });


   // Zodiac Signs
  Route::group(['prefix' => 'zodiacsigns', 'as'=>'zodiacsigns.'], function(){
    Route::get('/','Admin\ZodiacSignController@index')->name('index');
    Route::get('list', 'Admin\ZodiacSignController@all')->name('all');
    Route::post('submit', 'Admin\ZodiacSignController@submit')->name('submit');
    Route::get('{id}/edit', 'Admin\ZodiacSignController@form')->name('edit');
    Route::get('add', 'Admin\ZodiacSignController@form')->name('add');
    Route::get('page/{parent_slug}/{slug}', 'Admin\ZodiacSignController@page')->name('page');
    Route::post('page/{id}', 'Admin\ZodiacSignController@pageSave')->name('page.save');
    Route::delete('delete', 'Admin\ZodiacSignController@delete')->name('delete');
  });

  // Planets
  Route::group(['prefix' => 'planets', 'as'=>'planets.'], function(){
    Route::get('/','Admin\PlanetController@index')->name('index');
    Route::get('list', 'Admin\PlanetController@all')->name('all');
    Route::post('submit', 'Admin\PlanetController@submit')->name('submit');
    Route::get('add', 'Admin\PlanetController@form')->name('add');
    Route::get('{id}/edit', 'Admin\PlanetController@form')->name('edit');
    Route::delete('delete', 'Admin\PlanetController@delete')->name('delete');
  });

   // Video
   Route::group(['prefix' => 'videos', 'as'=>'videos.'], function(){
    Route::get('/','Admin\VideoController@index')->name('index');
    Route::get('list', 'Admin\VideoController@all')->name('all');
    Route::post('submit', 'Admin\VideoController@submit')->name('submit');
    Route::delete('delete', 'Admin\VideoController@delete')->name('delete');
    Route::get('add', 'Admin\VideoController@form')->name('add');
    Route::get('{id}/edit', 'Admin\VideoController@form')->name('edit');
    Route::get('fetch', 'Admin\VideoController@videoDetails')->name('fetch');
  });

  // palmreading
  Route::group(['prefix' => 'palmreading', 'as'=>'palmreading.'], function(){
    Route::get('/','Admin\PalmreadingController@index')->name('index');
    Route::get('list', 'Admin\PalmreadingController@all')->name('all');
    Route::post('submit', 'Admin\PalmreadingController@submit')->name('submit');
    Route::get('add', 'Admin\PalmreadingController@form')->name('add');
    Route::get('{id}/edit', 'Admin\PalmreadingController@form')->name('edit');
    Route::delete('delete', 'Admin\PalmreadingController@delete')->name('delete');
  });

  // remedies
  Route::group(['prefix' => 'remedies', 'as'=>'remedies.'], function(){
    Route::get('/','Admin\RemediesController@index')->name('index');
    Route::get('list', 'Admin\RemediesController@all')->name('all');
    Route::post('submit', 'Admin\RemediesController@submit')->name('submit');
    Route::get('add', 'Admin\RemediesController@form')->name('add');
    Route::get('{id}/edit', 'Admin\RemediesController@form')->name('edit');
    Route::delete('delete', 'Admin\RemediesController@delete')->name('delete');
  });

  // suggested remedies by astrologer
  Route::group(['prefix' => 'suggested-remedies', 'as'=>'suggested_remedies.'], function(){
    Route::get('/','Admin\SuggestedRemediesController@index')->name('index');
    Route::get('list', 'Admin\SuggestedRemediesController@all')->name('all');
    Route::get('{id}/show', 'Admin\SuggestedRemediesController@show')->name('show');
  });

  // vastu
  Route::group(['prefix' => 'vastu', 'as'=>'vastu.'], function(){
    Route::get('/','Admin\VastuController@index')->name('index');
    Route::get('/main','Admin\VastuController@main')->name('main');
    Route::get('list-main', 'Admin\VastuController@allMain')->name('all.main');
    Route::get('list', 'Admin\VastuController@all')->name('all');
    Route::post('submit', 'Admin\VastuController@submit')->name('submit');
    Route::get('add', 'Admin\VastuController@form')->name('add');
    Route::get('{id}/edit', 'Admin\VastuController@form')->name('edit');
    Route::delete('delete', 'Admin\VastuController@delete')->name('delete');
    Route::get('{id}/copy', 'Admin\VastuController@duplicate')->name('copy');

    //packages
    Route::get('{service_id}/package','Admin\VastuServicePackageController@index')->name('package');
    Route::get('{service_id}/package/list', 'Admin\VastuServicePackageController@all')->name('package.all');
    Route::get('{service_id}/package/add', 'Admin\VastuServicePackageController@form')->name('package.add');
    Route::post('{service_id}/package/submit', 'Admin\VastuServicePackageController@submit')->name('package.submit');
    Route::get('{service_id}/package/{id}/edit', 'Admin\VastuServicePackageController@form')->name('package.edit');
    Route::delete('{service_id}/package/delete', 'Admin\VastuServicePackageController@delete')->name('package.delete');
    Route::get('{service_id}/package/{id}/copy', 'Admin\VastuServicePackageController@duplicate')->name('package.copy');

    // procedures
    Route::group(['prefix' => 'procedure', 'as'=>'procedure.'], function(){
      Route::get('/','Admin\VastuProcedureController@index')->name('index');
      Route::get('list', 'Admin\VastuProcedureController@all')->name('all');
      Route::post('submit', 'Admin\VastuProcedureController@submit')->name('submit');
      Route::get('add', 'Admin\VastuProcedureController@form')->name('add');
      Route::get('{id}/edit', 'Admin\VastuProcedureController@form')->name('edit');
      Route::delete('delete', 'Admin\VastuProcedureController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\VastuProcedureController@duplicate')->name('copy');
    });
  });

  // ngsoftware
  Route::group(['prefix' => 'cms2', 'as'=>'software.'], function(){
    Route::get('/','Admin\NgSoftwareController@index')->name('index');
    Route::get('list', 'Admin\NgSoftwareController@all')->name('all');
    Route::post('submit', 'Admin\NgSoftwareController@submit')->name('submit');
    Route::get('add', 'Admin\NgSoftwareController@form')->name('add');
    Route::get('{id}/edit', 'Admin\NgSoftwareController@form')->name('edit');
    Route::delete('delete', 'Admin\NgSoftwareController@delete')->name('delete');

    //packages
    Route::get('{data_id}/package','Admin\ModulePackageController@index')->name('package');
    Route::get('{data_id}/package/list', 'Admin\ModulePackageController@all')->name('package.all');
    Route::get('{data_id}/package/add', 'Admin\ModulePackageController@form')->name('package.add');
    Route::post('{data_id}/package/submit', 'Admin\ModulePackageController@submit')->name('package.submit');
    Route::get('{data_id}/package/{id}/edit', 'Admin\ModulePackageController@form')->name('package.edit');
    Route::delete('{data_id}/package/delete', 'Admin\ModulePackageController@delete')->name('package.delete');
    Route::get('{data_id}/package/{id}/copy', 'Admin\ModulePackageController@duplicate')->name('package.copy');
    // Route::get('shipping-price/{id}/copy', 'Admin\ShippingPriceController@duplicate')->name('shippingprice.copy');
    // Route::delete('shipping-price/delete', 'Admin\ShippingPriceController@delete')->name('shippingprice.delete');

    //features
    Route::get('{data_id}/feature','Admin\ModuleFeatureController@index')->name('feature');
    Route::get('{data_id}/feature/list', 'Admin\ModuleFeatureController@all')->name('feature.all');
    Route::get('{data_id}/feature/add', 'Admin\ModuleFeatureController@form')->name('feature.add');
    Route::post('{data_id}/feature/submit', 'Admin\ModuleFeatureController@submit')->name('feature.submit');
    Route::get('{data_id}/feature/{id}/edit', 'Admin\ModuleFeatureController@form')->name('feature.edit');
    Route::delete('{data_id}/feature/delete', 'Admin\ModuleFeatureController@delete')->name('feature.delete');
    Route::get('{data_id}/feature/{id}/copy', 'Admin\ModuleFeatureController@duplicate')->name('feature.copy');

    //features
    Route::get('{data_id}/procedure','Admin\ModuleProcedureController@index')->name('procedure');
    Route::get('{data_id}/procedure/list', 'Admin\ModuleProcedureController@all')->name('procedure.all');
    Route::get('{data_id}/procedure/add', 'Admin\ModuleProcedureController@form')->name('procedure.add');
    Route::post('{data_id}/procedure/submit', 'Admin\ModuleProcedureController@submit')->name('procedure.submit');
    Route::get('{data_id}/procedure/{id}/edit', 'Admin\ModuleProcedureController@form')->name('procedure.edit');
    Route::delete('{data_id}/procedure/delete', 'Admin\ModuleProcedureController@delete')->name('procedure.delete');
    Route::get('{data_id}/procedure/{id}/copy', 'Admin\ModuleProcedureController@duplicate')->name('procedure.copy');

    //features
    Route::get('{data_id}/videos','Admin\DemoVideoController@index')->name('video');
    Route::get('{data_id}/videos/list', 'Admin\DemoVideoController@all')->name('video.all');
    Route::get('{data_id}/videos/add', 'Admin\DemoVideoController@form')->name('video.add');
    Route::post('{data_id}/videos/submit', 'Admin\DemoVideoController@submit')->name('video.submit');
    Route::get('{data_id}/videos/{id}/edit', 'Admin\DemoVideoController@form')->name('video.edit');
    Route::delete('{data_id}/videos/delete', 'Admin\DemoVideoController@delete')->name('video.delete');
  });
  // wedding
  Route::group(['prefix' => 'weddings', 'as'=>'weddings.'], function(){
    Route::get('/','Admin\WeddingController@index')->name('index');
    Route::get('list', 'Admin\WeddingController@all')->name('all');
    Route::post('submit', 'Admin\WeddingController@submit')->name('submit');
    Route::get('add', 'Admin\WeddingController@form')->name('add');
    Route::get('{id}/edit', 'Admin\WeddingController@form')->name('edit');
    Route::delete('delete', 'Admin\WeddingController@delete')->name('delete');
  });
  // One to One Puja
  Route::group(['prefix' => 'one-to-one-puja', 'as'=>'one_to_one_puja.'], function(){
    Route::get('/','Admin\OneToOnePujaController@index')->name('index');
    Route::get('list', 'Admin\OneToOnePujaController@all')->name('all');
    Route::post('submit', 'Admin\OneToOnePujaController@submit')->name('submit');
    Route::delete('delete', 'Admin\OneToOnePujaController@delete')->name('delete');
    Route::get('add', 'Admin\OneToOnePujaController@form')->name('add');
    Route::get('{id}/edit', 'Admin\OneToOnePujaController@form')->name('edit');
  });

  // puja
  Route::group(['prefix' => 'puja', 'as'=>'puja.'], function(){
    Route::get('/','Admin\PujaController@index')->name('index');
    Route::get('list', 'Admin\PujaController@all')->name('all');
    Route::post('submit', 'Admin\PujaController@submit')->name('submit');
    Route::get('add', 'Admin\PujaController@form')->name('add');
    Route::get('{id}/edit', 'Admin\PujaController@form')->name('edit');
    Route::delete('delete', 'Admin\PujaController@delete')->name('delete');
    Route::get('{id}/copy', 'Admin\PujaController@duplicate')->name('copy');
  });
  // mantra
  Route::group(['prefix' => 'mantra', 'as'=>'mantra.'], function(){
    Route::get('/','Admin\MantraController@index')->name('index');
    Route::get('list', 'Admin\MantraController@all')->name('all');
    Route::post('submit', 'Admin\MantraController@submit')->name('submit');
    Route::get('add', 'Admin\MantraController@form')->name('add');
    Route::get('{id}/edit', 'Admin\MantraController@form')->name('edit');
    Route::delete('delete', 'Admin\MantraController@delete')->name('delete');
    Route::get('{id}/copy', 'Admin\MantraController@duplicate')->name('copy');
  });

  // chakra
  Route::group(['prefix' => 'chakra', 'as'=>'chakra.'], function(){
    Route::get('/','Admin\ChakraController@index')->name('index');
    Route::get('list', 'Admin\ChakraController@all')->name('all');
    Route::post('submit', 'Admin\ChakraController@submit')->name('submit');
    Route::get('add', 'Admin\ChakraController@form')->name('add');
    Route::get('{id}/edit', 'Admin\ChakraController@form')->name('edit');
    Route::delete('delete', 'Admin\ChakraController@delete')->name('delete');
  });

    // color
    Route::group(['prefix' => 'color', 'as'=>'color.'], function(){
      Route::get('/','Admin\ColorTherapyController@index')->name('index');
      Route::get('list', 'Admin\ColorTherapyController@all')->name('all');
      Route::post('submit', 'Admin\ColorTherapyController@submit')->name('submit');
      Route::get('add', 'Admin\ColorTherapyController@form')->name('add');
      Route::get('{id}/edit', 'Admin\ColorTherapyController@form')->name('edit');
      Route::delete('delete', 'Admin\ColorTherapyController@delete')->name('delete');
    });
    // meditation
    Route::group(['prefix' => 'meditation', 'as'=>'meditation.'], function(){
      Route::get('/','Admin\MeditationController@index')->name('index');
      Route::get('list', 'Admin\MeditationController@all')->name('all');
      Route::post('submit', 'Admin\MeditationController@submit')->name('submit');
      Route::get('add', 'Admin\MeditationController@form')->name('add');
      Route::get('{id}/edit', 'Admin\MeditationController@form')->name('edit');
      Route::delete('delete', 'Admin\MeditationController@delete')->name('delete');
    });
    // yoga
    Route::group(['prefix' => 'yoga', 'as'=>'yoga.'], function(){
      Route::get('/','Admin\YogaController@index')->name('index');
      Route::get('list', 'Admin\YogaController@all')->name('all');
      Route::post('submit', 'Admin\YogaController@submit')->name('submit');
      Route::get('add', 'Admin\YogaController@form')->name('add');
      Route::get('{id}/edit', 'Admin\YogaController@form')->name('edit');
      Route::delete('delete', 'Admin\YogaController@delete')->name('delete');
    });
    // festival
    Route::group(['prefix' => 'festival', 'as'=>'festival.'], function(){
      Route::get('/','Admin\FestivalController@index')->name('index');
      Route::get('list', 'Admin\FestivalController@all')->name('all');
      Route::post('submit', 'Admin\FestivalController@submit')->name('submit');
      Route::get('add', 'Admin\FestivalController@form')->name('add');
      Route::get('{id}/edit', 'Admin\FestivalController@form')->name('edit');
      Route::delete('delete', 'Admin\FestivalController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\FestivalController@duplicate')->name('copy');
    });
    // language
    Route::group(['prefix' => 'language', 'as'=>'language.'], function(){
      Route::get('/','Admin\LanugageController@index')->name('index');
      Route::post('/','Admin\LanugageController@submit')->name('submit');
      Route::get('/list', 'Admin\LanugageController@all')->name('list');
      Route::delete('/', 'Admin\LanugageController@delete')->name('delete');
    });
    // service
    Route::group(['prefix' => 'service', 'as'=>'service.'], function(){
      Route::get('/','Admin\ServiceController@index')->name('index');
      Route::post('/','Admin\ServiceController@submit')->name('submit');
      Route::get('/list', 'Admin\ServiceController@all')->name('list');
      Route::delete('/', 'Admin\ServiceController@delete')->name('delete');
    });

    Route::group(['prefix' => 'form', 'as' => 'form.'], function(){
      Route::get('/', 'Admin\FormFieldsController@index')->name('index');
      Route::post('/','Admin\FormFieldsController@submit')->name('submit');
      Route::get('/list', 'Admin\FormFieldsController@all')->name('list');
      Route::delete('/', 'Admin\FormFieldsController@delete')->name('delete');
    });

    // horoscope-daily
    Route::group(['prefix' => 'horoscope-daily', 'as'=>'horoscope-daily.'], function(){
      Route::get('/','Admin\HoroscopeDailyController@index')->name('index');
      Route::get('list', 'Admin\HoroscopeDailyController@all')->name('all');
      Route::post('submit', 'Admin\HoroscopeDailyController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\HoroscopeDailyController@form')->name('edit');
    });

    // horoscope-weekly
    Route::group(['prefix' => 'horoscope-weekly', 'as'=>'horoscope-weekly.'], function(){
      Route::get('/','Admin\HoroscopeWeeklyController@index')->name('index');
      Route::get('list', 'Admin\HoroscopeWeeklyController@all')->name('all');
      Route::post('submit', 'Admin\HoroscopeWeeklyController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\HoroscopeWeeklyController@form')->name('edit');
    });

    // horoscope-monthly
    Route::group(['prefix' => 'horoscope-monthly', 'as'=>'horoscope-monthly.'], function(){
      Route::get('/','Admin\HoroscopeMonthlyController@index')->name('index');
      Route::get('list', 'Admin\HoroscopeMonthlyController@all')->name('all');
      Route::post('submit', 'Admin\HoroscopeMonthlyController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\HoroscopeMonthlyController@form')->name('edit');
    });

    // horoscope-yearly
    Route::group(['prefix' => 'horoscope-yearly', 'as'=>'horoscope-yearly.'], function(){
      Route::get('/','Admin\HoroscopeYearlyController@index')->name('index');
      Route::get('list', 'Admin\HoroscopeYearlyController@all')->name('all');
      Route::post('submit', 'Admin\HoroscopeYearlyController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\HoroscopeYearlyController@form')->name('edit');
    });

    // Products for Astrologer (display in Astrologer Profile - Top Selling)
    Route::group(['prefix' => 'products-for-astrologer', 'as' => 'products_for_astrologer.'], function(){
      Route::get('/', 'Admin\ProductsForAstrologerController@index')->name('index');
      Route::get('list', 'Admin\ProductsForAstrologerController@all')->name('all');
      Route::post('submit', 'Admin\ProductsForAstrologerController@submit')->name('submit');
    });

    // Astrologers
    Route::group(['prefix' => 'Astrologer', 'as'=>'Astrologer.'], function(){
      Route::get('/','Admin\AstrologerController@index')->name('index');
      Route::get('list', 'Admin\AstrologerController@all')->name('all');
      Route::get('active-list', 'Admin\AstrologerController@activeList')->name('activeList');
      Route::get('new-requests', 'Admin\AstrologerController@newRequestsList')->name('newRequestsList');
      Route::get('suggestions', 'Admin\AstrologerController@getAstrologerSuggestions')->name('suggestions');
      Route::post('submit', 'Admin\AstrologerController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\AstrologerController@form')->name('edit');
      Route::get('{id}/copy', 'Admin\AstrologerController@duplicate')->name('copy');
      Route::get('add', 'Admin\AstrologerController@form')->name('add');
      Route::delete('delete', 'Admin\AstrologerController@delete')->name('delete');
      Route::get('{id}/restore', 'Admin\AstrologerController@restore')->name('restore');
      Route::get('{id}/switch', 'Admin\AstrologerController@switch')->name('switch');
      Route::get('export', 'Admin\AstrologerController@exportCsv')->name('export');
    });
    // Instructor
    Route::group(['prefix' => 'AI', 'as'=>'AI.'], function(){
      Route::get('/','Admin\AstrologerController@index')->name('index');
      Route::get('list', 'Admin\AstrologerController@all')->name('all');
      Route::get('active-list', 'Admin\AstrologerController@activeAiList')->name('activeList');
      Route::get('suggestions', 'Admin\AstrologerController@getAstrologerSuggestions')->name('suggestions');
      Route::post('submit', 'Admin\AstrologerController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\AstrologerController@form')->name('edit');
      Route::get('add', 'Admin\AstrologerController@form')->name('add');
      Route::delete('delete', 'Admin\AstrologerController@delete')->name('delete');
      Route::get('{id}/restore', 'Admin\AstrologerController@restore')->name('restore');
      Route::get('{id}/copy', 'Admin\AstrologerController@duplicate')->name('copy');
      Route::get('{id}/switch', 'Admin\AstrologerController@switch')->name('switch');
      Route::get('export', 'Admin\AstrologerController@exportCsv')->name('export');
    });
    /*
    // Instructor
    Route::group(['prefix' => 'instructor', 'as'=>'instructor.'], function(){
      Route::get('/','Admin\InstructorController@index')->name('index');
      Route::get('list', 'Admin\InstructorController@all')->name('all');
      Route::post('submit', 'Admin\InstructorController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\InstructorController@form')->name('edit');
      Route::get('add', 'Admin\InstructorController@form')->name('add');
      Route::delete('delete', 'Admin\InstructorController@delete')->name('delete');
      Route::get('{id}/restore', 'Admin\InstructorController@restore')->name('restore');
    });
    */

    // Temp Astrologers
    Route::group(['prefix' => 'temp/astrologer', 'as'=>'temp_astrologer.'], function(){
      Route::get('/','Admin\TempAstroController@index')->name('index');
      Route::get('list', 'Admin\TempAstroController@all')->name('all');
      Route::post('submit', 'Admin\TempAstroController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\TempAstroController@form')->name('edit');
      Route::delete('delete', 'Admin\TempAstroController@delete')->name('delete');
    });

    // Temp Instuctors
    Route::group(['prefix' => 'temp/instructor', 'as'=>'temp_instructor.'], function(){
      Route::get('/','Admin\TempAstroController@index')->name('index');
      Route::get('list', 'Admin\TempAstroController@all')->name('all');
      Route::post('submit', 'Admin\TempAstroController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\TempAstroController@form')->name('edit');
      Route::delete('delete', 'Admin\TempAstroController@delete')->name('delete');
    });

    // Astrologer Live
    Route::group(['prefix' => 'astrologer-live', 'as'=>'astrologerlive.'], function(){
      Route::get('/all','Admin\AstrologerLiveController@index')->name('index');
      Route::get('list', 'Admin\AstrologerLiveController@all')->name('list');
      Route::get('add', 'Admin\AstrologerLiveController@form')->name('add');
      Route::get('{id}/edit', 'Admin\AstrologerLiveController@form')->name('edit');
      Route::get('{id}/view', 'Admin\AstrologerLiveController@viewData')->name('view');
      Route::post('submit', 'Admin\AstrologerLiveController@submit')->name('submit');
      Route::delete('delete', 'Admin\AstrologerLiveController@delete')->name('delete');
    });

    // Customers
    Route::group(['prefix' => 'customer', 'as'=>'customer.'], function(){
      Route::get('/','Admin\CustomerController@index')->name('index');
      Route::get('list', 'Admin\CustomerController@all')->name('all');
      Route::post('submit', 'Admin\CustomerController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\CustomerController@form')->name('edit');
      Route::get('add', 'Admin\CustomerController@form')->name('add');
      Route::delete('delete', 'Admin\CustomerController@delete')->name('delete');
      Route::get('{id}/restore', 'Admin\CustomerController@restore')->name('restore');
      Route::get('export', 'Admin\CustomerController@exportCsv')->name('export');
      Route::get('save-shipping-address', 'Admin\CustomerController@saveShippingAddress')->name('saveshippingaddress');
      Route::get('delete-shipping-address', 'Admin\CustomerController@deleteShippingAddress')->name('deleteshippingaddress');

      //courses
      Route::get('{user_id}/course','Admin\LmsCourseController@index')->name('course');
      Route::get('{user_id}/course/list', 'Admin\LmsCourseController@all')->name('course.all');
      Route::get('{user_id}/course/add', 'Admin\LmsCourseController@form')->name('course.add');
      Route::post('{user_id}/course/submit', 'Admin\LmsCourseController@submit')->name('course.submit');
      Route::get('{user_id}/course/{id}/edit', 'Admin\LmsCourseController@form')->name('course.edit');
      Route::delete('{user_id}/course/delete', 'Admin\LmsCourseController@delete')->name('course.delete');
      Route::get('{user_id}/course/{id}/copy', 'Admin\LmsCourseController@duplicate')->name('course.copy');
      Route::get('{user_id}/course/{id}/pass', 'Admin\LmsCourseController@passCourseAndGenerateCertificate')->name('course.pass');
      Route::get('{user_id}/course/{id}/certificate', 'Admin\LmsCourseController@downloadCertificate')->name('course.certificate.download');

      //cartitems
      Route::get('{user_id}/cart-items','Admin\CartItemController@index')->name('cartitem');
      Route::get('{user_id}/cart-items/list', 'Admin\CartItemController@all')->name('cartitem.all');
      Route::post('{user_id}/cart-items/submit', 'Admin\CartItemController@submit')->name('cartitem.submit');
      Route::get('{user_id}/cart-items/{id}/detail', 'Admin\CartItemController@form')->name('cartitem.edit');
      Route::delete('{user_id}/cart-items/delete', 'Admin\CartItemController@delete')->name('cartitem.delete');
    });

     // punchang
     Route::group(['prefix' => 'punchang', 'as'=>'punchang.'], function(){
      Route::get('/','Admin\PunchangController@index')->name('index');
      Route::get('list', 'Admin\PunchangController@all')->name('all');
      Route::post('submit', 'Admin\PunchangController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\PunchangController@form')->name('edit');
      Route::get('add', 'Admin\PunchangController@form')->name('add');
      Route::delete('delete', 'Admin\PunchangController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\PunchangController@duplicate')->name('copy');
    });
// CAll History
    Route::group(['prefix' => 'callhistory', 'as'=>'callhistory.'], function(){
      Route::get('/','Admin\CallHistoryController@index')->name('index');
      Route::get('list', 'Admin\CallHistoryController@all')->name('all');
      Route::get('{id}/view', 'Admin\CallHistoryController@viewData')->name('view');
    });
    Route::get('/fetch-call-details/{sid}', 'ExotelController@fetchCallDetails');

    // consultations
    Route::group(['prefix' => 'consultation', 'as'=>'consultation.'], function(){
      //video
      Route::get('{type}/video/','Admin\MeetingScheduleController@index')->name('video.index'); // schedulemeeting
      Route::get('{type}/video/list', 'Admin\MeetingScheduleController@all')->name('video.all');
      Route::get('{type}/video/{id}/view', 'Admin\MeetingScheduleController@viewData')->name('video.view');
      Route::post('{type}/video/submit', 'Admin\MeetingScheduleController@submit')->name('video.submit');
      //call
      Route::get('{type}/call/','Admin\CallMeetingController@index')->name('call.index'); // callmeeting
      Route::get('{type}/call/list', 'Admin\CallMeetingController@all')->name('call.all');
      Route::get('{type}/call/{id}/view', 'Admin\CallMeetingController@viewData')->name('call.view');
      Route::post('{type}/call/submit', 'Admin\CallMeetingController@submit')->name('call.submit');
      //chat
      Route::get('{type}/chat/','Admin\ChatMeetingController@index')->name('chat.index'); // chatmeeting
      Route::get('{type}/chat/list', 'Admin\ChatMeetingController@all')->name('chat.all');
      Route::get('{type}/chat/{id}/view', 'Admin\ChatMeetingController@viewData')->name('chat.view');
      Route::post('{type}/chat/submit', 'Admin\ChatMeetingController@submit')->name('chat.submit');


      Route::post('/date', 'Admin\CallMeetingController@date')->name('date');
      Route::post('/time', 'Admin\CallMeetingController@time')->name('time');
    });

    // ai consultations
    Route::group(['prefix' => 'ai-consultation', 'as'=>'aiconsultation.'], function(){
      //chat
      Route::get('{type}/chat/','Admin\AiChatConsultationController@index')->name('chat.index'); // chatmeeting
        Route::get('{type}/chat/list', 'Admin\AiChatConsultationController@all')->name('chat.all');
      Route::get('{type}/chat/{id}/view', 'Admin\AiChatConsultationController@viewData')->name('chat.view');
      Route::post('{type}/chat/submit', 'Admin\AiChatConsultationController@submit')->name('chat.submit');
    });

// Recharge Plans
    Route::group(['prefix' => 'rechargeplans', 'as'=>'rechargeplans.'], function(){
      Route::get('/','Admin\RechargePlansController@index')->name('index');
      Route::get('list', 'Admin\RechargePlansController@all')->name('all');
      Route::post('submit', 'Admin\RechargePlansController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\RechargePlansController@form')->name('edit');
      Route::get('add', 'Admin\RechargePlansController@form')->name('add');
      Route::delete('delete', 'Admin\RechargePlansController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\RechargePlansController@duplicate')->name('copy');
    });

// Payout Package
    Route::group(['prefix' => 'payoutpackages', 'as'=>'payoutpackages.'], function(){
      Route::get('/','Admin\PayoutController@index')->name('index');
      Route::get('list', 'Admin\PayoutController@all')->name('all');
      Route::post('submit', 'Admin\PayoutController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\PayoutController@form')->name('edit');
      Route::get('add', 'Admin\PayoutController@form')->name('add');
      Route::delete('delete', 'Admin\PayoutController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\PayoutController@duplicate')->name('copy');
    });
    // Coupons
    Route::group(['prefix' => 'coupons', 'as'=>'coupons.'], function(){
      Route::get('/','Admin\CouponController@index')->name('index');
      Route::get('list', 'Admin\CouponController@all')->name('all');
      Route::post('submit', 'Admin\CouponController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\CouponController@form')->name('edit');
      Route::get('add', 'Admin\CouponController@form')->name('add');
      Route::delete('delete', 'Admin\CouponController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\CouponController@duplicate')->name('copy');
      Route::get('apply', 'Admin\CouponController@apply')->name('apply');
    });

    // Withdraw
    Route::group(['prefix' => 'withdraw', 'as'=>'withdraw.'], function(){
      Route::get('/','Admin\WithdrawController@index')->name('index');
      Route::get('list', 'Admin\WithdrawController@all')->name('all');
      Route::post('submit', 'Admin\WithdrawController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\WithdrawController@form')->name('edit');
      Route::get('add', 'Admin\WithdrawController@form')->name('add');
      Route::delete('delete', 'Admin\WithdrawController@delete')->name('delete');
    });

    // Gift
    Route::group(['prefix' => 'gift', 'as'=>'gift.'], function(){
      Route::get('/','Admin\GiftController@index')->name('index');
      Route::get('list', 'Admin\GiftController@all')->name('all');
      Route::post('submit', 'Admin\GiftController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\GiftController@form')->name('edit');
      Route::get('add', 'Admin\GiftController@form')->name('add');
      Route::delete('delete', 'Admin\GiftController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\GiftController@duplicate')->name('copy');
    });

    // Notifications
    Route::group(['prefix' => 'notification', 'as'=>'notification.'], function(){
      Route::get('/{user_id}/{astrologer_id}','Admin\NotificationController@index')->name('index');
      Route::get('list/{user_id}/{astrologer_id}', 'Admin\NotificationController@all')->name('all');
      Route::post('submit', 'Admin\NotificationController@submit')->name('submit');
      Route::get('{id}/edit/{user_id}/{astrologer_id}', 'Admin\NotificationController@form')->name('edit');
      Route::get('add/{user_id}/{astrologer_id}', 'Admin\NotificationController@form')->name('add');
      Route::delete('delete/{user_id}/{astrologer_id}', 'Admin\NotificationController@delete')->name('delete');
      Route::get('{id}/copy/{user_id}/{astrologer_id}', 'Admin\NotificationController@duplicate')->name('copy');
    });
    // landing page
    Route::group(['prefix' => 'landing', 'as'=>'landing.'], function(){
      Route::get('/','Admin\LandingPageController@index')->name('index');
      Route::get('list', 'Admin\LandingPageController@all')->name('all');
      Route::post('submit', 'Admin\LandingPageController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\LandingPageController@form')->name('edit');
      Route::get('add', 'Admin\LandingPageController@form')->name('add');
      Route::delete('delete', 'Admin\LandingPageController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\LandingPageController@duplicate')->name('copy');
    });

    // Panchange
    Route::group(['prefix' => 'gyan-ganga', 'as'=>'panchange.'], function(){
      Route::get('/','Admin\PanchangController@index')->name('index');
      Route::get('list', 'Admin\PanchangController@all')->name('all');
      Route::post('submit', 'Admin\PanchangController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\PanchangController@form')->name('edit');
      Route::get('add', 'Admin\PanchangController@form')->name('add');
      Route::delete('delete', 'Admin\PanchangController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\PanchangController@duplicate')->name('copy');
    });

      Route::group(['prefix' => 'landing-page', 'as'=>'landingpage.'], function(){
      Route::get('/','Admin\LandingController@index')->name('index');
      Route::get('list', 'Admin\LandingController@all')->name('all');
      Route::post('submit', 'Admin\LandingController@submit')->name('submit');
      Route::get('{id}/edit', 'Admin\LandingController@form')->name('edit');
      Route::get('add', 'Admin\LandingController@form')->name('add');
      Route::delete('delete', 'Admin\LandingController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\LandingController@duplicate')->name('copy');
      Route::get('{id}/editor', 'Admin\LandingController@editor')->name('editor');
      Route::post('{id}/pagesave', 'Admin\LandingController@pagesave')->name('pagesave.save');
    });


    // landing page
   Route::group(['prefix' => 'landingdata', 'as'=>'landing.'], function(){
      Route::get('data/{slug}/','Admin\LandingPageDataController@index')->name('page.index');
      Route::get('data/{slug}/list', 'Admin\LandingPageDataController@all')->name('page.all');
      Route::post('data/{slug}/submit', 'Admin\LandingPageDataController@submit')->name('page.submit');
      Route::get('data/{slug}/{id}/edit', 'Admin\LandingPageDataController@form')->name('page.edit');
      Route::delete('data/{slug}/delete', 'Admin\LandingPageDataController@delete')->name('page.delete');
    });


    Route::group(['prefix' => '', 'as'=>'payment.'], function(){
      Route::get('payment','Admin\PaymentController@index')->name('list.index');
      Route::get('payment/list', 'Admin\PaymentController@all')->name('page.all');
      Route::delete('data/delete', 'Admin\PaymentController@delete')->name('page.delete');
    });
    // Enquries
    Route::group(['prefix' => 'enquiry', 'as'=>'enquiry.'], function(){
      Route::get('/','Admin\EnquiriesController@index')->name('index');
      Route::get('list', 'Admin\EnquiriesController@all')->name('all');
      Route::get('{id}/view', 'Admin\EnquiriesController@viewData')->name('view');
      Route::delete('delete', 'Admin\EnquiriesController@delete')->name('delete');
    });

    // EnquiryCmsTwo
    Route::group(['prefix' => 'enquiry/data/ngsoftware', 'as'=>'enquirycmstwo.'], function(){
      Route::get('/','Admin\EnquiryCmsTwoController@index')->name('index');
      Route::get('list', 'Admin\EnquiryCmsTwoController@all')->name('all');
      Route::get('{id}/view', 'Admin\EnquiryCmsTwoController@viewData')->name('view');
      Route::delete('delete', 'Admin\EnquiryCmsTwoController@delete')->name('delete');
    });

    // EnquiryVastu
    Route::group(['prefix' => 'enquiry/data/vastu', 'as'=>'enquiryvastu.'], function(){
      Route::get('/','Admin\VastuEnquiryController@index')->name('index');
      Route::get('list', 'Admin\VastuEnquiryController@all')->name('all');
      Route::get('{id}/view', 'Admin\VastuEnquiryController@viewData')->name('view');
      Route::delete('delete', 'Admin\VastuEnquiryController@delete')->name('delete');
    });

    // EnquiryAstrology
    Route::group(['prefix' => 'enquiry/data/astrology', 'as'=>'enquiryastrology.'], function(){
      Route::get('/','Admin\EnquiryAstrologyController@index')->name('index');
      Route::get('list', 'Admin\EnquiryAstrologyController@all')->name('all');
      Route::get('{id}/view', 'Admin\EnquiryAstrologyController@viewData')->name('view');
      Route::delete('delete', 'Admin\EnquiryAstrologyController@delete')->name('delete');
    });

    // NewsLetter Subscribers
    Route::group(['prefix' => 'subscribers', 'as'=>'subscribers.'], function(){
      Route::get('/','Admin\SubscriberController@index')->name('index');
      Route::get('list', 'Admin\SubscriberController@all')->name('all');
      Route::get('{id}/view', 'Admin\SubscriberController@viewData')->name('view');
    });

    Route::group(['prefix' => 'feedback', 'as'=>'feedback.'], function(){
      Route::get('/','Admin\FeedbackController@index')->name('index');
      Route::get('list', 'Admin\FeedbackController@all')->name('all');
      Route::get('{id}/view', 'Admin\FeedbackController@viewData')->name('view');
    });

    // Courses
    Route::group(['prefix' => 'course'], function(){

      Route::get('/','Admin\CourseController@index')->name('course');
      Route::get('list', 'Admin\CourseController@all')->name('course.all');
      Route::post('submit', 'Admin\CourseController@submit')->name('course.submit');
      Route::delete('delete', 'Admin\CourseController@delete')->name('course.delete');
      Route::get('add', 'Admin\CourseController@form')->name('course.add');
      Route::get('{id}/edit', 'Admin\CourseController@form')->name('course.edit');
      Route::get('{id}/copy', 'Admin\CourseController@duplicate')->name('course.copy');

      // Course Review

      Route::get('review','Admin\CourseController@courseReview')->name('course.review');
      Route::get('review/list','Admin\CourseController@reviewAll')->name('course.review.all');
      Route::get('review/{id}/edit','Admin\CourseController@reviewForm')->name('course.review.edit');
      Route::post('review/submit','Admin\CourseController@reviewFormSubmit')->name('course.review.submit');

       //Course video
       Route::get('{course_id}/video','Admin\CourseVideoController@index')->name('course.video');
       Route::get('{course_id}/video/list','Admin\CourseVideoController@all')->name('course.video.all');
       Route::post('{course_id}/video/submit', 'Admin\CourseVideoController@submit')->name('course.video.submit');
       Route::delete('{course_id}/video/delete', 'Admin\CourseVideoController@delete')->name('course.video.delete');
       Route::get('{course_id}/video/add','Admin\CourseVideoController@form')->name('course.video.add');
       Route::get('{course_id}/video/{id}/edit', 'Admin\CourseVideoController@form')->name('course.video.edit');
       //Course benifits from
       Route::get('{course_id}/benifit','Admin\CourseBenifitController@index')->name('course.benifit');
       Route::get('{course_id}/benifit/list','Admin\CourseBenifitController@all')->name('course.benifit.all');
       Route::post('{course_id}/benifit/submit', 'Admin\CourseBenifitController@submit')->name('course.benifit.submit');
       Route::delete('{course_id}/benifit/delete', 'Admin\CourseBenifitController@delete')->name('course.benifit.delete');
       Route::get('{course_id}/benifit/add','Admin\CourseBenifitController@form')->name('course.benifit.add');
       Route::get('{course_id}/benifit/{id}/edit', 'Admin\CourseBenifitController@form')->name('course.benifit.edit');
       Route::get('{course_id}/benifit/{id}/copy', 'Admin\CourseBenifitController@duplicate')->name('course.benifit.copy');
       // Courses Modules
      Route::get('{course_id}/module','Admin\CourseModuleController@index')->name('course.module');
      Route::get('{course_id}/module/list', 'Admin\CourseModuleController@all')->name('course.module.all');
      Route::post('{course_id}/module/submit', 'Admin\CourseModuleController@submit')->name('course.module.submit');
      Route::delete('{course_id}/module/delete', 'Admin\CourseModuleController@delete')->name('course.module.delete');
      Route::get('{course_id}/module/add', 'Admin\CourseModuleController@form')->name('course.module.add');
      Route::get('{course_id}/module/{id}/edit', 'Admin\CourseModuleController@form')->name('course.module.edit');
      Route::get('{course_id}/module/{id}/copy', 'Admin\CourseModuleController@duplicate')->name('course.module.copy');

      //Courses Video preview
      Route::get('{course_id}/module/{module_id}/content','Admin\CourseModuleContentController@index')->name('course.module.content');
      Route::get('{course_id}/module/{module_id}/content/list', 'Admin\CourseModuleContentController@all')->name('course.module.content.all');
      Route::get('{course_id}/module/{module_id}/content/add', 'Admin\CourseModuleContentController@form')->name('course.module.content.add');
      Route::post('{course_id}/module/{module_id}/content/submit', 'Admin\CourseModuleContentController@submit')->name('course.module.content.submit');
      Route::get('{course_id}/module/{module_id}/content/{id}/edit', 'Admin\CourseModuleContentController@form')->name('course.module.content.edit');
      Route::get('{course_id}/module/{module_id}/content/{id}/copy', 'Admin\CourseModuleContentController@duplicate')->name('course.module.content.copy');
      Route::delete('{course_id}/module/{module_id}/content/delete', 'Admin\CourseModuleContentController@delete')->name('course.module.content.delete');
      Route::post('{course_id}/module/content/{module_id}/file/upload','Admin\CourseModuleContentController@upload')->name('course.module.content.file.upload');

      //Courses Video preview
      Route::get('{course_id}/module/{module_id}/content/{content_id}/{faq_type}','Admin\CourseModuleQuestionController@index')->name('course.module.content.question');
      Route::get('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/list', 'Admin\CourseModuleQuestionController@all')->name('course.module.content.question.all');
      Route::get('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/add', 'Admin\CourseModuleQuestionController@form')->name('course.module.content.question.add');
      Route::post('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/submit', 'Admin\CourseModuleQuestionController@submit')->name('course.module.content.question.submit');
      Route::get('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/{id}/edit', 'Admin\CourseModuleQuestionController@form')->name('course.module.content.question.edit');
      Route::delete('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/delete', 'Admin\CourseModuleQuestionController@delete')->name('course.module.content.question.delete');
      Route::get('{course_id}/module/{module_id}/content/{content_id}/{faq_type}/{id}/copy', 'Admin\CourseModuleQuestionController@duplicate')->name('course.module.content.question.copy');

      //Courses Video preview
      Route::get('{course_id}/module/{module_id}/preview','Admin\CoursePreviewController@index')->name('course.module.preview');
      Route::get('{course_id}/module/{module_id}/preview/list', 'Admin\CoursePreviewController@all')->name('course.module.preview.all');
      Route::post('{course_id}/module/{module_id}/preview/submit', 'Admin\CoursePreviewController@submit')->name('course.module.preview.submit');
      Route::delete('{course_id}/module/{module_id}/preview/delete', 'Admin\CoursePreviewController@delete')->name('course.module.preview.delete');
      Route::get('{course_id}/module/{module_id}/preview/add', 'Admin\CoursePreviewController@form')->name('course.module.preview.add');
      Route::get('{course_id}/module/{module_id}/preview/{id}/edit', 'Admin\CoursePreviewController@form')->name('course.module.preview.edit');


      // Courses Modules Questions
      Route::get('{course_id}/module/{module_id}/question','Admin\CoursePreviewController@index')->name('course.module.question');
      Route::get('{course_id}/module/{module_id}/question/list', 'Admin\CoursePreviewController@all')->name('course.module.question.all');
      Route::post('{course_id}/module/{module_id}/question/submit', 'Admin\CoursePreviewController@submit')->name('course.module.question.submit');
      Route::delete('{course_id}/module/{module_id}/question/delete', 'Admin\CoursePreviewController@delete')->name('course.module.question.delete');
      Route::get('{course_id}/module/{module_id}/question/add', 'Admin\CoursePreviewController@form')->name('course.module.question.add');
      Route::get('{course_id}/module/{module_id}/question/{id}/edit', 'Admin\CoursePreviewController@form')->name('course.module.question.edit');


       // Courses Modules Files
      Route::get('{course_id}/module/{module_id}/file','Admin\CourseModuleFileController@index')->name('course.module.file');
      Route::get('{course_id}/module/{module_id}/file/list', 'Admin\CourseModuleFileController@all')->name('course.module.file.all');
      Route::post('{course_id}/module/{module_id}/file/submit', 'Admin\CourseModuleFileController@submit')->name('course.module.file.submit');
      Route::delete('{course_id}/module/{module_id}/file/delete', 'Admin\CourseModuleFileController@delete')->name('course.module.file.delete');
      Route::get('{course_id}/module/{module_id}/file/add', 'Admin\CourseModuleFileController@form')->name('course.module.file.add');
      Route::get('{course_id}/module/{module_id}/file/{id}/edit', 'Admin\CourseModuleFileController@form')->name('course.module.file.edit');
      Route::post('module/file/upload','Admin\CourseModuleFileController@upload')->name('course.module.file.upload');
 });

    // product Modules
    Route::group(['prefix' => 'product', 'as'=>'product.'], function(){
      Route::get('/all/{productTypeName?}','Admin\ProductController@index')->name('index');
      Route::get('list/{productTypeName?}', 'Admin\ProductController@all')->name('all');
      Route::get('add/{productTypeName?}', 'Admin\ProductController@alldata')->name('add');
      Route::get('{id}/edit/{productTypeName?}', 'Admin\ProductController@alldata')->name('edit');
      Route::post('submit/{productTypeName?}', 'Admin\ProductController@submit')->name('submit');
      Route::delete('delete', 'Admin\ProductController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\ProductController@duplicate')->name('copy');
      Route::get('search/tag', 'Admin\ProductController@getTags')->name('search.tag');
      Route::get('export/csv', 'Admin\ProductController@exportCsv')->name('exportcsv');
      Route::get('list/export/csv/{productTypeName?}', 'Admin\ProductController@exportListCsv')->name('exportlist');
      Route::get('change-order', 'Admin\ProductController@handleChangeOrder')->name('handlechangeorder');


      Route::get('/{p_id?}/{productTypeName?}','Admin\ProductController@index')->name('variation.index');
      Route::get('list/{p_id?}/{productTypeName?}', 'Admin\ProductController@all')->name('variation.all');
      Route::get('add/{p_id?}/{productTypeName?}', 'Admin\ProductController@alldata')->name('variation.add');
      Route::get('{id}/edit/{p_id?}/{productTypeName?}', 'Admin\ProductController@alldata')->name('variation.edit');
      Route::post('submit/{p_id?}/{productTypeName?}', 'Admin\ProductController@submit')->name('variation.submit');
      Route::delete('delete/{p_id?}', 'Admin\ProductController@delete')->name('variation.delete');
      Route::get('{id}/copy/{p_id?}', 'Admin\ProductController@duplicate')->name('variation.copy');
      Route::get('list/export/csv/{p_id?}/{productTypeName?}', 'Admin\ProductController@exportListCsv')->name('variation.exportlist');
    });

    // product review
    Route::get('product-review','Admin\ProductController@review')->name('product.review');
    Route::get('product-review/list','Admin\ProductController@reviewAll')->name('product.review.all');
    Route::get('product-review/{id}/edit','Admin\ProductController@edit')->name('product.review.edit');
    Route::post('product-review/submit','Admin\ProductController@reviewSubmit')->name('product.review.submit');

    // Booked Puja
    Route::group(['prefix' => 'booked-puja', 'as' => 'booked-puja.'], function(){
      Route::get('/', 'Admin\BookedPujaController@index')->name('index');
      Route::get('list', 'Admin\BookedPujaController@all')->name('all');
      Route::get('{id}/edit', 'Admin\BookedPujaController@edit')->name('edit');
      Route::get('group/family-media', 'Admin\BookedPujaController@getGroupFamilyMedia')->name('group.family-media');
      Route::get('group/{product_id}/bookings', 'Admin\BookedPujaController@groupBookings')->name('group-bookings');
      Route::get('group/{product_id}/bookings/pdf', 'Admin\BookedPujaController@groupBookingsPdf')->name('group-bookings.pdf');
      Route::post('update', 'Admin\BookedPujaController@update')->name('update');
      Route::post('media/bulk-approve', 'Admin\BookedPujaController@bulkApproveMedia')->name('media.bulk-approve');
      Route::delete('media/delete', 'Admin\BookedPujaController@deleteMedia')->name('media.delete');
      Route::post('media/upload', 'Admin\BookedPujaController@uploadMedia')->name('media.upload');
      Route::post('video/upload', 'Admin\BookedPujaController@uploadVideo')->name('video.upload');
    });

     // Hsn
     Route::group(['prefix' => 'hsn', 'as'=>'hsn.'], function(){
       Route::get('/','Admin\HsnCodeController@index')->name('index');
       Route::get('list', 'Admin\HsnCodeController@all')->name('all');
       Route::get('add', 'Admin\HsnCodeController@form')->name('add');
       Route::get('{id}/edit', 'Admin\HsnCodeController@form')->name('edit');
       Route::post('submit', 'Admin\HsnCodeController@submit')->name('submit');
       Route::delete('delete', 'Admin\HsnCodeController@delete')->name('delete');
       Route::get('{id}/copy', 'Admin\HsnCodeController@duplicate')->name('copy');
     });

     // DeliveryDate
     Route::group(['prefix' => 'delivery-date', 'as'=>'DeliveryDate.'], function(){
       Route::get('/all','Admin\DeliveryDateController@index')->name('index');
       Route::get('list', 'Admin\DeliveryDateController@all')->name('list');
       Route::get('add', 'Admin\DeliveryDateController@form')->name('add');
       Route::get('{id}/edit', 'Admin\DeliveryDateController@form')->name('edit');
       Route::post('submit', 'Admin\DeliveryDateController@submit')->name('submit');
       Route::delete('delete', 'Admin\DeliveryDateController@delete')->name('delete');
       Route::get('{id}/copy', 'Admin\DeliveryDateController@duplicate')->name('copy');
     });

    // Astrologer Review
    Route::get('{type}-review','Admin\AstrologerController@astrologerReview')->name('astrologer.review');
    Route::get('{type}-review/list','Admin\AstrologerController@reviewAll')->name('astrologer.review.all');
    Route::get('{type}/{id}/review/edit','Admin\AstrologerController@reviewForm')->name('astrologer.review.edit');
    Route::post('{type}/review/submit','Admin\AstrologerController@formSubmit')->name('astrologer.review.submit');

    // Testimonial
    Route::group(['prefix' => 'testimonial', 'as'=>'testimonial.'], function(){
      Route::get('/', 'Admin\TestimonialController@index')->name('list');
      Route::get('add','Admin\TestimonialController@form')->name('add');
      Route::post('submit', 'Admin\TestimonialController@submit')->name('submit');
      Route::get('list', 'Admin\TestimonialController@all')->name('all');
      Route::get('{id}/edit', 'Admin\TestimonialController@form')->name('edit');
      Route::delete('delete', 'Admin\TestimonialController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\TestimonialController@duplicate')->name('copy');
    });
    Route::group(['prefix' => 'video-testimonial', 'as'=>'videotestimonial.'], function(){
      Route::get('/', 'Admin\VideoTestimonialController@index')->name('list');
      Route::get('add','Admin\VideoTestimonialController@form')->name('add');
      Route::post('submit', 'Admin\VideoTestimonialController@submit')->name('submit');
      Route::get('list', 'Admin\VideoTestimonialController@all')->name('all');
      Route::get('{id}/edit', 'Admin\VideoTestimonialController@form')->name('edit');
      Route::delete('delete', 'Admin\VideoTestimonialController@delete')->name('delete');
      Route::get('{id}/copy', 'Admin\VideoTestimonialController@duplicate')->name('copy');
    });

    // Support
    Route::group(['prefix' => 'support'], function(){
      // Tickets
      Route::get('/','Admin\SupportController@index')->name('support');
      Route::get('list', 'Admin\SupportController@all')->name('support.all');
      Route::get('add', 'Admin\SupportController@form')->name('support.add');
      Route::post('submit', 'Admin\SupportController@submit')->name('support.submit');
      Route::get('{id}/edit', 'Admin\SupportController@form')->name('support.edit');
      Route::delete('delete', 'Admin\SupportController@delete')->name('support.delete');
      Route::post('{id}/replieslist', 'Admin\SupportController@replies')->name('support.replies.list');
      // Route::get('{id}/copy', 'Admin\SupportController@duplicate')->name('support.copy');
        // Replies
      Route::get('{support_id}/replies','Admin\SupportRepiesController@index')->name('support.replies');
      Route::get('{support_id}/replies/list', 'Admin\SupportRepiesController@all')->name('support.replies.all');
      Route::get('{support_id}/replies/add', 'Admin\SupportRepiesController@form')->name('support.replies.add');
      Route::post('{support_id}/replies/submit', 'Admin\SupportRepiesController@submit')->name('support.replies.submit');
      Route::get('{support_id}/replies/{id}/edit', 'Admin\SupportRepiesController@form')->name('support.replies.edit');
      Route::delete('{support_id}/replies/delete', 'Admin\SupportRepiesController@delete')->name('support.replies.delete');
      // Route::get('{support_id}/replies/{id}/copy', 'Admin\SupportRepiesController@duplicate')->name('support.replies.copy');
    });

    //orders
    Route::get('orders','Admin\OrderController@index')->name('order');
    Route::get('orders/list', 'Admin\OrderController@all')->name('order.all');
    Route::post('orders/submit', 'Admin\OrderController@submit')->name('order.submit');
    Route::get('orders/{id}/view', 'Admin\OrderController@form')->name('order.edit');
    Route::delete('orders/delete', 'Admin\OrderController@delete')->name('order.delete');
    Route::get('orders/redict-to-checkout', 'Admin\OrderController@redirectToCheckout')->name('order.redirecttocheckout');
    Route::get('order/create', 'Admin\OrderController@create')->name('order.create');
    Route::group(['prefix'=>'orders/product','as'=>'order.product.'], function(){
      Route::get('list', 'Admin\OrderController@productsList')->name('list');
      Route::get('add', 'Admin\OrderController@addProductToAdminSideCartItem')->name('add');
      Route::get('edit', 'Admin\OrderController@editAdminSideCartItem')->name('edit');
      Route::get('increase-quantity', 'Admin\OrderController@increaseQuantityAdminSideCartItem')->name('increase.quantity');
      Route::get('decrease-quantity', 'Admin\OrderController@decreaseQuantityAdminSideCartItem')->name('decrease.quantity');
      Route::get('remove', 'Admin\OrderController@removeProductFromAdminSideCartItem')->name('remove');
      Route::get('cart-list', 'Admin\OrderController@adminSideCartItemList')->name('cartlist');
    });
    Route::group(['prefix'=>'orders/customer','as'=>'order.customer.'], function(){
      Route::get('list', 'Admin\OrderController@usersList')->name('list');
      Route::get('edit', 'Admin\OrderController@editUser')->name('edit');
      Route::get('place', 'Admin\OrderController@placeOrder')->name('place');
      Route::get('generate-checkout-link', 'Admin\OrderController@generateCheckoutLink')->name('generatecheckoutlink');
    });

    //user_recharge
    Route::get('user-recharge/','Admin\UserRechargeController@index')->name('user_recharge');
    Route::get('user-recharge/list', 'Admin\UserRechargeController@all')->name('user_recharge.all');
    // Route::get('add', 'Admin\OrderController@form')->name('order.add');
    Route::post('user-recharge/submit', 'Admin\UserRechargeController@submit')->name('user_recharge.submit');
    Route::get('user-recharge/{id}/view', 'Admin\UserRechargeController@form')->name('user_recharge.edit');

    //earning reports
    Route::group(['prefix'=>'earning-reports','as'=>'earning-reports.'], function(){
      Route::get('astrologer', 'Admin\EarningReportController@astrologerReport')->name('astrologer');
      Route::get('astrologer/list', 'Admin\EarningReportController@astrologerReportList')->name('astrologer.list');
      Route::get('astrologer/pdf', 'Admin\EarningReportController@astrologerReportPdf')->name('astrologer.pdf');
      Route::get('astrologer-earnings', 'Admin\EarningReportController@astrologerEarningsReport')->name('astrologer-earnings');
      Route::get('astrologer-earnings/list', 'Admin\EarningReportController@astrologerEarningsReportList')->name('astrologer-earnings.list');
      Route::get('astrologer-earnings/pdf', 'Admin\EarningReportController@astrologerEarningsReportPdf')->name('astrologer-earnings.pdf');
      Route::get('astrologer-withdrawals', 'Admin\EarningReportController@astrologerWithdrawalsReport')->name('astrologer-withdrawals');
      Route::get('astrologer-withdrawals/list', 'Admin\EarningReportController@astrologerWithdrawalsReportList')->name('astrologer-withdrawals.list');
      Route::get('astrologer-withdrawals/pdf', 'Admin\EarningReportController@astrologerWithdrawalsReportPdf')->name('astrologer-withdrawals.pdf');
      Route::get('astrologer-suggestions', 'Admin\EarningReportController@getAstrologerSuggestions')->name('astrologer.suggestions');
      Route::get('user', 'Admin\EarningReportController@userReport')->name('user');
      Route::get('user/list', 'Admin\EarningReportController@userReportList')->name('user.list');
      Route::get('user/pdf', 'Admin\EarningReportController@userReportPdf')->name('user.pdf');
      Route::get('influencer', 'Admin\EarningReportController@influencerReport')->name('influencer');
      Route::get('influencer/list', 'Admin\EarningReportController@influencerReportList')->name('influencer.list');
      Route::get('influencer/pdf', 'Admin\EarningReportController@influencerReportPdf')->name('influencer.pdf');
      Route::get('view-detail/{id}', 'Admin\EarningReportController@viewDetail')->name('view-detail');
    });
    Route::delete('user-recharge/delete', 'Admin\UserRechargeController@delete')->name('user_recharge.delete');

    //invoice
    Route::get('invoice/','Admin\InvoiceController@index')->name('invoice');
    Route::get('invoice/list', 'Admin\InvoiceController@all')->name('invoice.all');
    // Route::get('invoice', 'Admin\InvoiceController@form')->name('invoice.add');
    Route::post('invoice/submit', 'Admin\InvoiceController@submit')->name('invoice.submit');
    Route::get('invoice/{id}/view', 'Admin\InvoiceController@form')->name('invoice.edit');
    Route::delete('invoice/delete', 'Admin\InvoiceController@delete')->name('invoice.delete');
    Route::get('invoice/download/{id}', 'Admin\InvoiceController@invoice')->name('invoice.download');

});
//        Route::get('{course_id}/video','Admin\CourseVideoController@index')->name('course.video');
//        Route::get('{course_id}/video/list','Admin\CourseVideoController@all')->name('course.video.all');
//        Route::post('{course_id}/video/submit', 'Admin\CourseVideoController@submit')->name('course.video.submit');
//        Route::delete('{course_id}/video/delete', 'Admin\CourseVideoController@delete')->name('course.video.delete');
//        Route::get('{course_id}/video/add','Admin\CourseVideoController@form')->name('course.video.add');
//        Route::get('{course_id}/video/{id}/edit', 'Admin\CourseVideoController@form')->name('course.video.edit');
