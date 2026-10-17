<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\{Controller, MediaController};
use Illuminate\Http\Request;
use App\Model\{Astrologer, Language, Service, AstrologerService, AstrologerLanguage, ReferredBy, VerifyUser, WalletTransition};
use Illuminate\Support\Str;
use App\Jobs\{EmailVerification};
use App\{User};
use DB;


class RegisterController extends Controller {
    public function registrationForm(Request $request){
      if(auth()->guard('astrologer')->check()){
        $astrologer = auth()->guard('astrologer')->user();
        return $astrologer->is_verified ? redirect()->route('astrologer.dashboard') : redirect()->route('astrologer.pending');
      }
      $languages = Language::orderBy('position', 'asc')->get();
      $services = Service::all();
      $countries = DB::table('master_countries')->where("id", 101)->get();
      $states = DB::table('master_states')->where("country_id", 101)->get();
      $hideUserLoginPopup = true;
      $phone = $request->phone;

      $verifyUser = VerifyUser::where("phone", $phone)->where("phone_verified", 1)->first();
      $step1 = $request->session()->get('astrologer_register_step1', []);
      $step2 = $request->session()->get('astrologer_register_step2', []);
      $step3 = $request->session()->get('astrologer_register_step3', []);
      return view(themeView().'astrologers.auth.register', compact('languages', 'services', 'countries', 'states', 'hideUserLoginPopup', 'phone', 'verifyUser', 'step1', 'step2', 'step3'));
    }

    /**
     * Save step 1 (Basic Information). Validates and stores in session.
     */
    public function saveStep1(Request $request)
    {
        $existingStep1 = $request->session()->get('astrologer_register_step1', []);
        $hasProfileInSession = !empty($existingStep1['profile_image_media_id'] ?? null);
        $request->validate([
            'profile_image' => $hasProfileInSession ? 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120' : 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:100|unique:astrologers,email,id,id',
            'phone_code' => 'required|max:5',
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:astrologers,phone,id,id',
            'gender' => 'required|gt:0',
            'dob' => 'required|date',
            'qualification' => 'required|max:255',
            'job_start_year' => 'required',
            'working_with_other_app' => 'required|in:0,1',
            'address' => 'required|max:500',
            'landmark' => 'required|max:255',
            'country_id' => 'required|gt:0',
            'state_id' => 'required|gt:0',
            'city_id' => 'required|gt:0',
            'pincode' => 'required|digits:6',
            'area' => 'nullable|max:255',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ], [
            'profile_image.required' => 'Profile photo is required.',
            'city_id.required' => 'Please select city.',
            'state_id.required' => 'Please select state.',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'email', 'phone_code', 'phone', 'gender', 'dob',
            'qualification', 'job_start_year', 'working_with_other_app', 'address', 'area',
            'landmark', 'country_id', 'state_id', 'city_id', 'pincode', 'instagram', 'facebook', 'youtube',
        ]);

        if ($request->hasFile('profile_image')) {
            $mediaController = new MediaController();
            $uploadRequest = new Request(['file' => $request->file('profile_image'), 'type' => 'astrologer']);
            $dataResp = $mediaController->upload($uploadRequest);
            $data['profile_image_media_id'] = $dataResp->getData()->media_id;
        } elseif ($hasProfileInSession) {
            $data['profile_image_media_id'] = $existingStep1['profile_image_media_id'] ?? null;
        }

        $request->session()->put('astrologer_register_step1', $data);
        return response()->json(['success' => true, 'message' => 'Step 1 saved.']);
    }

    /**
     * Save step 2 (Skills). Validates and stores in session.
     */
    public function saveStep2(Request $request)
    {
        if (!$request->session()->has('astrologer_register_step1')) {
            return response()->json(['errors' => ['step' => ['Please complete Step 1 first.']]], 422);
        }
        $request->validate([
            'languages' => 'required|array|min:1',
            'services' => 'required|array|min:1',
            'any_healing' => 'required|in:0,1',
            'about' => 'required|string|min:10',
        ], [
            'languages.required' => 'Please select at least one language.',
            'services.required' => 'Please select at least one skill.',
            'about.required' => 'About yourself is required.',
        ]);

        $data = $request->only(['languages', 'services', 'any_healing', 'about']);
        $request->session()->put('astrologer_register_step2', $data);
        return response()->json(['success' => true, 'message' => 'Step 2 saved.']);
    }

    /**
     * Save step 3 (Bank Details). Validates and stores in session.
     */
    public function saveStep3(Request $request)
    {
        if (!$request->session()->has('astrologer_register_step1')) {
            return response()->json(['errors' => ['step' => ['Please complete Step 1 first.']]], 422);
        }
        if (!$request->session()->has('astrologer_register_step2')) {
            return response()->json(['errors' => ['step' => ['Please complete Step 2 first.']]], 422);
        }
        $request->validate([
            'bank_account_number' => 'required|max:50',
            'bank_account_number_confirm' => 'required|same:bank_account_number',
            'bank_account_name' => 'required|max:255',
            'bank_name' => 'required|max:255',
            'bank_ifsc_code' => 'required|max:20',
            'bank_city' => 'nullable|max:100',
            'bank_state' => 'nullable|max:100',
        ], [
            'bank_account_number_confirm.same' => 'Account number and confirm account number must match.',
        ]);

        $data = $request->only(['bank_account_number', 'bank_account_name', 'bank_name', 'bank_ifsc_code', 'bank_city', 'bank_state']);
        $request->session()->put('astrologer_register_step3', $data);
        return response()->json(['success' => true, 'message' => 'Step 3 saved.']);
    }

    public function register(Request $request){
        $session = $request->session();
        $step1 = $session->get('astrologer_register_step1', []);
        $step2 = $session->get('astrologer_register_step2', []);
        $step3 = $session->get('astrologer_register_step3', []);

        if (empty($step1) || empty($step2) || empty($step3)) {
            return response()->json([
                'errors' => ['step' => ['Please complete all steps in order (1, 2, 3) before submitting.']]
            ], 422);
        }

        if ($step1) {
            foreach ($step1 as $key => $value) {
                if ($key !== 'profile_image_media_id' && $value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
        }
        if ($step2) {
            foreach ($step2 as $key => $value) {
                if ($value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
        }
        if ($step3) {
            foreach ($step3 as $key => $value) {
                if ($value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
        }

        $profileImageRule = (!empty($step1['profile_image_media_id'])) ? 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120' : 'required|file|mimes:jpg,jpeg,png,webp|max:5120';
        $request->validate([
          'profile_image'   => $profileImageRule,
          'first_name' => 'required|max:255',
          'last_name' => 'required|max:255',
          'email' => 'required|email|max:100|unique:astrologers,email,id,id',
          'phone_code' => 'required|max:3',
          'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:astrologers,phone,id,id',
          'gender'   => 'required|gt:0',
          'dob'   => 'required',
          'job_start_year'   => 'required',
          'qualification'   => 'required|max:255',
          'working_with_other_app'   => 'required|in:0,1',
          'languages'   => 'required|array',
          'services'   => 'required|array',
          'country_id'   => 'required|gt:0',
          'state_id'   => 'required|gt:0',
          'city_id'   => 'required|gt:0',
          'address'   => 'required',
          'area'   => 'nullable|max:255',
          'landmark'   => 'required|max:255',
          'pincode'   => 'required|digits:6',
          'instagram'   => 'nullable|url|max:255',
          'facebook'   => 'nullable|url|max:255',
          'youtube'   => 'nullable|url|max:255',
          'any_healing'   => 'required|in:0,1',
          'about'   => 'required',
          'bank_account_number'   => 'required|max:50',
          'bank_account_number_confirm'   => 'required|same:bank_account_number',
          'bank_account_name'   => 'required|max:255',
          'bank_name'   => 'required|max:255',
          'bank_ifsc_code'   => 'required|max:20',
          'bank_city'   => 'nullable|max:100',
          'bank_state'   => 'nullable|max:100',
          'aadhaar_number'   => 'required|digits:12',
          'pan_card'   => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
          'aadhaar_front'   => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
          'aadhaar_back'   => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
          'pancard'   => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
          'cancel_cheque'   => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
          'astrology_certificate'   => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ],[
          'profile_image.required'   => 'The Profile Image Field is Required',
          'profile_image.mimes'   => 'The Profile Image document should be (jpg, jpeg, png, or webp).',
          'profile_image.max'   => 'The Profile Image document should be max 5MB in size.',
          'gender.required'   => 'The Gender Field is Required',
          'dob.required'   => 'The Birth Date Field is Required',
          'job_start_year.required' => 'The Experience Field is Required',
          'qualification.required' => 'The Qualification Field is Required',
          'working_with_other_app.required' => 'Please specify if you are working with any other app.',
          'languages.required'   => 'The Language Field is Required',
          'services.required'   => 'The Skills Field is Required',
          'country_id.required'   => 'The Country Field is Required',
          'state_id.required'   => 'The State Field is Required',
          'city_id.required'   => 'The City Field is Required',
          'about.required'   => 'The About yourself Field is Required',
          'any_healing.required' => 'Please specify if you offer any healing.',
          'bank_account_number.required' => 'The Account Number is Required',
          'bank_account_number_confirm.same' => 'Account number and confirm account number must match.',
          'bank_account_name.required' => 'The Account Name is Required',
          'bank_name.required' => 'The Bank Name is Required',
          'bank_ifsc_code.required' => 'The IFSC Code is Required',
          'aadhaar_number.required' => 'The Aadhaar Number is Required',
          'aadhaar_number.digits' => 'Aadhaar Number must be 12 digits.',
          'pan_card.required' => 'The PAN Card number is Required',
          'pan_card.regex' => 'Please enter a valid PAN number (e.g. ABCDE1234F).',
          'aadhaar_front.required' => 'Please upload Aadhaar front image.',
          'aadhaar_back.required' => 'Please upload Aadhaar back image.',
          'pancard.required'   => 'Please upload PAN Card document.',
          // Cancel cheque and astrology certificate are optional on registration.
        ]);

        $request->validate([
          "terms_and_conditions" => "required|gt:0"
        ],[
          'terms_and_conditions'   => 'The Terms and Conditions Field is Required',
        ]);

        $has_website = $request->has_website ?: 0;

        if($has_website){
          $request->validate([
              'website_url' => 'required|max:255',
          ]);
        }

        $associate_with_temple = $request->associate_with_temple ?: 0;

        if($associate_with_temple){
          $request->validate([
              'temple_name' => 'required|max:255',
          ]);
        }

        // Validate referral code if provided
        // Check only in astrologers table (not in settings tables)
        if($request->referral_code){
          $referralCode = trim($request->referral_code);

          // Check if it's an astrologer's referral code in astrologers table
          $referralAstrologer = Astrologer::where('referral_code', $referralCode)
            ->where('status', 1)
            ->where('is_verified', 1)
            ->first();

          // If not found or not active/verified, return error
          if(!$referralAstrologer){
            return response()->json([
              'errors' => ['referral_code' => ['This referral code is invalid. The astrologer account is not active or verified.']]
            ], 422);
          }

          // Check if same phone or email is already using this referral code
          $existingAstrologer = Astrologer::where('referral_code', $referralCode)
            ->where(function($query) use ($request) {
              $query->where('phone', $request->phone)
                    ->orWhere('email', $request->email);
            })
            ->first();

          if($existingAstrologer){
            return response()->json([
              'errors' => ['referral_code' => ['This referral code has already been used with this phone number or email']]
            ], 422);
          }
        }

        $astrologer = new Astrologer();
        $astrologer->type = "Astrologer";

        if ($request->hasFile('profile_image')) {
            $mediaController = new MediaController();
            $newRequest = new Request(['file' => $request->file('profile_image'), 'type' => 'astrologer']);
            $data = $mediaController->upload($newRequest);
            $astrologer->image_id = $data->getData()->media_id;
        } elseif (!empty($step1['profile_image_media_id'])) {
            $astrologer->image_id = $step1['profile_image_media_id'];
        }

        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = "$first_name $last_name";

        $astrologer->firstname = $first_name;
        $astrologer->lastname = $last_name;
        $astrologer->display_name = $full_name;
        $astrologer->slug = str_slug($full_name);
        $astrologer->email = $request->email;
        $astrologer->phone_code = $request->phone_code;
        $astrologer->phone = $request->phone;
        $astrologer->gender = $request->gender;
        $astrologer->dob = $request->dob;
        $astrologer->job_start_year = date("Y", strtotime("now-".$request->job_start_year."years"));
        $astrologer->qualification = $request->qualification;
        $astrologer->working_with_other_app = (int) $request->working_with_other_app;
        $astrologer->country_id = $request->country_id;
        $astrologer->state_id = $request->state_id;
        $astrologer->city_id = $request->city_id;
        $astrologer->pincode = $request->pincode;
        $astrologer->address = $request->address;
        $astrologer->area = $request->area;
        $astrologer->landmark = $request->landmark;
        $astrologer->instagram = $request->instagram;
        $astrologer->facebook = $request->facebook;
        $astrologer->youtube = $request->youtube;
        $astrologer->any_healing = (int) $request->any_healing;
        $astrologer->about = $request->about;
        $astrologer->has_website = $request->has_website ?: 0;
        $astrologer->website_url = $request->website_url;
        $astrologer->associate_with_temple = $request->associate_with_temple ?: 0;
        $astrologer->temple_name = $request->temple_name;
        $astrologer->bank_account_number = $request->bank_account_number;
        $astrologer->bank_account_name = $request->bank_account_name;
        $astrologer->bank_name = $request->bank_name;
        $astrologer->bank_ifsc_code = $request->bank_ifsc_code;
        $astrologer->bank_city = $request->bank_city;
        $astrologer->bank_state = $request->bank_state;
        $astrologer->aadhaar_number = $request->aadhaar_number;
        $astrologer->pan_card = $request->pan_card;

        $mediaController = new MediaController();
        if($request->hasFile('pancard')){
          $newRequest = new Request(['file' => $request->file('pancard'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->pan_id = $response->media_id;
        }
        if($request->hasFile('aadhaar_front')){
          $newRequest = new Request(['file' => $request->file('aadhaar_front'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->aadhaar_front_id = $response->media_id;
          $astrologer->address_proof = $response->media_id;
        }
        if($request->hasFile('aadhaar_back')){
          $newRequest = new Request(['file' => $request->file('aadhaar_back'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->aadhaar_back_id = $response->media_id;
        }
        if($request->hasFile('cancel_cheque')){
          $newRequest = new Request(['file' => $request->file('cancel_cheque'), 'type' => 'astrologer']);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->cancel_cheque_id = $response->media_id;
        }
        if($request->hasFile('astrology_certificate')){
          $newRequest = new Request(['file' => $request->file('astrology_certificate'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->astrology_certificate_id = $response->media_id;
        }

        $astrologer->status = 1;
        $astrologer->is_verified = 0;
        $astrologer->chat = 0;
        $astrologer->call = 0;
        $astrologer->video = 0;
        $astrologer->register_from_ip = request()->ip();

        // Save the referral code used during registration (if provided)
        // Note: Astrologer's own referral_code will be auto-generated in boot() method
        if($request->referral_code){
          $astrologer->used_referral_code = trim($request->referral_code);
        }
        // If no referral code provided, used_referral_code will be null (no rewards will be given)

        $astrologer->save();

        $slug = "$full_name $astrologer->uuid";
        $astrologer->slug = str_slug($slug);
        $astrologer->save();

        $astrologerId = $astrologer->id;

        // Extract variables from request
        $phone = $request->phone;
        $email = $request->email;
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $city_id = $request->city_id;
        $display_name = $full_name;
        $astrologer_id = $astrologerId;

        $user=null;
        if($user=User::where("phone",$phone)->first()){
          $user=$user;
        }elseif($user=User::where("email",$email)->first()){
          $user=$user;
        }
        if($user){
          $user->astrologer_id = $astrologer_id;
          $user->save();
        }else{
          $country = DB::table('master_countries')->where('id', $country_id)->first();
          $state = DB::table('master_states')->where('id', $state_id)->first();
          $city = DB::table('master_cities')->where('id', $city_id)->first();
          $user = new User();
          $user->astrologer_id = $astrologer_id;
          $user->name = $display_name;
          $user->email = $email;
          $user->phone_code = "+91";
          $user->phone = $phone;
          $user->status = 1;
          $user->is_verified = 1;
          $user->register_from_ip = $astrologer->register_from_ip;
          $user->country_id = $country_id;
          $user->country = $country?$country->name:$country_id;
          $user->state_id = $state_id;
          $user->state = $state?$state->name:$state_id;
          $user->city_id = $city_id;
          $user->city = $city?$city->name:$city_id;
          $user->user_agent = $request->userAgent();
          $user->save();
        }
        $astrologer->order = $astrologer_id;
        $astrologer->user_id = $user->id;
        $astrologer->save();

        $languages = $request->languages;
        foreach ($languages as $languageId){
          if($language = Language::find($languageId)){
            $astrologerLanguage = new AstrologerLanguage();
            $astrologerLanguage->astrologer_id = $astrologerId;
            $astrologerLanguage->language_id = $language->id;
            $astrologerLanguage->created_at = now();
            $astrologerLanguage->updated_at = now();
            $astrologerLanguage->save();
          }
        }

        $services = $request->services;
        foreach($services as $serviceId){
          if($service = Service::find($serviceId)){
            $astrologerService = new AstrologerService();
            $astrologerService->astrologer_id = $astrologerId;
            $astrologerService->service_id = $service->id;
            $astrologerService->created_at = now();
            $astrologerService->updated_at = now();
            $astrologerService->save();
          }
        }

        // Process App Referral Reward if trigger is 'signup'
        if($astrologer->used_referral_code) {
          \App\Services\ReferralService::processReferralReward($astrologer, 'astrologer', null);
        }

        EmailVerification::dispatch($astrologer);
        $applicationId = $astrologer->uuid;
        $thankYouUrl = route('astrologer.register.thankyou', ['application_id' => $applicationId]);

        VerifyUser::where("phone", $request->phone)->delete();

        $session->forget(['astrologer_register_step1', 'astrologer_register_step2', 'astrologer_register_step3']);

        return response()->json([
          'success' => true,
          'message' => 'Registered successfully',
          'application_id' => $applicationId,
          'redirectTo' => $thankYouUrl,
        ], 200);
    }

    /**
     * Thank you page after registration (shows application id).
     */
    public function thankYou(Request $request)
    {
        $applicationId = $request->get('application_id');
        return view(themeView() . 'astrologers.auth.register-thankyou', compact('applicationId'));
    }

    /**
     * Proxy for Razorpay IFSC API. Fetches branch details by IFSC.
     * @see https://ifsc.razorpay.com/
     */
    public function ifscLookup(string $ifsc)
    {
        $ifsc = strtoupper(preg_replace('/[^A-Z0-9]/', '', $ifsc));
        if (strlen($ifsc) !== 11) {
            return response()->json(['error' => 'Invalid IFSC'], 422);
        }
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->get('https://ifsc.razorpay.com/' . $ifsc);
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'IFSC not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to verify IFSC'], 502);
        }
    }
}
