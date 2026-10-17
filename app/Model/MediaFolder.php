<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    use HasFactory;

    public static function breads($id = 0){
    $data = [];

    $category = self::find($id);
        if($category){
            $data[$category->id]  = $category->name;
            if($parent_id = $category->parent_id) {
                $data =  self::breads($parent_id) + $data ;
            }
        }
        // asort($data);
        return $data;
    }

    public static function selectTreeArray($parent = 0, $prefix = '',  $id = 0){
    $data = [];

    $categories = self::where('parent_id',$parent)->where('id', '!=', $id)->get();
    if(count($categories)>0){
    foreach($categories as $category){
    $data[$category->id] = $prefix.$category->name;
    $child = self::selectTreeArray($category->id, $prefix.'- ', $id);
    foreach($child as $k=>$v) {
    $data[$k] = $v;
    }
    }
     }
    return $data;
    }


}
