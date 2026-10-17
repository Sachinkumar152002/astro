<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferredBy extends Model
{
    use HasFactory;
    protected $table = 'referred_by';
}
