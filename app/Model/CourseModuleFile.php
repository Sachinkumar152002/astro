<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModuleFile extends Model
{
    // use SoftDeletes;
    public static function statusArray()
    {
        $status = [
            1 => 'Active',
            0 => 'Inactive',
        ];
        return $status;
    }

    public static function typesArray()
    {
    $types = [
    'pdf' => 'PDF',
            'wbt' => 'WBT',
    ];
    return $types;
    }

    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
        return $this->hasOne('App\Admin','id', 'updated_by');
    }


    public function getLinkAttribute(){

        if($this->file_type=='pdf') {
            return asset('file/'.$this->file);
        }
        else{
            return asset('file/'.$this->file.'/');
        }

    }
}
