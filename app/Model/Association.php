<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Association extends Model
{
    use SoftDeletes;
    public static function statusArray()
    {
    $status = [
    1 => 'Active',
            0 => 'Inactive',
    ];
    return $status;
    }

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
    return $this->hasOne('App\Admin','id', 'updated_by');
    }
}
