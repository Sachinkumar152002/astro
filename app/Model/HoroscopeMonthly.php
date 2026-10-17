<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HoroscopeMonthly extends Model
{
    public function sign()
    {
    return $this->hasOne('App\Model\ZodiacSign','id', 'zodiac_sign_id');
    }
}