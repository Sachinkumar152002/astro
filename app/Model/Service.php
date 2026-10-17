<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

  public static function selectTreeArray($parent = 0, $prefix = '', $id = 0){
    $data = ['0'=>'Select'];
    $services = self::where('parent_id',$parent)->where('id', '!=', $id)->orderBy('name', 'asc')->get();
    if(count($services)>0){
      foreach($services as $service){
        $data[$service->id] = $prefix.$service->name;
        $child = self::selectTreeArray($service->id, $prefix.'- ', $id);
        foreach($child as $k=>$v) {
          $data[$k] = $v;
        }
      }
    }
    return $data;
  }
  public function icon(){
    return $this->hasOne('App\Model\Media','id', 'icon_id');
  }

}
