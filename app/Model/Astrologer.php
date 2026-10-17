<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\AstrologerEmailVerification;
use App\Notifications\AstrologerOtpVerfiedNotification;
use App\Notifications\AstrologerOnBoardNotification;
use App\Notifications\AstrologerNotLoginedSinc;
use App\Notifications\EmailWithCustomTextNotification;
use App\Notifications\SuccessfullyRecharge;
use App\Notifications\{SupportNotification, AstrologerRegistrationNotification, AstrologerVerifiedNotification, AccountDeleteRequestNotification, AccountDeleteRequestConfirmationNotification};
use App\{User};
use App\Model\{AiAstrologerAppointment};

class Astrologer extends Authenticate
{
    use Notifiable,SoftDeletes;

    protected $guard = 'astrologer';

    protected $fillable = [
        'name', 'email', 'password','register_from_ip','google_id', 'dob', 'firstname', 'lastname', 'display_name', 'birth_time', 'birth_place', 'gender', 'about', 'image_id', 'badge_id', 'type', 'status', 'is_verified', 'referral_code', 'used_referral_code', 'wallet', 'bank_upi_id',
        'qualification', 'working_with_other_app', 'area', 'instagram', 'facebook', 'youtube', 'any_healing',
        'aadhaar_number', 'aadhaar_front_id', 'aadhaar_back_id', 'cancel_cheque_id', 'astrology_certificate_id',
        'bank_city', 'bank_state', 'pan_card'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate referral code before creating (always generate own referral code)
        static::creating(function ($astrologer) {
          // Always generate own referral code (even if used_referral_code is provided)
          // Check if referral_code is null, empty string, or not set
          if(empty($astrologer->referral_code)){
            $astrologer->referral_code = self::generateReferralCode();
          }
        });

        static::created(function ($astrologer) {
          $AI = 'A';
          if($astrologer->type) $AI = $astrologer->type[0];
          $astrologer->uuid = 'MA-'.date('Ym').'-'.$AI.sprintf('%04d',$astrologer->id);
          $astrologer->save();
        });
    }

    /**
     * Generate unique referral code.
     * Format: P + 5 alphanumeric (e.g., P7K2Q9)
     * Checks astrologers and users tables for uniqueness
     * Note: astrologer_referrals table no longer has referral_code column
     */
    public static function generateReferralCode()
    {
        $maxAttempts = 1000; // Prevent infinite loop
        $attempts = 0;

        // Characters to use: uppercase letters and numbers
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $suffix = '';
            for($i = 0; $i < 5; $i++){
                $suffix .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $code = "P{$suffix}";

            $attempts++;

            // Check if code exists in astrologers table
            $existsInAstrologers = self::where('referral_code', $code)
                ->whereNull('deleted_at')
                ->exists();

            // Check if code exists in users table
            $existsInUsers = User::where('referral_code', $code)
                ->exists();

            // Note: astrologer_referrals table no longer has referral_code column, so we don't check it
            // Note: customer_referrals table no longer has referral_code column, so we don't check it

            // If code doesn't exist in any table, it's unique
            if (!$existsInAstrologers && !$existsInUsers) {
                return $code;
            }

        } while ($attempts < $maxAttempts);

        // If we couldn't generate a unique code after max attempts, throw exception
        throw new \Exception('Unable to generate unique referral code after ' . $maxAttempts . ' attempts. Please try again.');
    }

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function badge()
    {
    return $this->hasOne('App\Model\Badge','id', 'badge_id');
    }

    public function idProof()
    {
      return $this->hasOne('App\Model\Media','id', 'id_proof');
    }

    public function addressProof()
    {
      return $this->hasOne('App\Model\Media','id', 'address_proof');
    }

    public function aadhaarFront()
    {
      return $this->hasOne('App\Model\Media', 'id', 'aadhaar_front_id');
    }

    public function aadhaarBack()
    {
      return $this->hasOne('App\Model\Media', 'id', 'aadhaar_back_id');
    }

    public function cancelCheque()
    {
      return $this->hasOne('App\Model\Media', 'id', 'cancel_cheque_id');
    }

    public function astrologyCertificate()
    {
      return $this->hasOne('App\Model\Media', 'id', 'astrology_certificate_id');
    }

    public function panId()
    {
      return $this->hasOne('App\Model\Media','id', 'pan_id');
    }

    public function passbookId()
    {
      return $this->hasOne('App\Model\Media','id', 'passbook_id');
    }

    public function barId()
    {
      return $this->hasOne('App\Model\Media','id', 'bar_id_image_id');
    }

    public function educationId()
    {
      return $this->hasOne('App\Model\Media','id', 'education_id');
    }

    public function signatureId()
    {
      return $this->hasOne('App\Model\Media','id', 'signature');
    }

    public function tempProfile()
    {
    return $this->hasOne('App\temp_astrologer','astrologer_id', 'id')->where('status', 0)->where('is_publish', 0);
    }

    public function languages()
    {
    return $this->hasMany('App\Model\AstrologerLanguage','astrologer_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function services()
    {
    return $this->hasMany('App\Model\AstrologerService','astrologer_id', 'id');
    }
    public function reviews()
    {
        return $this->hasMany(AstrologerReview::class, 'astrologer_id', 'id')->where('status',1);
    }
    public function timetable()
    {
    return $this->hasMany('App\Model\AstrologerTimeTable','astrologer_id', 'id')->select('week_day', 'from_time', 'to_time');
    }

    public function getMyLanguageAttribute() {
        $langs = [];
        if($this->languages) {
            foreach($this->languages as $lang) {
                $langs[] =   $lang->language ? $lang->language->name : 'English';
            }
        }
        return implode(',', $langs);
    }
    public function getMyExpertiseAttribute() {
        $services = [];
        if($this->services) {
            foreach($this->services as $service) {
                $services[] =   $service && $service->service ? $service->service->short_name : '';
            }
        }
        return implode(', ',$services);
    }

    public function getMyExpertiseFullAttribute() {
        $services = [];
        if($this->services) {
            foreach($this->services as $astrologerService){
              if($astrologerService){
                $service = $astrologerService->service;
                $services[] =   $service ? $service->name : '';
              }
            }
        }
        return implode(', ',$services);
    }

    public function getMyExpertiseFullArrayAttribute() {
        $services = [];
        if($this->services) {
            foreach($this->services as $astrologerService){
              if($astrologerService){
                $service = $astrologerService->service;
                $services[] =   $service ? $service->name : '';
              }
            }
        }
        return $services;
    }

    public function getOfferPriceAttribute(){
        $today = strtotime(date('Y-m-d'));
        if(is_null($this->special_price)) return 0;
        $special_price = 0;
        if(is_null($this->sp_from_date) && is_null($this->sp_to_date) && $this->special_price!='0.00') {
            return 1;
        }elseif($this->sp_from_date && strtotime($this->sp_from_date)<=$today && is_null($this->sp_to_date)){
            return 1;
        }elseif($this->sp_to_date && strtotime($this->sp_to_date)>=$today && is_null($this->sp_from_date)){
            return 1;
        }elseif(strtotime($this->sp_from_date)<=$today && strtotime($this->sp_to_date)>=$today ){
            return 1;
        }
        return 0;
    }

    public static function getUniqueSlug($string = '', $n=null){
        $slugString = $string;
        if(!is_null($n)) {
            $slugString .= '-'.$string[$n];
        }
        $slug = str_slug($slugString);

        if(Astrologer::withTrashed()->where('slug', $slug)->count()==0) {
            return $slug;
        }
        if(is_null($n)) $n = 0;
        else $n++;

        if(strlen($string)==$n){
            return Astrologer::getUniqueSlug($string.time(),$n);
        }
        return Astrologer::getUniqueSlug($string,$n);
    }

    public function getYearExpAttribute(){
        // return  Carbon::parse($this->job_start_from)->diffInYears(Carbon::now());
        return $this->job_start_year ? date('Y') - $this->job_start_year : 0;
    }

    public function getPerMinuteRateAttribute()
    {
        return $this->offer_price ? $this->special_price : $this->price;
    }

    public function sendOtp($message = ''){
        $this->notify(new EmailWithCustomTextNotification('Login OTP', $message));
    }

    public function getApiDataAttribute(){

        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'display_name' => $this->display_name,
            'image_id' => $this->image_id,
            'avatar'   => $this->image ? $this->image->image(100) : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'about' => $this->about,
            'price' => $this->price,
            'special_price' => $this->special_price,
            'sp_from_date' => $this->sp_from_date,
            'sp_to_date' => $this->sp_to_date,
            'job_start_year' => $this->job_start_year?:date('Y'),
            'address' => $this->address,
            'pincode' => $this->pincode,
            'city_id' => $this->city_id,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
            'chat' => $this->chat,
            'call' => $this->call,
            'dob' => $this->dob,
            'dob_dd' => $this->dob_dd,
            'dob_mm' => $this->dob_mm,
            'dob_yyyy' => $this->dob_yyyy,
            'address_proof' => $this->address_proof,
            'address_proof_url' => $this->address_proof ? asset('uploads/astrologers/'.$this->address_proof) : null,
            'id_proof' => $this->id_proof,
            'id_proof_url' => $this->id_proof ? asset('uploads/astrologers/'.$this->id_proof) : null,
            'bank_name' => $this->bank_name,
            'bank_account_name' => $this->bank_account_name,
            'bank_account_number' => $this->bank_account_number,
            'bank_ifsc_code' => $this->bank_ifsc_code,
            'bank_branch' => $this->bank_branch,
            'bank_account_number_verify' => $this->bank_account_number_verify,
            'pan_card' => $this->pan_card,
            'languages' => $this->languages->pluck('language_id')->toArray(),
            'services' => $this->services->pluck('service_id')->toArray(),
            'timetable' => $this->timetable,
            'gender' => $this->gender,
            'pan_id' => $this->pan_id,
            'pan_id_url' => $this->pan_id ? asset('uploads/astrologers/'.$this->pan_id) : null,
            'days' => $this->timetable->pluck('week_day')->unique()->sort()->toArray()
        ];
    }

    public function sendEmailVerification(){
        $this->notify(new AstrologerEmailVerification($this));
    }
    public function sendWelcomeVerification(){
        $this->notify(new AstrologerRegistrationNotification($this));
    }
    public function sendApprovedNotification(){
        $this->notify(new AstrologerVerifiedNotification($this));
    }
    public function pricing()
    {
        return $this->hasOne(Pricing::class);
    }
    public function sendEmailOTPVerified(){
        $this->notify(new AstrologerOtpVerfiedNotification($this));
    }
    public function sendOnBoard(){
        $this->notify(new AstrologerOnBoardNotification($this));
    }
    public function notLogined(){
        $this->notify(new AstrologerNotLoginedSinc($this));
    }

    public function sendSupportNotification($ticket){
        $this->notify(new SupportNotification($this, 'astrologer', $ticket));
    }

    public function packages()
    {
        return $this->hasMany('App\Model\AstrologerAppointmentPackage', 'astrologer_id', 'id');
    }

    public function canReview(){
      return $this->hasMany('App\Model\AstrologerAppointment', 'astrologer_id', 'id')->whereNotIn('status', ['pending', 'failed', 'cancel', 'booked']);
    }

    public function astrologerTimeTable()
    {
      return $this->hasMany('App\Model\AstrologerTimeTable', 'astrologer_id', 'id');
    }

    public function getCurrentDayTimeAttribute(){
      $day = date("w");
      $id = $this->id;
      return AstrologerTimeTable::where('astrologer_id', $id)->where('week_day', $day)->first();
    }

    public function gettDayTimeAttribute(){
      // $day = date("w");
      // return $day;
      $id = $this->id;
      return AstrologerTimeTable::where('astrologer_id', $id)->first();
    }
    public function videoAppointmentsBooking(){ // all which astrologer booked as user
      return $this->hasMany('App\Model\AstrologerAppointment','to_astrologer_id', 'id')->where('package', 1)->where('status', '!=', 'cancel');
    }
    public function callAppointmentsBooking(){ // all which astrologer booked as user
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\AstrologerAppointment','to_astrologer_id', 'id')->where('package', 3)->where('status', '!=', 'cancel');
    }
    public function chatAppointmentsBooking(){  // all which astrologer booked as user
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\AstrologerAppointment','to_astrologer_id', 'id')->where('package', 2)->where('status', '!=', 'cancel');
    }
    public function videoAppointments(){ // all
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->where('status', '!=', 'cancel');
    }
    public function callAppointments(){ // all
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->where('status', '!=', 'cancel');
    }
    public function chatAppointments(){ // all
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->where('status', '!=', 'cancel');
    }
    public function shippingAddress()
    {
      return $this->hasOne('App\Model\ShippingAddress','user_id', 'id')->where('user_type', 'astrologer');
    }

    public function successfullyRecharge($message = ''){
        $this->notify(new SuccessfullyRecharge($this, $message));
    }
    public function openSupportTickets(){
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type)->where('status', 'open');
    }
    public function closeSupportTickets(){
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type)->where('status', 'closed');
    }
    public function alotCourses(){
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\CourseAlot','user_id', 'id')->where('user_type', $user_type);
    }
    public function userr(){
      return $this->hasOne('App\User', 'astrologer_id', 'id')->where('is_verified', 1);
    }
    public function payoutPackage(){
      return $this->hasOne('App\Model\PayoutPackage', 'id', 'payout_package_id');
    }
    public function astrologerPayoutPackage($payout_package_id){
      return AstrologerPayoutPackage::where('astrologer_id', $this->id)->where('end_date', '>=', date('Y-m-d'))->orderBy('start_date', 'desc')->first();
    }
    public function currentAppointment(){
      $currentTime = date('H:i');
      return AstrologerAppointment::where('astrologer_id', $this->id)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('scheduled_time', '<=', $currentTime)
      ->where('max_time', '>=', $currentTime)
      ->whereNotIn('status', ['pending', 'cancel', 'failed'])
      ->whereNotNull('user_end_time')
      ->whereNotNull('astrologer_end_time')
      ->first();
    }

    public function withInThirtyMinutes(){
      return AstrologerAppointment::whereIn('status', ['booked'])
      ->where('astrologer_id', $this->id)
      ->where('scheduled_date', date('Y-m-d'))
      ->whereBetween('scheduled_time', [date('H:i:00', strtotime('now')), date('H:i:00', strtotime('now+30minutes'))])
      ->first();
    }

    public function checkCurrentAppointment($packageArray){
      $currentTime = date('H:i:00');
      $currentTimeForMaxTime = date('H:i:00', strtotime('now+3minutes'));
      return AstrologerAppointment::whereIn('package', $packageArray)
      ->where('astrologer_id', $this->id)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('scheduled_time', '<=', $currentTime)
      ->where('max_time', '>', $currentTimeForMaxTime)
      ->whereNotIn('status', ['pending', 'cancel', 'failed', 'done'])
      ->where('is_notification_sent', 0)
      ->first();
    }

    public function getNameAttribute(){
      return $this->display_name;
    }

    public function deleteAccountMail(){
        $this->notify(new AccountDeleteRequestNotification($this));
    }
    public function deleteAccountMailConfirmation(){
        $this->notify(new AccountDeleteRequestConfirmationNotification($this));
    }
    public function appointments(){
      return $this->hasMany('App\Model\AstrologerAppointment', 'astrologer_id', 'id')->whereNotIn('status', ['pending', 'failed', 'cancel']);
    }
    public function aiAppointments(){
      return $this->hasMany('App\Model\AiAstrologerAppointment', 'astrologer_id', 'id');
    }
    public function supports(){
      $user_type = 'astrologer';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type);
    }
    public function notifications(){
      return $this->hasMany('App\Model\Notification', 'astrologer_id', 'id');
    }
    public function aiConsultations($user_id){
      $astrologer_id = $this->id;
      return AiAstrologerAppointment::where("astrologer_id", $astrologer_id)->where("user_id", $user_id)->where("paid", 0)->first();
    }

    public function pujas(){
      return $this->belongsToMany('App\Product', 'puja_astrologers', 'astrologer_id', 'product_id')
                  ->wherePivot('status', 1)
                  ->orderByPivot('priority', 'asc')
                  ->withPivot('priority', 'status')
                  ->withTimestamps();
    }

    public function pujaAssignments(){
      return $this->hasMany('App\Model\PujaAstrologerAssignment', 'astrologer_id', 'id');
    }

}
