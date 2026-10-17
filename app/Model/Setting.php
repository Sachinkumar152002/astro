<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'key', 'value', 'type'];
    public function logoFn(){
      return $this->hasOne('App\Model\Media','id','value');
    }
    public function director_signFn(){
      return $this->hasOne('App\Model\Media','id','value');
    }

}
