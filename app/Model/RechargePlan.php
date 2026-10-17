<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RechargePlan extends Model
{
    protected $hidden = [
        'created_at', 'updated_at','status'
    ];
}
