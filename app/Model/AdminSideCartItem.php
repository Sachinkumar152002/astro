<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSideCartItem extends Model
{
    use HasFactory;
    public function product(){
    return $this->hasOne('App\Product','id', 'item_id');
    }
}
