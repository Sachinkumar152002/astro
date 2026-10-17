<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PujaAstrologerAssignment extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'astrologer_id',
        'user_id',
        'status',
        'assigned_at',
        'completed_at',
        'notes',
        'display_in_user_profile',
        'display_in_astrologer_profile',
        'commission_paid',
        'commission_amount'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo('App\OrderItem', 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function astrologer()
    {
        return $this->belongsTo('App\Model\Astrologer', 'astrologer_id');
    }

    public function approvedMedia()
    {
        return $this->hasMany('App\Model\AstrologerPujaMedia', 'assignment_id')
            ->where('is_approved', 1);
    }

    public function media()
    {
        return $this->hasMany('App\Model\AstrologerPujaMedia', 'assignment_id');
    }

    public function mahurat()
    {
        return $this->hasOne('App\Model\PujaMahurat', 'assignment_id');
    }
}
