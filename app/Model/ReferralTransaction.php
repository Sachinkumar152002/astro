<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralTransaction extends Model
{
    protected $table = 'referral_transactions';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'referred_id',
        'referred_type',
        'reward_amount',
        'reward_trigger',
        'status',
        'expiry_date',
        'credited_at',
        'order_id'
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'expiry_date' => 'datetime',
        'credited_at' => 'datetime'
    ];

    public function referrer()
    {
        if($this->referrer_type == 'user') {
            return $this->belongsTo('App\User', 'referrer_id');
        }
        return $this->belongsTo('App\Model\Astrologer', 'referrer_id');
    }

    public function referred()
    {
        if($this->referred_type == 'user') {
            return $this->belongsTo('App\User', 'referred_id');
        }
        return $this->belongsTo('App\Model\Astrologer', 'referred_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
