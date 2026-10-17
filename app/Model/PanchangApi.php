<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PanchangApi extends Model
{
    protected $fillable = ['date', 'time', 'timezone', 'response'];
}
