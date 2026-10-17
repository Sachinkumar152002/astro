<?php
return [
    'theme' => 'default',

    'servetel_product_id' => 16805,
    'servetel_api_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjE0NTU5LCJpc3MiOiJodHRwczpcL1wvY3VzdG9tZXIuc2VydmV0ZWwuaW5cL3Rva2VuXC9nZW5lcmF0ZSIsImlhdCI6MTYwNjIxNDQ3NCwiZXhwIjoxOTA2MjE0NDc0LCJuYmYiOjE2MDYyMTQ0NzQsImp0aSI6IjBFRUI4Z1dxSWxqSGFSNWkifQ.yeQQP96rwEfUnoPXnVb1Fh6qKE4EgqxT3R4ne-TB3n0',
    'servetel_smsapi_token' => 'A5fc48d842cb93f6ea63bd6792cf43e66',
    'servetel_smsapi_senderid' => 'Pvedic',

    'vedicastroapi_token'=> '37418a10-331a-5296-ad80-8ffb74bd7d89',
    // Customer Message
    'otp_message' => "OTP for your phone verification is %s.The OTP will expire in next ten minutes. On expiry new OTP will be regenerated. Regards Planet Vedic",
    'mobile_recharge' => "We confirm the receipt of Rs%s/- Your wallet balance is Rs%s/-. Connect with your future. Thanks , Planet Vedic Team.",
    'user_register' => "Thank you for the successful registration. Connect with leading astrologers to know your future - Planet Vedic Team.",
    'user_call_success' => "You have connected with astrologer %s. Please rate the call %s. The call duration was %s Minutes. The wallet Balance is %s. Thanks Planet Vedic Team.",
    // Astrologer Message
    'otp_astrologer' => "The OTP for phone verification is %s. The OTP will expire in next ten minutes. On expiry new OTP will be regenerated. Regards Planet Vedic",
    'astrologer_register' => "Dear Valued Partner, You have successfully registered yourself on Planet Vedic as an astrologer. We believe in the best services at reasonable prices. Please talk to support team for any clarification at %s. Regards Planet Vedic",
    'astrologer_register_review' => "Your OTP verification is successful. You shall receive a call from our team in next 2 Days to validate your  details. Regards Planet Vedic",
    'astrologer_approve' => "Dear Astrologer, Welcome onboard!. Your account has been verified. For connecting with clients please follow the instruction mentioned in the FAQ’s, or connect with our team on Phone : %s  or email : %s. Regards Planet Vedic",
    'astrologer_call_success' => "You were talking to Mr/Mrs. %s. Please mention your feedback about the call- Planet Vedic Team.",
    'astrologer_not_logined_since' => 'A lot of client has inquired about you, while you were away. Connect on the planet vedic app to connect with the clients. Regards Planet Vedic',
    // Mcube Credentials
    'mcube_sms_user'=>config('app.url'),
    'mcube_sms_passwd'=>'Xq2GX',
    'mcube_sender_id'=>'Pvedic',
    'mcube_call_apikey'=>'832857d17472ddf0f1d76d177f6722be',

    // 'razorpay_key' => "rzp_test_RAm5JFMED0bjRN",
    // 'razorpay_secret_key'=> "6Vks36lkBiJXGmYf8JZKNiQS",

    // 'vedic_api_key' => '89fe3b33-c1f2-5da6-b692-2f08925e831b',
    // 'agora_app_id' => '52f57e1802714a008f23590ee5b522e4',

    // 'agora_app_certificate' => '32baca70b7e14b9e9d29667a734ba995',

    // 'gpt_api_key' => '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',

    // 'zego_server_secret' => '67eca6f8c236b7201cede5161495946a',
    // 'zego_app_sign' => '67d1751eab62811b94b5e667a39ce1fd9353fefb3e1eb571f8451c5a4c126446',
    // 'zego_call_back_secret' => '67d1751eab62811b94b5e667a39ce1fd',
    // 'zego_app_id' => '1711072743',

    // 'exotel_sid'=>'astrokavach1',
    // 'exotel_caller_id'=>'01143146475',
    // 'exotel_api_key'=>'af86832fb57a2e3f68ff5ae75dd61a34503f9255f9e096cf',
    // 'exotel_api_token'=>'e78ccc176412c63be49a5c0ba6d86bd7a10af53bcf971e75',

    // 'vimeo_access_token' => 'e3adda9c9873bca65aab67801041c4e4',

    // 'telecrm_bearer_token' => env('TELECRM_BEARER_TOKEN'),

    'one_delhivery_api_token' => env('ONE_DELHIVERY_API_TOKEN'),

    'mobile_number' => '+919999999999',

    'custome_care_email' => 'support@astrokavach.com',
    'custome_care_number' => '+91 9116545771',

    'admin_email' => 'support@astrokavach.com',
    'admin_number' => '+91 9116545771',

    'support_email' => 'support@astrokavach.com',
    'support_number' => '+91 9116545771',

    'facebook_domain_verification' => env('FACEBOOK_DOMAIN_VERIFICATION'),
    'folder_path' => env('FOLDER_PATH', '/var/www/astrokavach'),

    'delhi_lat' => '28.7041',
    'delhi_lon' => '77.1025',

    'fcm_auth_token' => 'gGFMWPMnikxzFhtKoNmpJ3Cska4gES0jEO6t18nIbCm0xyuvetIbkMWc487hOpNYj6Ssvy',
    'fcm_project_id' => 'hello-cino',
    'otp_send' => false,
    'otp_send_except_number' => [],
    // WhatsApp Meta API (template messages): {{domain}}/api/meta/{{version}}/{{phone_number_id}}/messages
    'whatsapp_meta_domain' => env('WHATSAPP_META_DOMAIN', 'https://crmapi.1automations.com/api/meta'),
    'whatsapp_meta_version' => env('WHATSAPP_META_VERSION', 'v19.0'),
    'whatsapp_phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID','2236884099680498'),
    'whatsapp_meta_access_token' => env('WHATSAPP_META_ACCESS_TOKEN','zQqchiuqeT3vWMhGKfTir3rrjOB8pzOISBmTee2CHCejqVSIlC9UPuKREFTSAREFTSASMVG5wvbJm3Xb94k8F0Tr3osB1EL8EXrA4upACCLCgPuK9Fub5Ige8jTOIbeDrq9REFTSAI'),
    'whatsapp_welcome_template_name' => env('WHATSAPP_WELCOME_TEMPLATE_NAME', 'whatsapp order confirmation'),
    'whatsapp_welcome_template_language' => env('WHATSAPP_WELCOME_TEMPLATE_LANGUAGE', 'en'),
];

// 2025_01_27 invoice default information migartion file date
