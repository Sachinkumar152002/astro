<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VisitorCookie extends Model
{
    protected $fillable = ['cookie','value'];

    public function getByKey($key){
        if($this->value){
            $values = json_decode($this->value, true, 10);
            return isset($values[$key]) ? $values[$key] : false;
        }
        return false;
    }
}
