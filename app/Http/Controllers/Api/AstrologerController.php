<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, MediaController};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Model\{Astrologer,Language,AstrologerLanguage,Service,AstrologerService, VerifyUser, WalletTransition, Withdraw, Setting, PujaAstrologerAssignment, AstrologerReferral, ReferralWallet, ReferralTransaction};
use App\{User, Product, Order, OrderItem};
use App\Jobs\{EmailVerification,UserNotificationJob};
use DB;
use Auth;
use App\Services\{ExotelService};

class AstrologerController extends Controller
{
    public function getPhoneOTP(Request $request){
      $request->validate([
        'phone_code' => 'required|max:3',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:astrologers,phone,id,id',
      ]);

      $phone = $request->phone;

      $verifyUser = VerifyUser::where("phone", $phone)->first();

      if(!$verifyUser){
        $verifyUser = new VerifyUser();
        $verifyUser->phone = $phone;
      }
      $phoneArray = config('vrdcms.otp_send_except_number');
      $otp = 654321;
      if(!in_array($phone, $phoneArray) && config('vrdcms.otp_send')){
        $otp = mt_rand(100000, 999999);
        $message = loginOtpMessage($otp);
        $exotelService=new ExotelService();
        $sendSmsResponse=$exotelService->sendSms("FROM",$phone,$message);
        if($sendSmsResponse==false){
            return response()->json(['errors' => ['mobile'=>['Failed to send OTP. Please try again later.']] ], 500);
        }
      }
      $verifyUser->phone_otp = $otp;
      $verifyUser->phone_verified = 0;
      $verifyUser->save();
      return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function verifyPhoneOtp(Request $request){
      $request->validate([
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:verify_users,phone',
        'otp' => 'required|digits:6'
      ],[
        "phone.exists" => "Invalid action"
      ]);

      $phone = $request->phone;
      $otp = $request->otp;

      $verifyUser = VerifyUser::where("phone", $phone)->first();

      if($verifyUser){
        if($verifyUser->phone_otp == $otp){
          $verifyUser->phone_verified = 1;
          $verifyUser->save();

          return response()->json([
            'message'=>'Mobile Number Verified Successfully.'
          ], 200);
        }
        $errors = [
          "phone" => ["OTP not matched."]
        ];
        return response()->json(["errors" => $errors], 403);
      }

      return response()->json(["errors" => $errors], 403);
    }

    /**
     * GET /api/astrologer/register/options
     * Returns languages, services, countries, states for registration form (Flutter app).
     */
    public function registerOptions(Request $request)
    {
        $languages = Language::orderBy('position', 'asc')->get(['id', 'name', 'position']);
        $services = Service::orderBy('name', 'asc')->get(['id', 'name']);
        $countries = DB::table('master_countries')->where('id', 101)->orderBy('name')->get(['id', 'name', 'phonecode']);
        $states = DB::table('master_states')->where('country_id', 101)->orderBy('name')->get(['id', 'name', 'country_id']);
        return response()->json([
            'success' => true,
            'data' => [
                'languages' => $languages,
                'services' => $services,
                'countries' => $countries,
                'states' => $states,
            ],
        ], 200);
    }

    /**
     * GET /api/astrologer/register/cities?state_id=1
     * Returns cities for the given state_id (for registration form dropdown).
     */
    public function registerCities(Request $request)
    {
        $stateId = $request->query('state_id');
        if (!$stateId) {
            return response()->json(['success' => true, 'data' => []]);
        }
        $cities = DB::table('master_cities')->where('state_id', $stateId)->orderBy('name')->get(['id', 'name', 'state_id']);
        return response()->json(['success' => true, 'data' => $cities]);
    }

    /**
     * GET /api/astrologer/ifsc/{ifsc}
     * IFSC lookup (Razorpay) for bank name, city, state. Used by Flutter registration form.
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

    /** Cache key prefix and TTL (minutes) for step-wise registration draft. */
    const DRAFT_CACHE_PREFIX = 'astrologer_register_draft_';
    const DRAFT_CACHE_TTL_MINUTES = 60;

    /**
     * Get draft token from request (body or X-Draft-Token header).
     */
    private function getDraftToken(Request $request)
    {
        return $request->input('draft_token') ?: $request->header('X-Draft-Token');
    }

    /**
     * POST /api/astrologer/register/step/1
     * Step 1: Basic info + profile image. Returns draft_token for subsequent steps.
     * Content-Type: multipart/form-data.
     */
    public function registerStep1(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
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
            'profile_image.max' => 'Profile photo max 5MB.',
        ]);

        $mediaController = new MediaController();
        $uploadRequest = new Request(['file' => $request->file('profile_image'), 'type' => 'astrologer']);
        $dataResp = $mediaController->upload($uploadRequest);
        $profileImageMediaId = $dataResp->getData()->media_id;

        $step1 = $request->only([
            'first_name', 'last_name', 'email', 'phone_code', 'phone', 'gender', 'dob',
            'qualification', 'job_start_year', 'working_with_other_app', 'address', 'area',
            'landmark', 'country_id', 'state_id', 'city_id', 'pincode', 'instagram', 'facebook', 'youtube',
        ]);
        $step1['profile_image_media_id'] = $profileImageMediaId;

        $hasWebsite = (int) ($request->has_website ?? 0);
        if ($hasWebsite) {
            $request->validate(['website_url' => 'required|max:255']);
            $step1['has_website'] = 1;
            $step1['website_url'] = $request->website_url;
        } else {
            $step1['has_website'] = 0;
            $step1['website_url'] = null;
        }
        $associateWithTemple = (int) ($request->associate_with_temple ?? 0);
        if ($associateWithTemple) {
            $request->validate(['temple_name' => 'required|max:255']);
            $step1['associate_with_temple'] = 1;
            $step1['temple_name'] = $request->temple_name;
        } else {
            $step1['associate_with_temple'] = 0;
            $step1['temple_name'] = null;
        }

        $draftToken = (string) Str::uuid();
        $draft = ['step1' => $step1, 'step2' => null, 'step3' => null];
        Cache::put(self::DRAFT_CACHE_PREFIX . $draftToken, $draft, now()->addMinutes(self::DRAFT_CACHE_TTL_MINUTES));

        return response()->json([
            'success' => true,
            'message' => 'Step 1 saved.',
            'draft_token' => $draftToken,
        ], 200);
    }

    /**
     * POST /api/astrologer/register/step/2
     * Step 2: Skills, languages, about. Send draft_token in body or X-Draft-Token header.
     */
    public function registerStep2(Request $request)
    {
        $draftToken = $this->getDraftToken($request);
        if (!$draftToken) {
            return response()->json(['success' => false, 'message' => 'draft_token is required.'], 422);
        }
        $draft = Cache::get(self::DRAFT_CACHE_PREFIX . $draftToken);
        if (!$draft || empty($draft['step1'])) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired draft. Complete Step 1 first.'], 422);
        }

        if (is_string($request->languages)) {
            $request->merge(['languages' => json_decode($request->languages, true) ?: []]);
        }
        if (is_string($request->services)) {
            $request->merge(['services' => json_decode($request->services, true) ?: []]);
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

        $draft['step2'] = $request->only(['languages', 'services', 'any_healing', 'about']);
        Cache::put(self::DRAFT_CACHE_PREFIX . $draftToken, $draft, now()->addMinutes(self::DRAFT_CACHE_TTL_MINUTES));

        return response()->json(['success' => true, 'message' => 'Step 2 saved.'], 200);
    }

    /**
     * POST /api/astrologer/register/step/3
     * Step 3: Bank details. Send draft_token in body or X-Draft-Token header.
     */
    public function registerStep3(Request $request)
    {
        $draftToken = $this->getDraftToken($request);
        if (!$draftToken) {
            return response()->json(['success' => false, 'message' => 'draft_token is required.'], 422);
        }
        $draft = Cache::get(self::DRAFT_CACHE_PREFIX . $draftToken);
        if (!$draft || empty($draft['step1'])) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired draft. Complete Step 1 first.'], 422);
        }
        if (empty($draft['step2'])) {
            return response()->json(['success' => false, 'message' => 'Complete Step 2 first.'], 422);
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
            'bank_account_number_confirm.same' => 'Account number and confirm must match.',
        ]);

        $draft['step3'] = $request->only([
            'bank_account_number', 'bank_account_name', 'bank_name', 'bank_ifsc_code', 'bank_city', 'bank_state',
        ]);
        Cache::put(self::DRAFT_CACHE_PREFIX . $draftToken, $draft, now()->addMinutes(self::DRAFT_CACHE_TTL_MINUTES));

        return response()->json(['success' => true, 'message' => 'Step 3 saved.'], 200);
    }

    /**
     * POST /api/astrologer/register
     * Single-shot (all fields in one request) OR step-wise (draft_token + step 4 fields only).
     * Content-Type: multipart/form-data. Files: profile_image (single-shot), aadhaar_front, aadhaar_back, pancard, cancel_cheque, astrology_certificate.
     */
    public function register(Request $request)
    {
        $usingDraft = false;
        $draftToken = $this->getDraftToken($request);
        if ($draftToken) {
            $draft = Cache::get(self::DRAFT_CACHE_PREFIX . $draftToken);
            if (!$draft || empty($draft['step1']) || empty($draft['step2']) || empty($draft['step3'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired draft. Complete steps 1, 2 and 3 first.',
                ], 422);
            }
            $usingDraft = true;
            foreach ($draft['step1'] as $key => $value) {
                if ($value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
            foreach ($draft['step2'] as $key => $value) {
                if ($value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
            foreach ($draft['step3'] as $key => $value) {
                if ($value !== null && $value !== '') {
                    $request->merge([$key => $request->input($key) ?? $value]);
                }
            }
        }

        if (is_string($request->languages)) {
            $request->merge(['languages' => json_decode($request->languages, true) ?: []]);
        }
        if (is_string($request->services)) {
            $request->merge(['services' => json_decode($request->services, true) ?: []]);
        }

        if (!$usingDraft) {
            $request->validate([
                'profile_image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
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
                'languages' => 'required|array',
                'services' => 'required|array',
                'any_healing' => 'required|in:0,1',
                'about' => 'required',
                'bank_account_number' => 'required|max:50',
                'bank_account_number_confirm' => 'required|same:bank_account_number',
                'bank_account_name' => 'required|max:255',
                'bank_name' => 'required|max:255',
                'bank_ifsc_code' => 'required|max:20',
                'bank_city' => 'nullable|max:100',
                'bank_state' => 'nullable|max:100',
                'aadhaar_number' => 'required|digits:12',
                'pan_card' => 'required|string|size:10|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/',
                'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'pancard' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'cancel_cheque' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'astrology_certificate' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'terms_and_conditions' => 'required|gt:0',
            ], [
                'profile_image.required' => 'Profile photo is required.',
                'profile_image.max' => 'Profile photo max 5MB.',
                'bank_account_number_confirm.same' => 'Account number and confirm must match.',
                'pan_card.regex' => 'Invalid PAN format (e.g. ABCDE1234F).',
                'aadhaar_number.digits' => 'Aadhaar must be 12 digits.',
            ]);
        } else {
            $request->validate([
                'aadhaar_number' => 'required|digits:12',
                'pan_card' => 'required|string|size:10|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/',
                'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'pancard' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'cancel_cheque' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'astrology_certificate' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'terms_and_conditions' => 'required|gt:0',
            ], [
                'pan_card.regex' => 'Invalid PAN format (e.g. ABCDE1234F).',
                'aadhaar_number.digits' => 'Aadhaar must be 12 digits.',
            ]);
        }

        $has_website = (int) ($request->has_website ?? 0);
        if ($has_website && !$usingDraft) {
            $request->validate(['website_url' => 'required|max:255']);
        }
        $associate_with_temple = (int) ($request->associate_with_temple ?? 0);
        if ($associate_with_temple && !$usingDraft) {
            $request->validate(['temple_name' => 'required|max:255']);
        }

        if ($request->referral_code) {
            $referralCode = trim($request->referral_code);
            $referralAstrologer = Astrologer::where('referral_code', $referralCode)->where('status', 1)->where('is_verified', 1)->first();
            if (!$referralAstrologer) {
                return response()->json(['errors' => ['referral_code' => ['Invalid or inactive referral code.']]], 422);
            }
            $existing = Astrologer::where('referral_code', $referralCode)
                ->where(function ($q) use ($request) {
                    $q->where('phone', $request->phone)->orWhere('email', $request->email);
                })->first();
            if ($existing) {
                return response()->json(['errors' => ['referral_code' => ['This referral code is already used with this phone or email.']]], 422);
            }
        }

        $phone = $request->phone;
        $email = $request->email;
        if (Astrologer::where('phone', $phone)->first()) {
            return response()->json(['errors' => ['phone' => ['Phone already registered.']]], 422);
        }
        if (Astrologer::where('email', $email)->first()) {
            return response()->json(['errors' => ['email' => ['Email already registered.']]], 422);
        }

        $mediaController = new MediaController();

        $astrologer = new Astrologer();
        $astrologer->type = 'Astrologer';

        if ($request->hasFile('profile_image')) {
            $newRequest = new Request(['file' => $request->file('profile_image'), 'type' => 'astrologer']);
            $data = $mediaController->upload($newRequest);
            $astrologer->image_id = $data->getData()->media_id;
        } elseif ($usingDraft && $request->input('profile_image_media_id')) {
            $astrologer->image_id = $request->input('profile_image_media_id');
        }

        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = "{$first_name} {$last_name}";

        $astrologer->firstname = $first_name;
        $astrologer->lastname = $last_name;
        $astrologer->display_name = $full_name;
        $astrologer->slug = str_slug($full_name);
        $astrologer->email = $email;
        $astrologer->phone_code = $request->phone_code;
        $astrologer->phone = $phone;
        $astrologer->gender = $request->gender;
        $astrologer->dob = $request->dob;
        $astrologer->job_start_year = date('Y', strtotime('now -' . (int) $request->job_start_year . ' years'));
        $astrologer->qualification = $request->qualification;
        $astrologer->working_with_other_app = (int) $request->working_with_other_app;
        $astrologer->country_id = $request->country_id;
        $astrologer->state_id = $request->state_id;
        $astrologer->city_id = $request->city_id;
        $astrologer->pincode = $request->pincode;
        $astrologer->address = $request->address;
        $astrologer->area = $request->area ?: null;
        $astrologer->landmark = $request->landmark;
        $astrologer->instagram = $request->instagram ?: null;
        $astrologer->facebook = $request->facebook ?: null;
        $astrologer->youtube = $request->youtube ?: null;
        $astrologer->any_healing = (int) $request->any_healing;
        $astrologer->about = $request->about;
        $astrologer->has_website = $has_website;
        $astrologer->website_url = $request->website_url ?: null;
        $astrologer->associate_with_temple = $associate_with_temple;
        $astrologer->temple_name = $request->temple_name ?: null;
        $astrologer->bank_account_number = $request->bank_account_number;
        $astrologer->bank_account_name = $request->bank_account_name;
        $astrologer->bank_name = $request->bank_name;
        $astrologer->bank_ifsc_code = $request->bank_ifsc_code;
        $astrologer->bank_city = $request->bank_city ?: null;
        $astrologer->bank_state = $request->bank_state ?: null;
        $astrologer->aadhaar_number = $request->aadhaar_number;
        $astrologer->pan_card = strtoupper($request->pan_card);

        if ($request->hasFile('aadhaar_front')) {
            $newRequest = new Request(['file' => $request->file('aadhaar_front'), 'type' => 'astrologer', 'allow_pdf' => true]);
            $data = $mediaController->upload($newRequest);
            $astrologer->aadhaar_front_id = $data->getData()->media_id;
            $astrologer->address_proof = $data->getData()->media_id;
        }
        if ($request->hasFile('aadhaar_back')) {
            $newRequest = new Request(['file' => $request->file('aadhaar_back'), 'type' => 'astrologer', 'allow_pdf' => true]);
            $data = $mediaController->upload($newRequest);
            $astrologer->aadhaar_back_id = $data->getData()->media_id;
        }
        if ($request->hasFile('pancard')) {
            $newRequest = new Request(['file' => $request->file('pancard'), 'type' => 'astrologer', 'allow_pdf' => true]);
            $data = $mediaController->upload($newRequest);
            $astrologer->pan_id = $data->getData()->media_id;
        }
        if ($request->hasFile('cancel_cheque')) {
            $newRequest = new Request(['file' => $request->file('cancel_cheque'), 'type' => 'astrologer', 'allow_pdf' => true]);
            $data = $mediaController->upload($newRequest);
            $astrologer->cancel_cheque_id = $data->getData()->media_id;
        }
        if ($request->hasFile('astrology_certificate')) {
            $newRequest = new Request(['file' => $request->file('astrology_certificate'), 'type' => 'astrologer', 'allow_pdf' => true]);
            $data = $mediaController->upload($newRequest);
            $astrologer->astrology_certificate_id = $data->getData()->media_id;
        }

        $astrologer->status = 1;
        $astrologer->is_verified = 0;
        $astrologer->chat = 0;
        $astrologer->call = 0;
        $astrologer->video = 0;
        $astrologer->register_from_ip = $request->ip();
        if ($request->referral_code) {
            $astrologer->used_referral_code = trim($request->referral_code);
        }
        $astrologer->save();

        $slug = "{$full_name} {$astrologer->uuid}";
        $astrologer->slug = str_slug($slug);
        $astrologer->save();

        $astrologerId = $astrologer->id;

        $user = User::where('phone', $phone)->first() ?? User::where('email', $email)->first();
        if ($user) {
            $user->astrologer_id = $astrologerId;
            $user->save();
        } else {
            $country = DB::table('master_countries')->where('id', $request->country_id)->first();
            $state = DB::table('master_states')->where('id', $request->state_id)->first();
            $city = DB::table('master_cities')->where('id', $request->city_id)->first();
            $user = new User();
            $user->astrologer_id = $astrologerId;
            $user->name = $full_name;
            $user->email = $email;
            $user->phone_code = $request->phone_code;
            $user->phone = $phone;
            $user->status = 1;
            $user->is_verified = 1;
            $user->register_from_ip = $astrologer->register_from_ip;
            $user->country_id = $request->country_id;
            $user->country = $country ? $country->name : (string) $request->country_id;
            $user->state_id = $request->state_id;
            $user->state = $state ? $state->name : (string) $request->state_id;
            $user->city_id = $request->city_id;
            $user->city = $city ? $city->name : (string) $request->city_id;
            $user->user_agent = $request->userAgent();
            $user->save();
        }
        $astrologer->order = $astrologerId;
        $astrologer->user_id = $user->id;
        $astrologer->save();

        foreach ((array) $request->languages as $languageId) {
            if ($language = Language::find($languageId)) {
                AstrologerLanguage::create([
                    'astrologer_id' => $astrologerId,
                    'language_id' => $language->id,
                ]);
            }
        }
        foreach ((array) $request->services as $serviceId) {
            if ($service = Service::find($serviceId)) {
                AstrologerService::create([
                    'astrologer_id' => $astrologerId,
                    'service_id' => $service->id,
                ]);
            }
        }

        if ($astrologer->used_referral_code) {
            \App\Services\ReferralService::processReferralReward($astrologer, 'astrologer', null);
        }
        EmailVerification::dispatch($astrologer);
        VerifyUser::where('phone', $phone)->delete();

        if ($usingDraft && $draftToken) {
            Cache::forget(self::DRAFT_CACHE_PREFIX . $draftToken);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully. Your application is under review.',
            'application_id' => $astrologer->uuid,
        ], 200);
    }

    /**
     * GET/POST /api/astrologer/earnings
     * Returns earnings summary and paginated wallet transitions for the authenticated astrologer.
     * Auth: api_token in body or Authorization: Bearer {api_token}
     */
    public function earnings(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        $totalVideoAmount = (float) WalletTransition::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->where('table_name', 'astrologer_appointments')
            ->where('status', 1)
            ->where('action', 1)
            ->whereHas('appointments', function ($q) {
                $q->where('package', 1);
            })
            ->sum('amount');

        $totalChatAmount = (float) WalletTransition::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->where('table_name', 'astrologer_appointments')
            ->where('status', 1)
            ->where('action', 1)
            ->whereHas('appointments', function ($q) {
                $q->where('package', 2);
            })
            ->sum('amount');

        $totalCallAmount = (float) WalletTransition::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->where('table_name', 'astrologer_appointments')
            ->where('status', 1)
            ->where('action', 1)
            ->whereHas('appointments', function ($q) {
                $q->where('package', 3);
            })
            ->sum('amount');

        $totalAmount = (float) ($astrologer->wallet ?? 0);
        $withdraws = Withdraw::where('user_id', $astrologer->id)->where('user_type', 'astrologer')->get();
        $withdrawAmount = (float) $withdraws->where('status', 'done')->sum('amount');

        $minSetting = Setting::where('key', 'minumum_withraw_amount')->first();
        $maxSetting = Setting::where('key', 'maximum_withraw_amount')->first();
        $holdSetting = Setting::where('key', 'hold_withraw_amount')->first();
        $minWithdraw = (float) ($minSetting && $minSetting->value ? $minSetting->value : 0);
        $maxWithdraw = (float) ($maxSetting && $maxSetting->value ? $maxSetting->value : 0);
        $holdAmount = (float) ($holdSetting && $holdSetting->value ? $holdSetting->value : 0);
        $availableAmount = $totalAmount - $holdAmount;
        if ($availableAmount < 0) {
            $availableAmount = 0;
        }

        $perPage = max(5, min(50, (int) $request->input('per_page', 15)));
        $transitions = WalletTransition::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $items = $transitions->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'remark' => $item->remark,
                'amount' => (float) $item->amount,
                'action' => (int) $item->action, // 1 = credit, 0 = debit
                'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'chat_balance' => $totalChatAmount,
                    'call_balance' => $totalCallAmount,
                    'video_balance' => $totalVideoAmount,
                    'total_revenue' => $totalAmount,
                    'redeem_balance' => $withdrawAmount,
                    'available_balance' => $availableAmount,
                    'hold_amount' => $holdAmount,
                    'minimum_withdraw' => $minWithdraw,
                    'maximum_withdraw' => $maxWithdraw,
                ],
                'transitions' => [
                    'data' => $items,
                    'current_page' => $transitions->currentPage(),
                    'last_page' => $transitions->lastPage(),
                    'per_page' => $transitions->perPage(),
                    'total' => $transitions->total(),
                    'next_page_url' => $transitions->hasMorePages() ? $transitions->nextPageUrl() : null,
                    'prev_page_url' => $transitions->previousPageUrl(),
                ],
            ],
        ], 200);
    }

    /**
     * Resolve astrologer from api_token (body or Authorization Bearer).
     */
    private function getAstrologerFromToken(Request $request)
    {
        $token = $request->input('api_token') ?: $request->header('Authorization');
        if ($token && strpos($token, 'Bearer ') === 0) {
            $token = trim(substr($token, 7));
        }
        if (!$token) {
            return null;
        }
        return Astrologer::where('api_token', $token)->first();
    }

    public function otp(Request $request){
      $this->validate($request, [
        'phone' => 'required|digits:10|numeric',
      ]);
      $phone = $request->phone;
      $astrologer = Astrologer::where('phone', $phone)->first();
      if(!$astrologer){
        return response()->json(['success'=>false, 'message'=>'Mobile is not registered with us. Please create new account'], 422);
      }
      if($astrologer->status==0){
        return response()->json(['success'=>false, 'message'=>'Your account is suspended. Please contact to our support to reactivate.'], 422);
      }
      $phoneArray = config('vrdcms.otp_send_except_number');
      $otp = 654321;
      if(!in_array($astrologer->phone, $phoneArray) && config('vrdcms.otp_send')){
        $otp = mt_rand(100000, 999999);
        $message = loginOtpMessage($otp);
        $exotelService=new ExotelService();
        $sendSmsResponse=$exotelService->sendSms("FROM",$astrologer->phone,$message);
        if($sendSmsResponse==false){
            return response()->json(['errors' => ['mobile'=>['Failed to send OTP. Please try again later.']] ], 500);
        }
      }
      $astrologer->last_otp=$otp;
      $astrologer->is_otp_verified=0;
      $astrologer->save();
      return response()->json(['success'=>true, 'message'=>'Otp send successfully.'], 200);
    }

    public function verifyOtp(Request $request){
      $this->validate($request, [
        'phone'   => 'required|digits:10|numeric',
        'otp'   => 'required|digits:6|numeric',
      ]);
      $user_type = 'astrologer';
      $phone = $request->phone;
      $astrologer = Astrologer::where('phone', $phone)->first();
      if(!$astrologer){
        return response()->json(['success'=>false, 'message'=>'Mobile is not registered with us. Please create new account'], 422);
      }
      if($astrologer->status==0){
        return response()->json(['success'=>false, 'message'=>'Your account is suspended. Please contact to our support to reactivate.'], 422);
      }
      if($astrologer->last_otp == $request->otp){

        $astrologer->is_otp_verified = 1;
        $astrologer->last_logined = date('Y-m-d H:i:s');
        if(!$astrologer->api_token){
          $astrologer->api_token = Str::random(70);
        }
        if ($request->has('fcm_token') && is_string($request->fcm_token)) {
          $astrologer->fcm_token = trim($request->fcm_token) ?: '';
        }
        $astrologer->save();
        $data['token'] = $astrologer->api_token;
        $data['slug'] = $astrologer->slug;
        $data['id'] = $astrologer->id;
        Auth::guard('astrologer')->logout();
        Auth::guard('astrologer')->login($astrologer);
        UserNotificationJob::dispatch($astrologer,null,"user","astrologer-online");
        return response()->json(['success'=>true, 'message'=>'Otp matched successfully', 'data'=>$data], 200);
      }
      return response()->json(['success'=>false, 'message'=>'Otp not matched'], 422);
    }

    public function data(Request $request){
      $this->validate($request, [
        'id' => 'required|integer',
      ]);
      $id=$request->id;
      $astrologer=Astrologer::find($id);
      if($astrologer){
        $country = DB::table('master_countries')->where('id', $astrologer->country_id)->first();
        $state = DB::table('master_states')->where('id', $astrologer->state_id)->first();
        $city = DB::table('master_cities')->where('id', $astrologer->city_id)->first();
        $data['id'] = $astrologer->id;
        $data['uuid'] = $astrologer->uuid;
        $data['name'] = $astrologer->display_name;
        $data['email'] = $astrologer->email;
        $data['phone'] = $astrologer->phone;
        $data['country_id'] = $astrologer->country_id ?: 0;
        $data['country'] = $country ? $country->name : '';
        $data['state_id'] = $astrologer->state_id ?: 0;
        $data['state'] = $state ? $state->name : '';
        $data['city_id'] = $astrologer->city_id ?: 0;
        $data['city'] = $city ? $city->name : '';
        $data['pincode'] = $astrologer->pincode ?: '';
        $data['address'] = $astrologer->address ?: '';
        $data['gender_id'] = $astrologer->gender ?: 0;
        $data['gender'] = 'Male';
        if($astrologer->gender == 2){
          $data['gender'] = 'Female';
        }elseif($astrologer->gender == 3){
          $data['gender'] = 'Other';
        }
        $data['image'] = $astrologer->image ? $astrologer->image->image(200, 200) : no_image();
        $data['can_go_live'] = $astrologer->can_go_live?true:false;
        $data['last_logined'] = date('d F Y h:i a', strtotime($astrologer->last_logined));
        return response()->json(['success' => true, 'data' => $data], 200);
      }
      return response()->json(['success' => false, 'data' => []], 404);
    }

    /**
     * GET /api/astrologer/profile
     * Returns profile data for the authenticated astrologer (api_token in query, body or Bearer header).
     */
    public function profile(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }
        $country = DB::table('master_countries')->where('id', $astrologer->country_id)->first();
        $state = DB::table('master_states')->where('id', $astrologer->state_id)->first();
        $city = DB::table('master_cities')->where('id', $astrologer->city_id)->first();
        $data = [
            'id' => $astrologer->id,
            'uuid' => $astrologer->uuid,
            'name' => $astrologer->display_name,
            'first_name' => $astrologer->firstname,
            'last_name' => $astrologer->lastname,
            'email' => $astrologer->email,
            'phone' => $astrologer->phone,
            'phone_code' => $astrologer->phone_code ?: '',
            'country_id' => $astrologer->country_id ?: 0,
            'country' => $country ? $country->name : '',
            'state_id' => $astrologer->state_id ?: 0,
            'state' => $state ? $state->name : '',
            'city_id' => $astrologer->city_id ?: 0,
            'city' => $city ? $city->name : '',
            'pincode' => $astrologer->pincode ?: '',
            'address' => $astrologer->address ?: '',
            'area' => $astrologer->area ?: '',
            'landmark' => $astrologer->landmark ?: '',
            'gender_id' => $astrologer->gender ?: 0,
            'gender' => 'Male',
            'dob' => $astrologer->dob ?: '',
            'qualification' => $astrologer->qualification ?: '',
            'instagram' => $astrologer->instagram ?: '',
            'facebook' => $astrologer->facebook ?: '',
            'youtube' => $astrologer->youtube ?: '',
            'about' => $astrologer->about ?: '',
            'image' => $astrologer->image ? $astrologer->image->image(200, 200) : no_image(),
            'can_go_live' => $astrologer->can_go_live ? true : false,
            'last_logined' => $astrologer->last_logined ? date('d F Y h:i a', strtotime($astrologer->last_logined)) : '',
        ];
        if ($astrologer->gender == 2) {
            $data['gender'] = 'Female';
        } elseif ($astrologer->gender == 3) {
            $data['gender'] = 'Other';
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    /**
     * POST /api/astrologer/profile
     * Update profile for the authenticated astrologer (api_token in body or Bearer header).
     */
    public function profileUpdate(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }
        $request->validate([
            'first_name' => 'sometimes|max:255',
            'last_name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:100|unique:astrologers,email,' . $astrologer->id,
            'gender' => 'sometimes|in:1,2,3',
            'dob' => 'sometimes|date',
            'qualification' => 'sometimes|max:255',
            'address' => 'sometimes|max:500',
            'area' => 'nullable|max:255',
            'landmark' => 'nullable|max:255',
            'country_id' => 'sometimes|nullable|integer',
            'state_id' => 'sometimes|nullable|integer',
            'city_id' => 'sometimes|nullable|integer',
            'pincode' => 'sometimes|nullable|max:20',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'about' => 'sometimes|nullable|string|max:5000',
        ]);
        if ($request->has('first_name')) {
            $astrologer->firstname = $request->first_name;
        }
        if ($request->has('last_name')) {
            $astrologer->lastname = $request->last_name;
        }
        if ($request->has('email')) {
            $astrologer->email = $request->email;
        }
        if ($request->has('gender')) {
            $astrologer->gender = (int) $request->gender;
        }
        if ($request->has('dob')) {
            $astrologer->dob = $request->dob;
        }
        if ($request->has('qualification')) {
            $astrologer->qualification = $request->qualification;
        }
        if ($request->has('address')) {
            $astrologer->address = $request->address;
        }
        if ($request->has('area')) {
            $astrologer->area = $request->area;
        }
        if ($request->has('landmark')) {
            $astrologer->landmark = $request->landmark;
        }
        if ($request->has('country_id')) {
            $astrologer->country_id = $request->country_id ?: null;
        }
        if ($request->has('state_id')) {
            $astrologer->state_id = $request->state_id ?: null;
        }
        if ($request->has('city_id')) {
            $astrologer->city_id = $request->city_id ?: null;
        }
        if ($request->has('pincode')) {
            $astrologer->pincode = $request->pincode ?: null;
        }
        if ($request->has('instagram')) {
            $astrologer->instagram = $request->instagram ?: null;
        }
        if ($request->has('facebook')) {
            $astrologer->facebook = $request->facebook ?: null;
        }
        if ($request->has('youtube')) {
            $astrologer->youtube = $request->youtube ?: null;
        }
        if ($request->has('about')) {
            $astrologer->about = $request->about ?: null;
        }
        $astrologer->display_name = trim(($astrologer->firstname ?? '') . ' ' . ($astrologer->lastname ?? ''));
        $astrologer->save();
        return response()->json(['success' => true, 'message' => 'Profile updated successfully.', 'data' => ['id' => $astrologer->id]], 200);
    }

    public function logout(Request $request){
      $this->validate($request, [
        'api_token' => 'required',
      ]);
      $api_token = $request->api_token;
      $astrologer = Astrologer::where("api_token", $api_token)->first();
      // $astrologer->api_token = "";
      $astrologer->fcm_token = "";
      $astrologer->save();
      session(['app_login' => false]);
      return response()->json(['success'=>true, 'message'=>"Loged Out Successfully"], 402);
    }

    /**
     * GET/POST /api/astrologer/puja
     * Returns pujas assigned to the astrologer (one-to-one and group). Auth: api_token.
     */
    public function puja(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        $oneToOnePujas = Product::where('type', 2)
            ->where('is_one_to_one_puja', 1)
            ->where('status', 1)
            ->whereHas('astrologers', function ($q) use ($astrologer) {
                $q->where('astrologers.id', $astrologer->id);
            })
            ->orderBy('id', 'DESC')
            ->get();

        $groupPujaParentIds = Product::where('type', 2)
            ->where(function ($q) {
                $q->where('is_one_to_one_puja', '!=', 1)->orWhereNull('is_one_to_one_puja');
            })
            ->where('status', 1)
            ->where('pandit_id', $astrologer->id)
            ->where(function ($q) {
                $q->where('parent_id', 0)->orWhereNull('parent_id');
            })
            ->pluck('id')
            ->toArray();

        $variationParentIds = Product::where('type', 2)
            ->where(function ($q) {
                $q->where('is_one_to_one_puja', '!=', 1)->orWhereNull('is_one_to_one_puja');
            })
            ->where('status', 1)
            ->where('pandit_id', $astrologer->id)
            ->where('parent_id', '>', 0)
            ->pluck('parent_id')
            ->unique()
            ->toArray();

        $allParentIds = array_unique(array_merge($groupPujaParentIds, $variationParentIds));
        $groupPujas = Product::whereIn('id', $allParentIds)
            ->where(function ($q) {
                $q->where('parent_id', 0)->orWhereNull('parent_id');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $formatProduct = function ($p) {
            $expireAt = $p->expire_at
                ? (is_object($p->expire_at) ? $p->expire_at->format('Y-m-d H:i:s') : \Carbon\Carbon::parse($p->expire_at)->format('Y-m-d H:i:s'))
                : null;
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'image' => $p->image ? $p->image->image(200, 200) : no_image(),
                'expire_at' => $expireAt,
            ];
        };

        return response()->json([
            'success' => true,
            'data' => [
                'one_to_one_pujas' => $oneToOnePujas->map($formatProduct)->values(),
                'group_pujas' => $groupPujas->map($formatProduct)->values(),
            ],
        ], 200);
    }

    /**
     * GET/POST /api/astrologer/booked-puja
     * Returns booked pujas (assignments) for the astrologer. Auth: api_token.
     */
    public function bookedPuja(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        $allAssignments = PujaAstrologerAssignment::where('astrologer_id', $astrologer->id)
            ->where('status', 'assigned')
            ->with(['product', 'order.user', 'orderItem', 'mahurat'])
            ->withCount([
                'media as total_images_count' => function ($q) { $q->where('type', 'image'); },
                'media as total_videos_count' => function ($q) { $q->where('type', 'video'); },
            ])
            ->get();

        $oneToOneBookedPujas = $allAssignments->filter(function ($a) {
            if (!$a->product) return false;
            $p = $a->product;
            if ($p->expire_at && strtotime($p->expire_at) < time()) return false;
            return $p->type == 2 && $p->is_one_to_one_puja == 1 && $p->status == 1;
        })->values()->sortByDesc(function ($a) {
            return $a->assigned_at ?? $a->created_at;
        })->values();

        $parentProductIdsWithOrders = OrderItem::where('type', 'shop')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'Success')->where('status', 'Success');
            })
            ->whereHas('product', function ($q) use ($astrologer) {
                $q->where('type', 2)
                    ->where(function ($q2) {
                        $q2->where('is_one_to_one_puja', '!=', 1)->orWhereNull('is_one_to_one_puja');
                    })
                    ->where('status', 1)
                    ->where('pandit_id', $astrologer->id);
            })
            ->get()
            ->map(function ($item) {
                $product = $item->product;
                if ($product && $product->parent_id && $product->parent_id > 0) return $product->parent_id;
                return $product ? $product->id : null;
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $groupPujaProducts = Product::whereIn('id', $parentProductIdsWithOrders)
            ->where(function ($q) {
                $q->where('parent_id', 0)->orWhereNull('parent_id');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $oneToOneList = $oneToOneBookedPujas->map(function ($a) {
            return [
                'id' => $a->id,
                'assignment_id' => $a->id,
                'product_id' => $a->product_id,
                'title' => $a->product ? $a->product->title : '',
                'image' => $a->product && $a->product->image ? $a->product->image->image(200, 200) : no_image(),
                'order_id' => $a->order_id,
                'assigned_at' => $a->assigned_at ? $a->assigned_at->format('Y-m-d H:i:s') : null,
                'total_images_count' => $a->total_images_count ?? 0,
                'total_videos_count' => $a->total_videos_count ?? 0,
                'customer_name' => $a->order && $a->order->user ? $a->order->user->name : null,
            ];
        });

        $groupList = $groupPujaProducts->map(function ($p) {
            $variationIds = Product::where('parent_id', $p->id)->where('status', 1)->pluck('id')->toArray();
            $allIds = array_merge([$p->id], $variationIds);
            $orderItemsCount = OrderItem::whereIn('product_id', $allIds)
                ->where('type', 'shop')
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'Success')->where('status', 'Success');
                })
                ->count();
            return [
                'product_id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'image' => $p->image ? $p->image->image(200, 200) : no_image(),
                'order_items_count' => $orderItemsCount,
                'expire_at' => $p->expire_at ? (is_object($p->expire_at) ? $p->expire_at->format('Y-m-d H:i:s') : \Carbon\Carbon::parse($p->expire_at)->format('Y-m-d H:i:s')) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'one_to_one_booked_pujas' => $oneToOneList,
                'group_pujas' => $groupList,
            ],
        ], 200);
    }

    /**
     * GET/POST /api/astrologer/puja-earnings
     * Returns puja earnings (wallet transitions for puja_astrologer_assignments). Auth: api_token.
     */
    public function pujaEarnings(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        $pujaEarnings = WalletTransition::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->where('table_name', 'puja_astrologer_assignments')
            ->where('action', 1)
            ->where('status', 1)
            ->with(['pujaAssignment.orderItem.product', 'pujaAssignment.orderItem.order', 'pujaAssignment.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEarnings = $pujaEarnings->sum('amount');

        $list = $pujaEarnings->map(function ($t) {
            $assignment = $t->pujaAssignment;
            $productTitle = null;
            $orderId = null;
            if ($assignment) {
                if ($assignment->orderItem && $assignment->orderItem->product) {
                    $productTitle = $assignment->orderItem->product->title;
                } elseif ($assignment->product) {
                    $productTitle = $assignment->product->title;
                }
                $orderId = $assignment->order_id;
            }
            return [
                'id' => $t->id,
                'amount' => (float) $t->amount,
                'remark' => $t->remark,
                'created_at' => $t->created_at ? $t->created_at->format('Y-m-d H:i:s') : null,
                'product_title' => $productTitle,
                'order_id' => $orderId,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total_earnings' => (float) $totalEarnings,
                'list' => $list,
            ],
        ], 200);
    }

    /**
     * GET/POST /api/astrologer/referral-listing
     * Returns users and astrologers who registered using this astrologer's referral code. Auth: api_token.
     * Includes referral_code (required for app), plus optional reward/earnings fields for dynamic UI.
     */
    public function referralListing(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        // Ensure astrologer has a referral code (generate and persist if missing)
        if (empty($astrologer->referral_code)) {
            $astrologer->referral_code = Astrologer::generateReferralCode();
            $astrologer->save();
        }
        $referralCode = $astrologer->referral_code;

        $referredUsers = User::where('used_referral_code', $referralCode)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'name', 'created_at']);

        $referredAstrologers = Astrologer::where('used_referral_code', $referralCode)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'display_name', 'created_at']);

        // Optional: reward settings from astrologer_referrals (single global row)
        $referralSettings = AstrologerReferral::where('status', 1)->first();
        $rewardAmount = $referralSettings ? (float) $referralSettings->astrologer_reward : 100;
        $rewardPerReferral = $referralSettings ? (float) $referralSettings->astrologer_reward : 10;
        $referralGoal = 10; // default progress goal; can be made configurable later

        // Total earnings from referral wallet if it exists
        $referralWallet = ReferralWallet::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->first();
        $totalEarnings = $referralWallet ? (float) $referralWallet->lifetime_earnings : 0;
        if ($totalEarnings == 0 && ($referredUsers->count() + $referredAstrologers->count()) > 0) {
            $totalEarnings = ($referredUsers->count() + $referredAstrologers->count()) * $rewardPerReferral;
        }

        $formatUser = function ($u) use ($rewardPerReferral) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'created_at' => $u->created_at ? $u->created_at->format('Y-m-d H:i:s') : null,
                'amount' => $rewardPerReferral,
                'status' => 'completed',
            ];
        };
        $formatAstrologer = function ($a) use ($rewardPerReferral) {
            return [
                'id' => $a->id,
                'name' => $a->display_name,
                'created_at' => $a->created_at ? $a->created_at->format('Y-m-d H:i:s') : null,
                'amount' => $rewardPerReferral,
                'status' => 'completed',
            ];
        };

        $data = [
            // Referral code: multiple keys so app can read any of them
            'referral_code' => $referralCode,
            'referralCode' => $referralCode,
            'code' => $referralCode,
            'referred_users' => $referredUsers->map($formatUser)->values(),
            'referred_astrologers' => $referredAstrologers->map($formatAstrologer)->values(),
            // Optional: make rewards and progress dynamic in the app
            'reward_amount' => $rewardAmount,
            'reward_per_referral' => $rewardPerReferral,
            'referral_goal' => $referralGoal,
            'total_earnings' => round($totalEarnings, 2),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * GET/POST /api/astrologer/products-for-astrologer
     * Returns products selected for "Top Selling" in Astrologer Profile, with URL including astrologer referral code.
     * Auth: api_token. For app: show in profile and use Share / Copy URL (with ref) for tracking.
     */
    public function productsForAstrologer(Request $request)
    {
        $astrologer = $this->getAstrologerFromToken($request);
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Invalid or missing api_token.'], 401);
        }

        $referralCode = $astrologer->referral_code ?? '';

        // type can be stored as string in DB (VARCHAR)
        $products = Product::with('image')
            ->where('show_in_astrologer_profile', 1)
            ->whereIn('type', [1, 2, 3, '1', '2', '3'])
            ->orderByRaw('COALESCE(astrologer_profile_sort_order, 9999) ASC')
            ->orderBy('title', 'ASC')
            ->limit(50)
            ->get(['id', 'title', 'slug', 'type', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date', 'image_id']);

        $list = $products->map(function ($product) use ($referralCode) {
            $price = $product->base_price;
            if ($product->sale_price && $product->sale_price_start_date && $product->sale_price_end_date) {
                $today = date('Y-m-d');
                if ($today >= $product->sale_price_start_date && $today <= $product->sale_price_end_date) {
                    $price = $product->sale_price;
                }
            }
            $imageUrl = null;
            if ($product->image_id && $product->image) {
                $imageUrl = $product->image->image(300, 300);
            }
            return [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => (float) $price,
                'image' => $imageUrl,
                'url' => $product->getPublicDetailUrl($referralCode),
                'url_with_referral' => $product->getPublicDetailUrl($referralCode),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $list,
                'referral_code' => $referralCode,
            ],
        ], 200);
    }
}
