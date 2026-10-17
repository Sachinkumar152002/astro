<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommissionTransaction extends Model
{
    protected $table = 'commission_transactions';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'order_id',
        'order_item_id',
        'category_id',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'max_cap_applied',
        'commission_mode',
        'credit_time',
        'status',
        'credited_at',
        'refunded_at'
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'max_cap_applied' => 'decimal:2',
        'credited_at' => 'datetime',
        'refunded_at' => 'datetime'
    ];

    public function referrer()
    {
        if($this->referrer_type == 'user') {
            return $this->belongsTo('App\User', 'referrer_id');
        }
        return $this->belongsTo('App\Model\Astrologer', 'referrer_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo('App\OrderItem', 'order_item_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Model\Category', 'category_id');
    }
}
