<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransition extends Model
{
    use HasFactory;

    public function appointments(){
      return $this->hasMany('App\Model\AstrologerAppointment', 'id', 'table_id');
    }

    public function pujaAssignment(){
      return $this->belongsTo('App\Model\PujaAstrologerAssignment', 'table_id', 'id');
    }
}
