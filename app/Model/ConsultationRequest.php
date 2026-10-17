<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    use HasFactory;
    public function consultation_request_responses(){
      return $this->hasOne('\App\Model\ConsultationRequestResponse', 'request_id', 'id');
    }
}
