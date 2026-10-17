<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\UserEmailVerification;
use App\Notifications\UserWelcomeNotification;
use App\Notifications\UserRegisterNotification;
use App\Notifications\SupportNotification;
use App\Notifications\EmailWithCustomTextNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','google_id','status', 'is_verified', 'register_from_ip', 'wallet', 'facebook_id', 'apple_id', 'phone_code', 'referral_code', 'used_referral_code', 'is_influencer', 'account_number', 'ifsc_code', 'upi_id', 'passbook_image_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate referral code before creating (always generate own referral code)
        static::creating(function ($user) {
          // Always generate own referral code (even if used_referral_code is provided)
          if(!$user->referral_code){
            $user->referral_code = self::generateReferralCode();
          }
        });

        static::created(function ($user) {
          $user->uuid = 'MA-'.date('Ym').'-C'.sprintf('%04d',$user->id);
          $user->save();
        });
    }

    /**
     * Generate unique referral code.
     * Format: C + 5 alphanumeric (e.g., C7K2Q9)
     * Checks users and astrologers tables for uniqueness
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
            $code = "C{$suffix}";

            $attempts++;

            // Check if code exists in users table
            $existsInUsers = self::where('referral_code', $code)
                ->whereNull('deleted_at')
                ->exists();

            // Check if code exists in astrologers table
            $existsInAstrologers = \App\Model\Astrologer::where('referral_code', $code)
                ->whereNull('deleted_at')
                ->exists();

            // Note: astrologer_referrals table no longer has referral_code column, so we don't check it
            // Note: customer_referrals table no longer has referral_code column, so we don't check it

            // If code doesn't exist in any table, it's unique
            if (!$existsInUsers && !$existsInAstrologers) {
                return $code;
            }

        } while ($attempts < $maxAttempts);

        // If we couldn't generate a unique code after max attempts, throw exception
        throw new \Exception('Unable to generate unique referral code after ' . $maxAttempts . ' attempts. Please try again.');
    }

    public function sendEmailVerification(){
        $this->notify(new UserEmailVerification($this));
    }

    public function sendWelcomeVerification(){
        $this->notify(new UserRegisterNotification($this));
        // $this->notify(new UserWelcomeNotification($this));
    }

    public function sendSupportNotification($ticket){
        $this->notify(new SupportNotification($this, 'user', $ticket));
    }
    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }
    public function sendOtp($message = ''){
        $this->notify(new EmailWithCustomTextNotification('Login OTP', $message));
    }
    public function getApiDataAttribute(){
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'wallet' => $this->wallet,
            'city_id' => $this->city_id,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'address' => $this->address,
            'state' => $this->state,
            'country' => $this->country,
            'pincode' => $this->pincode,
            'birth_date' => $this->birth_date,
            'birth_time' => $this->birth_time,
            'birth_place' => $this->birth_place,
            'dob_dd' => $this->dob_dd,
            'dob_mm' => $this->dob_mm,
            'dob_yyyy' => $this->dob_yyyy,
            'gender' => $this->gender,
            'language_id' => $this->language_id,
            'loyality_points' => $this->loyality_points,
        ];
    }
    public function image()
    {
      return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function passbookImage()
    {
      return $this->hasOne('App\Model\Media','id', 'passbook_image_id');
    }
    public function shippingAddress()
    {
      return $this->hasOne('App\Model\ShippingAddress','user_id', 'id')->where('user_type', 'user');
    }
    public function additionalInformation()
    {
      return $this->hasOne('App\Model\UserAdditionalInformation','user_id', 'id');
    }
    public function videoAppointments(){ // all
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->whereNotIn('user_status', ['cancel', 'pending', 'reschedule']);
    }
    public function videoAppointmentsUpcoming(){ // only upcoming
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->where('user_status', 'booked');
    }
    public function videoAppointmentsDone(){ // only which is done
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 1)->where('user_status', '!=', 'booked')->orWhere('user_status', '!=', 'cancel');
    }
    public function callAppointments(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 3)->whereNotIn('user_status', ['cancel', 'pending', 'reschedule']);
    }
    public function callAppointmentsUpcoming(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 3)->where('user_status', 'booked');
    }
    public function callAppointmentsDone(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 3)->where('user_status', '!=', 'booked')->orWhere('user_status', '!=', 'cancel');
    }
    public function chatAppointments(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 2)->whereNotIn('user_status', ['cancel', 'pending', 'reschedule']);
    }
    public function chatAppointmentsUpcoming(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 2)->where('user_status', 'booked');
    }
    public function chatAppointmentsDone(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type)->where('package', 2)->where('user_status', '!=', 'booked')->orWhere('user_status', '!=', 'cancel');
    }
    public function supports(){
      $user_type = 'user';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type);
    }
    public function openSupportTickets(){
      $user_type = 'user';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type)->where('status', 'open');
    }
    public function closeSupportTickets(){
      $user_type = 'user';
      return $this->hasMany('App\Model\Support','user_id', 'id')->where('user_type', $user_type)->where('status', 'closed');
    }
    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id')->where('is_verified', 1)->where('deleted_at', null);
    }
    public function courses(){
      return $this->hasMany('App\Model\LmsCourse', 'user_id', 'id')->where('expire_date', '>=', date('Y-m-d'));
    }
    public function allcourses(){
      return $this->hasMany('App\Model\LmsCourse', 'user_id', 'id')->where('expire_date', '>=', date('Y-m-d'));
    }
    public function cartItems(){
      return $this->hasMany('App\CartItem', 'user_id', 'id')->where('user_type', 'user');
    }
    public function notifications(){
      return $this->hasMany('App\Model\Notification', 'user_id', 'id');
    }

    public function rechargeHistory(){
      return $this->hasMany('App\Model\UserRechargeHistory', 'user_id', 'id');
    }

    public function education(){
      return $this->hasOne('App\Model\UserAdditionalInformation', 'user_id', 'id');
    }

    public function appointments(){
      $user_type = 'user';
      return $this->hasMany('App\Model\AstrologerAppointment','user_id', 'id')->where('user_type', $user_type);
    }
}
