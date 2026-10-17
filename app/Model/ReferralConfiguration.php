<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralConfiguration extends Model
{
    protected $table = 'referral_configurations';

    protected $fillable = [
        'enable_app_referral',
        // Astrologer to Astrologer
        'astrologer_to_astrologer_reward_amount',
        'astrologer_to_astrologer_trigger',
        'astrologer_to_astrologer_min_consultation_amount',
        'astrologer_to_astrologer_max_referrals',
        'astrologer_to_astrologer_max_earning',
        'astrologer_to_astrologer_expiry_days',
        'astrologer_to_astrologer_status',
        // Astrologer to Customer
        'astrologer_to_customer_reward_amount',
        'astrologer_to_customer_trigger',
        'astrologer_to_customer_min_purchase_amount',
        'astrologer_to_customer_max_referrals',
        'astrologer_to_customer_max_earning',
        'astrologer_to_customer_expiry_days',
        'astrologer_to_customer_status',
        // Customer
        'customer_reward_amount',
        'customer_trigger',
        'customer_min_purchase_amount',
        'customer_max_referrals',
        'customer_max_earning',
        'customer_expiry_days',
        'customer_status',
        'status'
    ];

    protected $casts = [
        'enable_app_referral' => 'boolean',
        'astrologer_to_astrologer_reward_amount' => 'decimal:2',
        'astrologer_to_astrologer_min_consultation_amount' => 'decimal:2',
        'astrologer_to_astrologer_max_earning' => 'decimal:2',
        'astrologer_to_astrologer_max_referrals' => 'integer',
        'astrologer_to_astrologer_expiry_days' => 'integer',
        'astrologer_to_astrologer_status' => 'integer',
        'astrologer_to_customer_reward_amount' => 'decimal:2',
        'astrologer_to_customer_min_purchase_amount' => 'decimal:2',
        'astrologer_to_customer_max_earning' => 'decimal:2',
        'astrologer_to_customer_max_referrals' => 'integer',
        'astrologer_to_customer_expiry_days' => 'integer',
        'astrologer_to_customer_status' => 'integer',
        'customer_reward_amount' => 'decimal:2',
        'customer_min_purchase_amount' => 'decimal:2',
        'customer_max_earning' => 'decimal:2',
        'customer_max_referrals' => 'integer',
        'customer_expiry_days' => 'integer',
        'customer_status' => 'integer',
        'status' => 'integer'
    ];
}
