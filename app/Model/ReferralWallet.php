<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralWallet extends Model
{
    protected $table = 'referral_wallets';

    protected $fillable = [
        'user_id',
        'user_type',
        'balance',
        'lifetime_earnings'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'lifetime_earnings' => 'decimal:2'
    ];

    public function user()
    {
        if($this->user_type == 'user') {
            return $this->belongsTo('App\User', 'user_id');
        }
        return $this->belongsTo('App\Model\Astrologer', 'user_id');
    }
}
