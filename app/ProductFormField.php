<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFormField extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function formFields() {
      return $this->hasMany('App\Product','id','product_id');
    }

    public function field() {
      return $this->hasOne('App\Form','id','form_field');
    }
}
