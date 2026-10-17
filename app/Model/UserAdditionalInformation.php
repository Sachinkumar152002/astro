<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdditionalInformation extends Model
{
    use HasFactory;
    protected $table = 'user_additional_informations';

    public function image()
    {
      return $this->hasOne('App\Model\Media','id', 'file');
    }
}
