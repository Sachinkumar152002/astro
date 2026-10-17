<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductReferralConfiguration extends Model
{
    protected $table = 'product_referral_configurations';

    protected $fillable = [
        'enable_product_referral',
        'applicable_user_types',
        'commission_mode',
        'trigger',
        'credit_time',
        'status'
    ];

    protected $casts = [
        'enable_product_referral' => 'boolean',
        'applicable_user_types' => 'array',
        'status' => 'integer'
    ];
}
