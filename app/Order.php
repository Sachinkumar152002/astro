<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\{OrderPlaced, OrderFailedNotification, OrderStatusNotification};

class Order extends Model
{
    use HasFactory, Notifiable;
    protected $guarded = [];
    protected $user_type;
    public function orderItems()
    {
       return $this->hasMany('App\OrderItem', 'order_id', 'id');
    }
    public function getCountryNameAttribute(){
      $id = $this->country;
      return \DB::table('master_countries')->where('id', $id)->first();
    }
    public function getStateNameAttribute(){
      $id = $this->state;
      return \DB::table('master_states')->where('id', $id)->first();
    }
    public function getCityNameAttribute(){
      $id = $this->city;
      return \DB::table('master_cities')->where('id', $id)->first();
    }
    public function sendOrderPlacedNotification($pdfUrl, $user_type){
      $this->user_type = $user_type;
        $this->notify(new OrderPlaced($this, $pdfUrl, $user_type));
    }
    public function sendOrderFailedNotification(){
        $this->notify(new OrderFailedNotification($this));
    }
    public function sendOrderStatusNotification($user_type){
      $this->user_type = $user_type;
        $this->notify(new OrderStatusNotification($this, $user_type));
    }
    public function routeNotificationForMail(){
      if($this->user_type == 'admin' || $this->user_type == 'admin-course'){
        return config("vrdcms.admin_email");
      }
      return $this->email;
    }
    public function invoice(){
       return $this->hasOne('App\Model\Invoice', 'relation_id', 'id')->where('relation_type', 'order');
    }
    public function user(){
      $user_id = $this->user_id;
      $user_type = $this->user_type;
      return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function consignment(){
       return $this->hasOne('App\Model\Consignment', 'order_id', 'id')->where('status', 'success')->where('type', 0)->orderBy('id', 'desc');
    }
    public function returnConsignment(){
       return $this->hasOne('App\Model\Consignment', 'order_id', 'id')->where('status', 'success')->where('type', 1);
    }
    public function cancelRequests(){
       return $this->hasMany('App\Model\OrderRequest', 'order_id', 'id')->where('request_type', 'cancel');
    }
    public function returnRequests(){
       return $this->hasMany('App\Model\OrderRequest', 'order_id', 'id')->where('request_type', 'return');
    }

    public function requests(){
      return $this->hasMany('App\Model\OrderRequest', 'order_id', 'id');
    }


}
