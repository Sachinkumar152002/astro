<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;
    public function orderRequestItems(){
       return $this->hasMany('App\Model\OrderRequestItem', 'request_id', 'id');
    }
    public function response(){
       return $this->hasOne('App\Model\OrderRequestResponse', 'request_id', 'id');
    }
    public function order(){
       return $this->hasOne('App\Order', 'id', 'order_id');
    }
}
