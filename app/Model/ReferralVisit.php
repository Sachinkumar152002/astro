<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReferralVisit extends Model
{
    protected $table = 'referral_visits';

    protected $fillable = [
        'referral_code',
        'visitor_id',
        'visited_on',
        'ip',
        'user_agent',
        'landing_url',
        'referrer_url',
    ];

    protected $casts = [
        'visited_on' => 'date',
    ];
}
