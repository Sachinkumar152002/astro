<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AstrologerPujaMedia extends Model
{
    protected $table = 'astrologer_puja_media';

    protected $fillable = [
        'assignment_id',
        'astrologer_id',
        'product_id',
        'order_id',
        'group_puja_reference',
        'media_id',
        'video_id',
        'type',
        'is_approved'
    ];

    public function assignment()
    {
        return $this->belongsTo('App\Model\PujaAstrologerAssignment', 'assignment_id');
    }

    public function astrologer()
    {
        return $this->belongsTo('App\Model\Astrologer', 'astrologer_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function media()
    {
        return $this->belongsTo('App\Model\Media', 'media_id');
    }

    public function video()
    {
        return $this->belongsTo('App\Model\Media', 'video_id');
    }
}
