<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badges';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];

    public function createdby()
    {
        return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
        return $this->hasOne('App\Admin','id', 'updated_by');
    }
}
