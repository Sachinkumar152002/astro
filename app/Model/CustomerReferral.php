<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerReferral extends Model
{
    use SoftDeletes;

    protected $table = 'customer_referrals';

    protected $fillable = [
        'user_reward',
        'referrer_reward',
        'status'
    ];

    protected $casts = [
        'user_reward' => 'decimal:2',
        'referrer_reward' => 'decimal:2',
        'status' => 'integer'
    ];

}








