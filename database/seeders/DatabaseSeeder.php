<?php

use Illuminate\Database\Seeder;
use App\Model\{Setting, Role, Module, Attribute, Language, Service, PayoutPackage, HsnCode};
use App\{Admin, User, Form};

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        if(Admin::count()==0) {
            $role = Role::firstOrCreate(['name'=>'Super Admin']);
            Admin::create([
                'name' => 'Admin',
                'email' => 'admin@astrokavach.com',
                'password'=> bcrypt('admin@astroKavach@2025'),
                'status' => 1,
                'role_id' => $role->id
            ]);
            echo "Admin user created";
        }

        $settings = [
            [
                'name' => 'Logo',
                'key' => 'logo',
                'type' => 'image',
                'value'=> '0'
            ],
            [
                'name' => 'Contact No.',
                'key' => 'contact',
                'type' => 'text',
                'value'=> '0000000000/0000000000'
            ],
            [
                'name' => 'WhatsApp No.',
                'key' => 'whatsapp',
                'type' => 'text',
                'value'=> '0000000000'
            ],
            [
                'name' => 'Contact Email',
                'key' => 'contact_email',
                'type' => 'email',
                'value'=> 'dummy@dummy.com'
            ],
            [
                'name' => 'Address',
                'key' => 'address',
                'type' => 'text',
                'value'=> 'Company Address'
            ],
            [
                'name' => 'Director Name',
                'key' => 'director_name',
                'type' => 'text',
                'value'=> 'D. Name'
            ],
            [
                'name' => 'Director Sign',
                'key' => 'director_sign',
                'type' => 'image',
                'value'=> '0'
            ],
            [
                'name' => 'Facebook Link',
                'key' => 'fb_link',
                'type' => 'text',
                'value'=> 'https://www.facebook.com/'
            ],
            [
                'name' => 'Twitter Link',
                'key' => 'tw_link',
                'type' => 'text',
                'value'=> 'https://twitter.com/'
            ],
            [
                'name' => 'Google Link',
                'key' => 'google_link',
                'type' => 'text',
                'value'=> 'https://plus.google.com/discover'
            ],
            [
                'name' => 'Linkedin Link',
                'key' => 'linkedin_link',
                'type' => 'text',
                'value'=> 'https://www.linkedin.com/'
            ],
            [
                'name' => 'Instragram Link',
                'key' => 'instagram_link',
                'type' => 'text',
                'value'=> 'https://www.instagram.com/'
            ],
            [
                'name' => 'Youtube Link',
                'key' => 'youtube_link',
                'type' => 'text',
                'value'=> 'https://www.youtube.com/'
            ],
            [
                'name' => 'Website Title',
                'key' => 'website_title',
                'type' => 'text',
                'value'=> 'Title'
            ],
            [
                'name' => 'Website description',
                'key' => 'description',
                'type' => 'text',
                'value'=> 'description'
            ],
            [
                'name' => 'Website keywords',
                'key' => 'keywords',
                'type' => 'text',
                'value'=> 'keywords'
            ],
            [
                'name' => 'Header Script',
                'key' => 'header_script',
                'type' => 'textarea',
                'value'=> ''
            ],
            [
                'name' => 'Footer Script',
                'key' => 'footer_script',
                'type' => 'textarea',
                'value'=> ''
            ],
            [
                'name' => 'Server (₹)',
                'key' => 'server',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Sms (₹)',
                'key' => 'sms',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Email (₹)',
                'key' => 'email_charges',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Resources (marketing + maintainance) (₹)',
                'key' => 'maintainance_charge',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Call (₹)',
                'key' => 'call_charges',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Video (₹)',
                'key' => 'video_charges',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Chat (₹)',
                'key' => 'chat_charges',
                'type' => 'text',
                'value'=> '0.00'
            ],
            [
                'name' => 'Payment Gateway (%)',
                'key' => 'payment_gateway',
                'type' => 'text',
                'value'=> '0.00'
            ],
            // [
            //     'name' => 'Minimum (₹)',
            //     'key' => 'minumum_withraw_amount',
            //     'type' => 'text',
            //     'value'=> '0.00'
            // ],
            // [
            //     'name' => 'Maximum (₹)',
            //     'key' => 'maximum_withraw_amount',
            //     'type' => 'text',
            //     'value'=> '0.00'
            // ],
            [
                'name' => 'Hold (%)',
                'key' => 'hold_withraw_amount',
                'type' => 'text',
                'value'=> '10'
            ],
            [
                'name' => 'Contact Email',
                'key' => 'contact_email',
                'type' => 'number',
                'value'=> ''
            ],
            [
                'name' => 'Recharge HSN',
                'key' => 'recharge_hsn',
                'type' => 'number',
                'value'=> '996111'
            ],
            // [
            //     'name' => 'Consultation Cancel Before (Hrs)',
            //     'key' => 'cancel_before',
            //     'type' => 'number',
            //     'value'=> ''
            // ],
            [
                'name' => 'Shipping Charge (default)',
                'key' => 'shipping_charges',
                'type' => 'number',
                'value'=> '120'
            ],
            // [
            //     'name' => 'Coruse Brochure',
            //     'key' => 'course_brochure',
            //     'type' => 'file',
            //     'value'=> ''
            // ],
            // [
            //     'name' => 'Welcome Bonus',
            //     'key' => 'welcome_bonus',
            //     'type' => 'number',
            //     'value'=> '0'
            // ],
            [
                'name' => 'Username',
                'key' => 'username',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Email',
                'key' => 'email',
                'type' => 'email',
                'value'=> ''
            ],
            [
                'name' => 'Password',
                'key' => 'password',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Confirm',
                'key' => 'confirm',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Razorpay API Key',
                'key' => 'razorpay_api_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Razorpay API Secret Key',
                'key' => 'razorpay_api_secret_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Razorpay API About',
                'key' => 'razorpay_api_about',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Astro API Key',
                'key' => 'astro_api_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Astro API About',
                'key' => 'astro_api_about',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Agora App ID',
                'key' => 'agora_app_id',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Agora App Certificate',
                'key' => 'agora_app_certificate',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Agora App About',
                'key' => 'agora_app_about',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Open.ai Api Key',
                'key' => 'openai_api_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Open.ai Api About',
                'key' => 'openai_api_about',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Divine Api Key',
                'key' => 'divine_api_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Divine Api Token',
                'key' => 'divine_api_token',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Divine Api About',
                'key' => 'divine_api_about',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Meta Pixel Id (Conversion Api)',
                'key' => 'meta_pixel_id',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Meta CAPI Access Token (Conversion Api)',
                'key' => 'meta_capi_access_token',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Meta About (Conversion Api)',
                'key' => 'meta_about_conversion_api',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Newsletter (user)',
                'key' => 'newsletter_banner',
                'type' => 'image',
                'value'=> '0'
            ],
            [
                'name' => 'Newsletter (astrologer)',
                'key' => 'newsletter_banner_astrologer',
                'type' => 'image',
                'value'=> '0'
            ],
            [
                'name' => 'COD option',
                'key' => 'cod',
                'type' => 'toggle',
                'value'=> '0'
            ],
            [
                'name' => 'Follow Tag',
                'key' => 'follow_tag',
                'type' => 'toggle',
                'value'=> '0'
            ],
            [
                'name' => 'Index Tag',
                'key' => 'index_tag',
                'type' => 'toggle',
                'value'=> '0'
            ],
            [
                'name' => 'Exotel Api Key',
                'key' => 'exotel_api_key',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Exotel Api Token',
                'key' => 'exotel_api_token',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Exotel Api Sid',
                'key' => 'exotel_api_sid',
                'type' => 'text',
                'value'=> ''
            ],
            // [
            //     'name' => 'Exotel Api Caller Id',
            //     'key' => 'exotel_api_caller_id',
            //     'type' => 'text',
            //     'value'=> ''
            // ],
            [
                'name' => 'Youtube Channel Link (Astrologer Training)',
                'key' => 'youtube_channel_link_astrologer_training',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Website URL',
                'key' => 'website_url',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Website Username',
                'key' => 'website_username',
                'type' => 'text',
                'value'=> ''
            ],
            [
                'name' => 'Website Password',
                'key' => 'Website_password',
                'type' => 'text',
                'value'=> ''
            ],
       ];
       $keysArray = [];
       foreach($settings as $setting){
         $keysArray[] = $setting['key'];
         if(Setting::where('key', $setting['key'])->count()==0){
           Setting::create($setting);
         }
       }
       Setting::whereNotIn("key", $keysArray)->delete();
       echo "Setting table updated";

       $modules = [
         [
           'name'=>'Astrologer Categories',
           'key'=>'expertise',
         ],
         [
           'name'=>'Astrologer',
           'key'=>'astrologer',
         ],
         [
           'name'=>'Astrologer Review',
           'key'=>'astrologer-review',
         ],
         [
           'name'=>'Astrologer Video Consultation',
           'key'=>'astrologer-video-consultation',
         ],
         [
           'name'=>'Astrologer Chat Consultation',
           'key'=>'astrologer-chat-consultation',
         ],
         [
           'name'=>'Astrologer Call Consultation',
           'key'=>'astrologer-call-consultation',
         ],
         [
           'name'=>'Astrologer Approval Request',
           'key'=>'temporary-astrologer-data',
         ],
         [
           'name'=>'AI Astrologer',
           'key'=>'ai',
         ],
         [
           'name'=>'AI Astrologer Review',
           'key'=>'ai-review',
         ],
         [
           'name'=>'AI Astrologer Chat Consultation',
           'key'=>'ai-chat-consultation',
         ],
         [
           'name'=>'Recharge Plan',
           'key'=>'recharge-plan',
         ],
         [
           'name'=>'Payout Package',
           'key'=>'payout-package',
         ],
         // [
         //   'name'=>'Course Category',
         //   'key'=>'course-category',
         // ],
         // [
         //   'name'=>'Course',
         //   'key'=>'course',
         // ],
         // [
         //   'name'=>'Course Review',
         //   'key'=>'course-review',
         // ],
         // [
         //   'name'=>'Courses Enquiry',
         //   'key'=>'landing-data-courses',
         // ],
         [
           'name'=>'Product Category',
           'key'=>'product-category',
         ],
         [
           'name'=>'Product',
           'key'=>'product',
         ],
         [
           'name'=>'Product Review',
           'key'=>'product-review',
         ],
         [
           'name'=>'Service Product Category',
           'key'=>'service-product-category',
         ],
         [
           'name'=>'Service Product',
           'key'=>'service-product',
         ],
         [
           'name'=>'Service Product Review',
           'key'=>'service-product-review',
         ],
         [
           'name'=>'Pooja Product Category',
           'key'=>'pooja-product-category',
         ],
         [
           'name'=>'Pooja Product',
           'key'=>'pooja-product',
         ],
         [
           'name'=>'Pooja Product Review',
           'key'=>'pooja-product-review',
         ],
         [
           'name'=>'HSN',
           'key'=>'hsn',
         ],
         [
           'name'=>'Form Field',
           'key'=>'form-field',
         ],
         [
           'name'=>'Delivery Date',
           'key'=>'DeliveryDate',
         ],
         [
           'name'=>'Pandit Category',
           'key'=>'PanditCategory',
         ],
         [
           'name'=>'Pandit',
           'key'=>'Pandit',
         ],
         [
           'name'=>'Order',
           'key'=>'order',
         ],
         [
           'name'=>'CartItems',
           'key'=>'cartitem',
         ],
         [
           'name'=>'Customers Recharge',
           'key'=>'user-recharge',
         ],
         [
           'name'=>'Invoice',
           'key'=>'invoice',
         ],
         [
           'name'=>'Customer',
           'key'=>'customer',
         ],
         [
           'name'=>'Coupon',
           'key'=>'coupon',
         ],
         [
           'name'=>'Gift',
           'key'=>'gift',
         ],
         [
           'name'=>'Banner',
           'key'=>'banner',
         ],
         [
           'name'=>'Page',
           'key'=>'cms',
         ],
         [
           'name'=>'Blog Category',
           'key'=>'blog-category',
         ],
         [
           'name'=>'Blog',
           'key'=>'blog',
         ],
         [
           'name'=>'FAQ',
           'key'=>'faq',
         ],
         [
           'name'=>'Text Testimonial',
           'key'=>'text-testimonial',
         ],
         [
           'name'=>'Video Testimonial',
           'key'=>'video-testimonial',
         ],
         [
           'name'=>'Support',
           'key'=>'support',
         ],
         [
           'name'=>'Price Weight',
           'key'=>'shippingweightprice',
         ],
         [
           'name'=>'Price Volume',
           'key'=>'shippingvolumeprice',
         ],
         [
           'name'=>'Pincode Local',
           'key'=>'localpincode',
         ],
         [
           'name'=>'Pincode Domestic',
           'key'=>'domesticpincode',
         ],
         [
           'name'=>'Contact Enquiry',
           'key'=>'contact-enquiry',
         ],
         [
           'name'=>'NewsLetter',
           'key'=>'newsletter',
         ],
         [
           'name'=>'Notification',
           'key'=>'notification',
         ],
         // [
         //   'name'=>'Advertisement',
         //   'key'=>'advertisement',
         // ],
         // [
         //   'name'=>'User Advertisement',
         //   'key'=>'astrologer-advertisement',
         // ],
         // [
         //   'name'=>'Contact Us',
         //   'key'=>'contact-us',
         // ],
         // [
         //   'name'=>'Listing',
         //   'key'=>'cms-two',
         // ],
         // [
         //   'name'=>'Vastu Page & Services',
         //   'key'=>'vastu-page',
         // ],
         // [
         //   'name'=>'Vastu Procedure',
         //   'key'=>'vastu-procedure',
         // ],
         // [
         //   'name'=>'Landing Page',
         //   'key'=>'landing',
         // ],
         // [
         //   'name'=>'Astrology Enquiry',
         //   'key'=>'enquiryastrology',
         // ],
         // [
         //   'name'=>'NG Software Enquiry',
         //   'key'=>'enquirycmstwo',
         // ],
         // [
         //   'name'=>'Vastu Shastra Enquiry',
         //   'key'=>'enquiryvastu',
         // ],
         // [
         //   'name'=>'Vastu Guidence (LP) Enquiry',
         //   'key'=>'landing-data-vastu-consultation-landing-page',
         // ],
         // [
         //   'name'=>'NG Software (LP) Enquiry',
         //   'key'=>'landing-data-ng-software-landing-page',
         // ],
         // [
         //   'name'=>'Rudraksh (LP) Enquiry',
         //   'key'=>'landing-data-rudraksh-landing-page',
         // ],
         // [
         //   'name'=>'Gemstone (LP) Enquiry',
         //   'key'=>'landing-data-gemstone-landing-page',
         // ],
         // [
         //   'name'=>'Punchang',
         //   'key'=>'panchang',
         // ],
         [
           'name'=>'User',
           'key'=>'admin',
         ],
         [
           'name'=>'Role',
           'key'=>'role',
         ],
         // [
         //   'name'=>'Module',
         //   'key'=>'module',
         // ],
         [
           'name'=>'Permission',
           'key'=>'permission',
         ],
         [
           'name'=>'Language',
           'key'=>'language',
         ],
         [
           'name'=>'Setting',
           'key'=>'settings',
         ],
         [
           'name'=>'Vendors',
           'key'=>'vendors',
         ],
         [
           'name'=>'Astrologer Live',
           'key'=>'astrologerlive',
         ],
       ];

       // \DB::select('truncate table modules');
       $keysArray = [];
       foreach($modules as $key => $module){
         $keysArray[] = $module['key'];
         if(Module::where('key', $module['key'])->count() == 0){
           $modulee = new Module();
           $modulee->name = $module['name'];
           $modulee->key = strtolower(str_replace(' ', '-', $module['key']));
           $modulee->save();
         }
       }
       Module::whereNotIn("key", $keysArray)->delete();
       echo 'module done';

       if(Attribute::count() == 0){
         $attributes = [
           [
             'name'=>'Color',
             'key'=>'color',
           ],
           [
             'name'=>'Size',
             'key'=>'size',
           ],
           [
             'name'=>'Weight',
             'key'=>'weight',
           ],
           [
             'name'=>'Type',
             'key'=>'type',
           ],
         ];

         foreach ($attributes as $index => $value){
           if(Attribute::where('name', $value['name'])->count()==0){
             $attribute = new Attribute();
             $attribute->name = $value['name'];
             $attribute->key = $value['key'];
             $attribute->save();
           }
         }
         echo 'attribute done';
       }

       if(Language::count() == 0){
         $languages = [
           [
             'name'=>'Hindi',
           ],
           [
             'name'=>'English',
           ],
         ];

         foreach($languages as $key => $value){
           $slug = str_slug($value['name']);
           if(!Language::where('slug', $slug)->first()){
             $new = new Language();
             $new->name = $value['name'];
             $new->slug = $slug;
             $new->save();
           }
         }
         echo 'language done';
       }

       if(Service::count() == 0){
         $services = [
           [
             'name'=>'One',
           ],
           [
             'name'=>'two',
           ],
         ];

         foreach($services as $key => $value){
           $slug = str_slug($value['name']);
           if(!Service::where('slug', $slug)->first()){
             $new = new Service();
             $new->slug = $slug;
             $new->name = $value['name'];
             $new->short_name = $value['name'];
             $new->save();
           }
         }
         echo 'services done';
       }

       if(PayoutPackage::count() == 0){
         $payoutPackages = [
           [
             'name'=>"Default",
             'ratio'=>50,
             'commission_settlement_period'=>"15",
           ],
         ];

         foreach($payoutPackages as $key => $value){
           if(!PayoutPackage::where('name', $value['name'])->first()){
             $new = new PayoutPackage();
             $new->name = $value['name'];
             $new->commission_settlement_period = $value['commission_settlement_period'];
             $new->ratio = $value['ratio'];
             $new->save();
           }
         }
         echo 'payout packages done';
       }

       if(HsnCode::count() == 0){
         $hsnCodes = [
           [
             'name'=>"Zero",
             'hsn_code'=>"0",
             'gst_percentage'=>0,
             'description'=>"Zero",
             'short_description'=>"Zero",
             'status'=>1,
           ],
           [
             'name'=>"Default",
             'hsn_code'=>"111111",
             'gst_percentage'=>18,
             'description'=>"Default",
             'short_description'=>"Default",
             'status'=>1,
           ],
         ];

         foreach($hsnCodes as $key => $value){
           if(!HsnCode::where('hsn_code', $value['hsn_code'])->first()){
             $new = new HsnCode();
             $new->name = $value['name'];
             $new->hsn_code = $value['hsn_code'];
             $new->gst_percentage = $value['gst_percentage'];
             $new->description = $value['description'];
             $new->short_description = $value['short_description'];
             $new->status = $value['status'];
             $new->save();
           }
         }
         echo 'hsncode done';
       }

       $formFields = [
         [
           'form_label'=>"Birth Place",
           'form_type'=>"search",
           'isrequired'=>1,
         ],
         [
           'form_label'=>"Gender",
           'form_type'=>"select",
           'isrequired'=>1,
         ],
         [
           'form_label'=>"Language",
           'form_type'=>"select",
           'isrequired'=>0,
         ],
       ];

       foreach($formFields as $key => $value){
         if(Form::where('form_label', $value['form_label'])->count() == 0){
           $new = new Form();
           $new->form_label = $value['form_label'];
           $new->form_type = $value['form_type'];
           $new->isrequired = $value['isrequired'];
           $new->save();
         }
       }
       echo 'form done';
    }
}
