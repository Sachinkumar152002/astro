<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequestItem extends Model
{
    use HasFactory;
    public function item(){
       return $this->hasOne('App\OrderItem', 'id', 'item_id');
    }
}
