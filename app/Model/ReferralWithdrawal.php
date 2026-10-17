<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralWithdrawal extends Model
{
    protected $table = 'referral_withdrawals';

    protected $fillable = [
        'astrologer_id',
        'wallet_type',
        'referral_amount',
        'commission_amount',
        'total_amount',
        'status',
        'admin_notes',
        'processed_at'
    ];

    protected $casts = [
        'referral_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    public function astrologer()
    {
        return $this->belongsTo('App\Model\Astrologer', 'astrologer_id');
    }
}
