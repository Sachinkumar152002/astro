<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\VastuProcedure;
use App\Model\Vastu;

class VastuController extends Controller
{
    public function detail(Request $request){
      $page = Vastu::first();
      $data = [];
      if(!$page){
        return response()->json(["success"=>false, "data"=>$data], 402);
      }
      $services = Vastu::where('type','sub-page')->where('status',1)->get();
      $procedures = VastuProcedure::where('type','vastu')->where('status',1)->get();
      $data['name'] = $page->name;
      $data['slug'] = $page->slug;
      $data['image'] = $page->image?$page->image->image(100, 100):no_image();
      $data['short_description'] = $page->short_description;
      $data['description'] = $page->description;
      $data['why_choose_us'] = $page->why_choose_us;
      $data['benifits'] = $page->benifits;
      $data['services'] = [
        "heading"=>"Vastu Services Offered by Us",
        "list"=>[]
      ];
      foreach ($services as $key => $value) {
        $item['name'] = $value->name;
        $item['slug'] = $value->slug;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        $item['short_description'] = $value->short_description;
        array_push($data['services']['list'], $item);
        $item = [];
      }
      $data['procedures'] = [
        "heading"=>"Procedures",
        "list"=>[]
      ];
      foreach ($procedures as $key => $value) {
        $item['name'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        $item['description'] = $value->description;
        array_push($data['procedures']['list'], $item);
        $item = [];
      }
      $data['packages'] = [
        "heading"=>"Packages",
        "list"=>[]
      ];
      foreach ($page->packageServices as $key => $value) {
        $item['heading'] = $value->heading;
        $item['price'] = $value->title;
        $item['list'] = [];
        foreach ($value->services as $key => $value) {
          array_push($item['list'], $value->name);
        }
        array_push($data['packages']['list'], $item);
        $item = [];
      }

      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function serviceDetail(Request $request){
      $this->validate($request, [
        'slug'=>'required'
      ]);
      $data = [];
      $page = Vastu::where('slug', $request->slug)->where('status',1)->first();
      if(!$page){
        return response()->json(["success"=>false, "data"=>$data], 402);
      }
      $services = Vastu::where('type','sub-page')->where('status',1)->where('id','!=' , $page->id)->orderBy('id', 'DESC')->get();
      $procedures = VastuProcedure::where('type','vastu')->where('show_on_services', 1)->where('status',1)->get();
      $data['name'] = $page->name;
      $data['slug'] = $page->slug;
      $data['services'] = [
        "heading"=>"Vastu Services",
        "list"=>[]
      ];
      foreach ($services as $key => $value) {
        $item['name'] = $value->name;
        $item['slug'] = $value->slug;
        // $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        // $item['short_description'] = $value->short_description;
        array_push($data['services']['list'], $item);
        $item = [];
      }
      $data['image'] = $page->image?$page->image->image(100, 100):no_image();
      // $data['short_description'] = $page->short_description;
      $data['description'] = $page->description;
      $data['procedures'] = [
        "heading"=>"Procedures",
        "list"=>[]
      ];
      $data['packages'] = [
        "heading"=>"Packages",
        "list"=>[]
      ];
      foreach ($page->packageServices as $key => $value) {
        $item['heading'] = $value->heading;
        $item['price'] = $value->title;
        $item['list'] = [];
        foreach ($value->services as $key => $value) {
          array_push($item['list'], $value->name);
        }
        array_push($data['packages']['list'], $item);
        $item = [];
      }
      foreach ($procedures as $key => $value) {
        $item['name'] = $value->title;
        $item['image'] = $value->image?$value->image->image(100, 100):no_image();
        $item['description'] = $value->description;
        array_push($data['procedures']['list'], $item);
        $item = [];
      }
      // $data['why_choose_us'] = $page->why_choose_us;
      // $data['benifits'] = $page->benifits;

      return response()->json(["success"=>true, "data"=>$data], 200);
    }
}
