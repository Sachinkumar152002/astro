<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AstrologerLanguage extends Model
{
    public function language()
    {
    return $this->hasOne('App\Model\Language','id', 'language_id');
    }
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
