<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AstrologerService extends Model
{
    public function service()
    {
    return $this->hasOne('App\Model\Service','id', 'service_id');
    }
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    public function astrologer()
    {
    return $this->hasOne('App\Model\Astrologer','id', 'astrologer_id');
    }
}
