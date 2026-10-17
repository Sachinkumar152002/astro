<?php

namespace App\Http\Controllers\Api;

use App\Model\{Category,Article};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function categories(Request $request){
    $results=Category::select('categories.*')
    ->join('articles', 'articles.category_id', '=', 'categories.id')
    ->where('categories.status',1)
    ->where('articles.status',1)
    ->groupBy('categories.id')
    ->orderBy('name', 'asc')
    ->get();
    $data=$results->map(function($item){
      return [
        'id' => $item->id,
        'slug' => $item->slug,
        'name' => $item->name
      ];
    });
     return response()->json([
       'success'=>true,
        'data'=>$data,
     ], 200);
  }

  public function list(Request $request){
    $categoryId=$request->categoryId?:0;
    $exceptId=$request->exceptId?:0;
    $limit=$request->limit?:9;
    $page=$request->page?:1;
    $results=Article::where('status',1)
    ->where('id',"!=",$exceptId);
    if($categoryId){
      $results->where('category_id',$categoryId);
    }
    $results=$results->orderBy('created_at','desc')
    ->paginate($limit);
    $data=$results->map(function($item){
      $image=$item->image;
      $category=$item->category;
      return [
        'id'=>$item->id,
        'slug'=>$item->slug,
        'title'=>$item->title,
        'category'=>$category?$category->name:'',
        'subTitle'=>$item->sub_title,
        'image'=>$image?$image->image(1200,400):no_image(),
        'postedOn'=>date('d-m-Y', strtotime($item->posted_on)),
      ];
    });
     return response()->json([
       'success'=>true,
        'data'=>[
          'categoryId'=>$categoryId,
          'current_page' => $results->currentPage(),
          'total' => $results->total(),
          'first_page_url' => url('api/blog/list'),
          'last_page_url' => $results->url($results->lastPage()),
          'prev_page_url' => $results->previousPageUrl(),
          'next_page_url' => $results->nextPageUrl(),
          'per_page' => $results->perPage(),
          'list'=>$data,
        ],
     ], 200);
  }

  public function detail(Request $request){
    $id=$request->id;
    $result=Article::where('id',$id)
    ->where('status',1)
    ->first();
    $data["id"]=$result->id;
    $data["slug"]=$result->slug;
    $image=$result->image;
    $data["image"]=$image?$image->image(1200,400):no_image();
    $data["title"]=$result->title;
    $data["subTitle"]=$result->sub_title;
    $data["description"]=$result->description;
    $data["postedOn"]=date('d-m-Y', strtotime($result->posted_on));
    $category=$result->category;
    $data["category"]=[
      "id"=>$category->id,
      "slug"=>$category->slug,
      "name"=>$category->name
    ];
    return response()->json([
     'success' => true,
      'data' => $data,
   ], 200);
  }

}
