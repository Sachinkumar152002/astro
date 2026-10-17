<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class temp_astrologer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
    'firstname' => 'json',
    'lastname' => 'json',
    'astrologer_id' => 'json',
    'call_price' => 'json',
    'call_special_price' => 'json',
    'chat_price' => 'json',
    'chat_special_price' => 'json',
    'price' => 'json',
    'special_price' => 'json',
    'day' => 'json',
    'status' => 'json',
    'display_name' => 'json',
    'email' => 'json',
    'phone' => 'json',
    'day' => 'json',
    'month' => 'json',
    'year' => 'json',
    'gender' => 'json',
    'about' => 'json',
    'address' => 'json',
    'country' => 'json',
    'state' => 'json',
    'city' => 'json',
    'zip_code' => 'json',
    'bank_account_name' => 'json',
    'bank_account_number' => 'json',
    'bank_account_number_verify' => 'json',
    'bank_branch' => 'json',
    'bank_ifsc_code' => 'json',
    'bank_name' => 'json',
    'pan_card' => 'json',
    'job_start_year' => 'json',
    'sp_from_date' => 'json',
    'sp_to_date' => 'json',
    'languages' => 'json',
    'experties' => 'json',
 ];

 public function image()
 {
   return $this->hasOne('App\Model\Media','id', 'image_id');
 }

 public function idProof()
 {
   return $this->hasOne('App\Model\Media','id', 'id_proof');
 }

 public function addressProof()
 {
   return $this->hasOne('App\Model\Media','id', 'address_proof');
 }
 public function signatureImg()
 {
   return $this->hasOne('App\Model\Media','id', 'signature');
 }

 public function panId()
 {
   return $this->hasOne('App\Model\Media','id', 'pan_id');
 }

 public function passbookId()
 {
   return $this->hasOne('App\Model\Media','id', 'passbook_id');
 }

 public function astrologer(){
   return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
 }
}
