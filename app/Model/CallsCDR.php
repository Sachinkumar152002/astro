<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CallsCDR extends Model
{
    protected $fillable = [
        'id',
        'call_id',
        'uuid',
        'direction',
        'description',
        'detailed_description',
        'status',
        'blocked_number_id',
        'recording_url',
        'service',
        'date',
        'time',
        'end_stamp',
        'broadcast_id',
        'dtmf_input',
        'call_duration',
        'answered_seconds',
        'minutes_consumed',
        'charges',
        'department_name',
        'agent_number',
        'agent_name',
        'client_number',
        'did_number',
        'reason',
        'hangup_cause',
        'notes',
        'contact_details',
        'missed_agents',
        'call_flow',
        'accountid',
        'agent_ring_time',
        'circle',
        'agent_hangup_data',
        'transfer_missed_agent',
        'customer_ring_time',
        'total_hold_duration',
        'hold_count',
        'disposition_name',
        'disposition_code',
        'is_calc',
    ];
}
