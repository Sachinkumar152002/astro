<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = "forms";
    public function field (){
      return $this->hasOne('App\ProductFormField', 'form_field', 'id');
    }
}
