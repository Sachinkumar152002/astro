<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Astrologer;
use App\Model\AstrologerAppointment;
class AstrologerUserConsultacy extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function astrologer()
    {
        return $this->belongsTo(Astrologer::class, 'astrologer_id');
    }
    public function astrologer_appointment()
    {
        return $this->belongsTo(AstrologerAppointment::class, 'astrologer_appointment_id');
    }
}
