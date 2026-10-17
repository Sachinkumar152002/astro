<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LiveCall extends Model
{
    protected $fillable = ['id','user_id','client_id','call_id','direction','source','type','did','multiple_destination_type','multiple_destination_name','destination','state','queue_state','channel_id','sip_domain','broadcast_id','broadcast_no','call_time','agent_name','created_at'];
}
