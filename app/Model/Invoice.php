<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Order;
use App\Model\UserRechargeHistory;

class Invoice extends Model
{
    use HasFactory;
    public function getRelationAttribute(){
      $relation_type = $this->relation_type;
      $relation_id = $this->relation_id;
      if($relation_type=='order'){
        return Order::find($relation_id);
      }
      return UserRechargeHistory::find($relation_id);
    }
}
