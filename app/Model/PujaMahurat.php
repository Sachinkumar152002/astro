<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PujaMahurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'date',
        'time',
        'reminder_4hr_sent',
        'reminder_2hr_sent',
        'reminder_1hr_sent'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function assignment()
    {
        return $this->belongsTo('App\Model\PujaAstrologerAssignment', 'assignment_id');
    }
}
