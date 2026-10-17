<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'weight', 'shipment_width', 'shipment_height',
        'shipment_length', 'shipping_mode', 'status', 'waybill',
        'consignment_request', 'consignment_response', 'pickup_time',
        'pickup_date', 'pickup_location', 'package_count', 'pickup_request',
        'pickup_response', 'pickup_id', 'delivery_type', 'courier_company_name',
        'awb_number', 'tracking_url', 'fragile', 'type'
    ];

    public function orders(){
      return $this->hasOne('App\Order', 'id', 'order_id');
    }

    public function trackConsignment(){
      return $this->hasMany('App\Model\TrackConsignment', 'consignment_id', 'id');
    }
}
