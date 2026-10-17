<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RudrakshaConsultation extends Model
{
    use HasFactory;

    protected $table = 'rudraksha_consultation';

    const PAYMENT_SUCCESS = 1;
    const PAYMENT_FAILED = 0;
    const PAYMENT_PENDING = 2;

    protected $fillable = [
        'fullName', 'email', 'mobile', 'dob', 'birthTime', 'birthPlace', 'message', 'payment_status', 'amount','module','payment_id','payment_method'
    ];

}
