<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Article;

class Category extends Model
{
    //

    public function parent(){
    return $this->hasOne('App\Model\Category', 'id', 'parent_id');
    }
    public function childrens(){
    return $this->hasMany('App\Model\Category', 'parent_id', 'id');
    }

    public static function selectTreeArray($parent = 0, $prefix = '', $lang = 'en', $id = 0){
    $data = ['0'=>'Select'];

    $categories = self::where('parent_id',$parent)->where('lang', $lang)->where('id', '!=', $id)->orderBy('name', 'asc')->get();
    if(count($categories)>0){
    foreach($categories as $category){
    $data[$category->id] = $prefix.$category->name;
    $child = self::selectTreeArray($category->id, $prefix.'- ', $lang, $id);
    foreach($child as $k=>$v) {
    $data[$k] = $v;
    }
    }
    }
    return $data;
    }



  public static function selectTreeArray2($parent = 0, $prefix = '', $id = 0){
    $data = [];
    $category_ids = Article::where('status', 1)->groupBy('category_id')->pluck('category_id')->toArray();
    if(count($category_ids)>0){
          foreach($category_ids as $category_id){
            if($cat = self::find($category_id)){
              if($cat->parent_id){
                $cat = self::find($cat->parent_id);
                if(!isset($data[$cat->id])){
                  $data[$cat->id] = $cat->name;
                }
              }else{
                if(!isset($data[$cat->id])){
                  $data[$cat->id] = $cat->name;
                }
              }
            }
          }
     }
    return $data;
  }

    public function articles(){
    return $this->hasMany('App\Model\Article', 'category_id', 'id');
    }

    public static function navigation($parent = 0, $lang = 'en'){
    $data = [];

    $categories = self::where('parent_id',$parent)
    ->where('show_in_navigation', 1)
    ->where('lang', $lang)
    ->where('status', 1)
    ->get();
    if(count($categories)>0){
    foreach($categories as $category){
    $child = self::navigation($category->id, $lang);
    $data[] = ['name'=>$category->name , 'link'=> "/blog/".$category->slug, 'children' => $child ? : false];
    }
     }
    return $data;
    }
}
