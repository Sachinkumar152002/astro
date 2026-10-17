<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LikeDislike extends Model
{
    protected $fillable = ['article_id', 'type', 'client_ip'];
}
