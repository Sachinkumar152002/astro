<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WalletLedger extends Model
{
    protected $table = 'wallet_ledgers';

    protected $fillable = [
        'wallet_type',
        'user_id',
        'user_type',
        'transaction_type',
        'amount',
        'balance_after',
        'reference_type',
        'reference_id',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2'
    ];

    public function user()
    {
        if($this->user_type == 'user') {
            return $this->belongsTo('App\User', 'user_id');
        }
        return $this->belongsTo('App\Model\Astrologer', 'user_id');
    }
}
