<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\NgSoftware;
use App\Model\DemoVideo;

class PagesController extends Controller
{
    public function astrology(Request $request){
      $page = NgSoftware::where('id', 2)->first();
      $data = [];
      if(!$page){
        return response()->json(["success"=>false, "data"=>$data], 402);
      }
      $data['title'] = $page->title;
      $data['slug'] = $page->slug;
      $data['image'] = $page->image?$page->image->image(100, 100):no_image();
      $data['short_description'] = $page->short_description;
      $data['description'] = $page->description;
      $data['features'] = [
        "heading"=>"Features Of Astrology",
        "list"=>[]
      ];
      foreach ($page->features as $key => $value) {
        $item['title'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        array_push($data['features']['list'], $item);
        $item = [];
      }
      $data['procedures'] = [
        "heading"=>"Procedures",
        "list"=>[]
      ];
      foreach ($page->procedures as $key => $value) {
        $item['title'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        $item['description'] = $value->description;
        array_push($data['procedures']['list'], $item);
        $item = [];
      }
      $data['packages'] = [
        "heading"=>"Packages",
        "list"=>[]
      ];
      foreach ($page->packages as $key => $value) {
        $item['heading'] = $value->heading;
        $item['price'] = $value->title;
        $item['list'] = [];
        foreach ($value->services as $key => $value) {
          array_push($item['list'], $value->title);
        }
        array_push($data['packages']['list'], $item);
        $item = [];
      }

      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function ngSoftware(Request $request){
      $page = NgSoftware::where('id', 1)->first();
      $data = [];
      if(!$page){
        return response()->json(["success"=>false, "data"=>$data], 402);
      }
      $videos = DemoVideo::where('status', 1)->where('page_id', $page->id)->orderBy('order', 'ASC')->get();
      $data['title'] = $page->title;
      $data['slug'] = $page->slug;
      $data['image'] = $page->image?$page->image->image(100, 100):no_image();
      $data['short_description'] = $page->short_description;
      $data['description'] = $page->description;
      $data['procedures'] = [
        "heading"=>"Procedures",
        "list"=>[]
      ];
      foreach ($page->procedures as $key => $value) {
        $item['title'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        $item['description'] = $value->description;
        array_push($data['procedures']['list'], $item);
        $item = [];
      }
      $data['features'] = [
        "heading"=>"Features Of Nakshatra Gold",
        "list"=>[]
      ];
      foreach ($page->features as $key => $value) {
        $item['title'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        array_push($data['features']['list'], $item);
        $item = [];
      }
      $data['videos'] = [
        "heading"=>"Reference Video",
        "list"=>[]
      ];
      foreach ($videos as $key => $value) {
        $item['title'] = $value->title;
        $item['type'] = 'youtube';
        $item['video_id'] = $value->video_id;
        $item['thumbnail'] = $value->thumbnail;
        $item['video_hash'] = $value->hash;
        array_push($data['videos']['list'], $item);
        $item = [];
      }
      $data['packages'] = [
        "heading"=>"Packages",
        "list"=>[]
      ];
      foreach ($page->packages as $key => $value) {
        $item['heading'] = $value->heading;
        $item['price'] = $value->title;
        $item['list'] = [];
        foreach ($value->services as $key => $value) {
          array_push($item['list'], $value->title);
        }
        array_push($data['packages']['list'], $item);
        $item = [];
      }

      return response()->json(["success"=>true, "data"=>$data], 200);
    }
}
