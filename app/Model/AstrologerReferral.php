<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AstrologerReferral extends Model
{
    use SoftDeletes;

    protected $table = 'astrologer_referrals';

    protected $fillable = [
        'astrologer_reward',
        'user_reward',
        'minimum_price',
        'product_discount',
        'status'
    ];

    protected $casts = [
        'astrologer_reward' => 'decimal:2',
        'user_reward' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'product_discount' => 'decimal:2',
        'status' => 'integer'
    ];

}








