<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CourseCategory;
use App\Model\Course;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use App\Model\{Testimonial};
use App\{CourseReview};

class CourseController extends Controller
{
  public function categories(Request $request){
    $categories = CourseCategory::where('status', 1)->orderBy('updated_at', 'DESC')->get();
    if(!$categories) return response()->json(['success'=>false, 'data'=>[], 'message'=>'not found'], 404);
    $categoriesData = [
      [
        'slug'=>'all',
        'name'=>'All',
        'image'=>no_image(),
      ]
    ];
    foreach ($categories as $key => $category) {
      $item = [
        'slug'=>$category->slug,
        'name'=>$category->name,
        'icon'=>$category->iconImage?$category->iconImage->image(50):no_image(),
        'image'=>$category->image?$category->image->image(100):no_image(),
      ];
      array_push($categoriesData, $item);
    }
     return response()->json([
       'success' => true,
        'data' => $categoriesData,
        'message' => 'Course Categories',
     ], 200);
  }
  public function types(Request $request){
    $typesData = [
      [
        'slug'=>'live',
        'name'=>'Live',
      ],
      [
        'slug'=>'online',
        'name'=>'Online',
      ],
      [
        'slug'=>'recorded',
        'name'=>'Recorded',
      ],
    ];
     return response()->json([
       'success' => true,
        'data' => $typesData,
        'message' => 'Courses Types',
     ], 200);
  }
  public function packageTypes(Request $request){
    $packageTypesData = [
      [
        'slug'=>'all',
        'name'=>'All',
      ],
      [
        'slug'=>'premium',
        'name'=>'Premium',
      ],
      [
        'slug'=>'gold',
        'name'=>'Gold',
      ],
      [
        'slug'=>'silver',
        'name'=>'Silver',
      ],
      [
        'slug'=>'free',
        'name'=>'Free',
      ],
    ];
     return response()->json([
       'success' => true,
        'data' => $packageTypesData,
        'message' => 'Courses Package Types',
     ], 200);
  }
  public function sortBy(Request $request){
    $sortByData = [
      [
        'slug'=>'whats_new',
        'name'=>"What's New",
      ],
      [
        'slug'=>'price_low_to_high',
        'name'=>'Price Low To High',
      ],
      [
        'slug'=>'price_high_to_low',
        'name'=>'Price High To Low',
      ],
    ];
     return response()->json([
       'success' => true,
        'data' => $sortByData,
        'message' => 'Course Sort By',
     ], 200);
  }

  public function list(Request $request){
    $category = $request->category?:'all';
    $type = $request->type?strtolower($request->type):'live';
    $packageType = $request->packageType?:'all';
    $sortBy = $request->sortBy?:'whats_new';
    $maxPrice = $minPrice = $priceFrom = $priceTo = 0;
    $page = $request->page?:1;
    $limit = $request->limit?:8;
    $courses = Course::where('status', 1);
    if($category!='all'){
      $category = CourseCategory::where('slug', $category)->whereStatus(1)->first();
      $category_id = $category?$category->id:0;
      $courses = $courses->where('course_category_id', $category_id);
    }
    // if($type!='all'){
      if($type=='live'){
        $type = 1;
      }elseif($type=='online'){
        $type = 3;
      }elseif($type=='recorded'){
        $type = 2;
      }
      $courses = $courses->where('type', $type);
    // }
    $coursesForPrices = $courses;
    $minPrice = $coursesForPrices->min('special_price') ?: 0;
    $maxPrice = $coursesForPrices->max('special_price') ?: 0;
    if($packageType!='all'){
      if($packageType=='premium'){
        $packageType = 1;
      }elseif($packageType=='gold'){
        $packageType = 2;
      }elseif($type=='silver'){
        $packageType = 3;
      }elseif($type=='free'){
        $packageType = 4;
      }
      $courses = $courses->where('purchase_type', $packageType);
    }

    if($request->has('priceFrom')){
      $courses = $courses->where('special_price', '>=', $request->priceFrom);
    }
    if($request->has('priceTo')){
      $courses = $courses->where('special_price', '<=', $request->priceTo);
    }
    if($sortBy == 'whats_new'){
      $courses = $courses->orderBy('created_at', 'DESC');
    }elseif($sortBy == 'price_high_to_low'){
      $courses = $courses->orderBy('sale_price', 'DESC');
    }elseif($sortBy == 'price_low_to_high'){
      $courses = $courses->orderBy('sale_price', 'ASC');
    }
    $count = $courses;
    $count = $count->count();
    $courses = $courses->paginate($limit);

    if(!$courses) return response()->json(['success'=>false, 'data'=>[], 'message'=>'not found'], 404);
    $coursesData = [];
    foreach ($courses as $key => $course) {
      $classes = [];
      if($type == 1){
        foreach($course->courseDays as $dayKey => $day){
          array_push($classes, substr($day->day,0,3));
        }
      }
      $item = [
        'id'=>$key+1,
        'slug'=>$course->slug,
        'name'=>$course->name,
        'image'=>$course->image?$course->image->image(100):no_image(),
        'base_price'=>$course->price,
        'sale_price'=>$course->special_price,
        'total_classes'=>$course->total_class,
        'classes'=>$type==1?$classes:null,
        'start_date'=>$type==1?date('d-m-Y', strtotime($course->start_date)):null,
        'start_time'=>$type==1?date('h:i A', strtotime($course->start_time)):null,
        'category'=>$course->category?[
          'name'=>$course->category->name,
          'slug'=>$course->category->slug,
          'image'=>$course->category->image?$course->category->image->image(50,50):no_image(),
        ]:null,
        'instructor'=>$course->instructor?[
          'name'=>$course->instructor->firstname.' '.$course->instructor->lastname,
          'slug'=>$course->instructor->slug,
          'image'=>$course->instructor->image?$course->instructor->image->image(50,50):no_image(),
        ]:[
          'name'=>'name',
          'slug'=>'slug',
          'image'=>no_image(),
        ],
        'discount'=>ceil((($course->price-$course->special_price)/$course->price)*100)
      ];
      array_push($coursesData, $item);
    }
     return response()->json([
       'success' => true,
        'data' => [
          'category'=>$request->category,
          'minPrice'=>$minPrice,
          'maxPrice'=>$maxPrice,
          'current_page' => $courses->currentPage(),
          'total' => $courses->total(),
          'first_page_url' => asset('/api/course/list'),
          'last_page_url' => $courses->url($courses->lastPage()),
          'prev_page_url' => $courses->previousPageUrl(),
          'next_page_url' => $courses->nextPageUrl(),
          'per_page' => $courses->perPage(),
          'courses'=>$coursesData,
        ],
        'message' => 'Courses'
     ], 200);
  }

  public function detail(Request $request){
  // types:
  // live 1
  // online 2
  // recorded 3
    $slug = $request->slug?:'';
    $course = Course::where('slug', $slug)->where('status', 1)->first();
    if(!$course){
      return response()->json([
          'errors' => "Course Not Found.",
          'status' => 402,
      ], 402);
    }
    $type = 'Live';
    if($course->type == 2){
      $type = 'Online';
    }elseif($course->type == 3){
      $type = 'Recorded';
    }
    $packageType = 'Premium';
    if($course->purchase_type == 2){
      $type = 'Gold';
    }elseif($course->purchase_type == 3){
      $type = 'Silver';
    }elseif($course->purchase_type == 4){
      $type = 'Free';
    }
    $specifications = [];
    $i = 0;
    foreach (courseSpecifications() as $key => $value) {
      if(isset($course->specifications[$i]) && $course->specifications[$i] && $course->specifications[$i]->text){
        $item = [
          'image'=>asset($key),
          'heading'=>$value,
          'text'=>$course->specifications[$i]->text,
        ];
        array_push($specifications, $item);
      }
      $i++;
    }
    $benifits = [
      'heading'=>'Who can benefit from enrolling?',
      'benifits'=>[],
    ];
    foreach ($course->benifits as $key => $value) {
      if($value->status){
      $item = [
        'image'=>$value->image?$value->image->image(100,100):no_image(),
        'title'=>$value->title,
        'description'=>$value->description,
      ];
      array_push($benifits['benifits'], $item);
      }
    }
    $modules = [];
    foreach ($course->modules as $moduleKey => $module){
      $item = [];
      if($module->status){
        $item['name'] = $module->name;
        $item['content'] = [];
        foreach ($module->content as $contenKey => $content){
          if($content->status){
            $contentItem = [];
            $contentItem['title'] = $content->title;
            $contentItem['description'] = $content->description;
            if($content->preview){
              $contentItem['preview'] = [];
              $contentItem['preview']['type'] = $content->preview->type;
              if($content->preview->type == 'youtube'){
                $contentItem['preview']['video_id'] = $content->preview->video_id;
                $contentItem['preview']['url'] = 'https://www.youtube.com/embed/'.$content->preview->video_id;
              }elseif($content->preview->type == 'vimeo'){
                $contentItem['preview']['video_id'] = $content->preview->video_id;
                $contentItem['preview']['video_hash'] = $content->preview->video_id;
                $contentItem['preview']['url'] = 'https://player.vimeo.com/video/'.$content->preview->video_id.'?h='.$content->preview->video_hash;;
              }
            }
            if($content->files){
              $contentItem['file'] = [];
              $contentItem['file']['url'] = asset($content->files->file);
            }
            array_push($item['content'], $contentItem);
          }
        }
        if(count($item['content'])){
          array_push($modules, $item);
        }
      }
    }
    $textTestimonials = Testimonial::where('status', 1)->where('pageType', 1)->orderBy('order', 'asc')->limit(8)->get();
    $joined_peoples = [
      'count' => $course->id+100,
      'images' => [],
    ];
    foreach ($textTestimonials as $key => $testiValue) {
      if($testiValue->image){
        array_push($joined_peoples['images'], $testiValue->image->image(50, 50));
      }
    }
    $previewUrl = '';
    $previewType = 'youtube';
    $videoId = '';
    $imageUrl = '';
    if($course->previewVideo && $course->previewVideo->title != 'vimeo'){
      $previewUrl = 'https://www.youtube.com/embed/'.$course->previewVideo->video_id;
      $videoId = '';
      $videoId = $course->previewVideo->video_id;
      $imageUrl = $course->previewVideo->image;
    }else{
      $previewUrl = $course->previewVideo ? vimeoVideo($course->previewVideo->video_id) : '';
      $previewUrl = $previewUrl?$previewUrl:'';
      $previewType = 'other';
      $videoId = $course->previewVideo ? $course->previewVideo->video_id : '';
    }
    $rating = CourseReview::where('course_id',$course->id)->where('status', 1)->orderBy('rating', 'DESC')->select('rating')->first();
    if($rating){
      $rating = floatval(sprintf('%.1f', $rating->rating));
    }else{
      $rating = 4.5;
    }
    $data = [
      'preview'=>[
        'type'=>$previewType,
        'url'=>$previewUrl,
        'videoId'=>$videoId,
        'imageUrl'=>$imageUrl
        ],
      'slug'=>$course->slug,
      'title'=>$course->name,
      'image'=>$course->image?$course->image->image(100,100):no_image(),
      'base_price'=>$course->price,
      'sale_price'=>$course->special_price,
      'google_rating'=>$rating,
      'youtube_subscriber'=>'17000',
      'facebook_likes'=>'23918',
      'joined_peoples'=> $joined_peoples,
      'category'=>$course->category?[
        'name'=>$course->category->name,
        'slug'=>$course->category->slug,
        'image'=>$course->category->image?$course->category->image->image(50,50):no_image(),
      ]:null,
      'instructor'=>$course->instructor?[
        'name'=>$course->instructor->firstname.' '.$course->instructor->lastname,
        'slug'=>$course->instructor->slug,
        'image'=>$course->instructor->image?$course->instructor->image->image(50,50):no_image(),
      ]:null,
      'type'=>$type,
      'packageType'=>$packageType,
      'short_description'=>[
        'heading'=>$course->name,
        'text'=>$course->short_description,
      ],
      'description'=>[
        'heading'=>$course->name,
        'text'=>$course->description,
      ],
      'learn'=>[
        'heading'=>'What you will learn?',
        'text'=>$course->ilearn,
      ],
      'specifications'=>$specifications,
      'learn'=>[
        'heading'=>'What you will learn?',
        'text'=>$course->ilearn,
      ],
      'benifits'=>$benifits,
      'modules'=>$modules,
    ];
     return response()->json([
       'success' => true,
        'data' => $data,
        'message' => 'Course '.$course->name,
     ], 200);
  }

  public function review(Request $request){
    $course = Course::where('slug', $request->slug)->first();
    if(!$course){
      return response()->json(['success'=>false, 'message'=>'course not found'], 402);
    }
    $data =[];
    foreach ($course->reviews as $key => $review){
      if($review->user){
        $item['user_name'] = $review->user->name;
        $item['user_image_url'] = $review->user->image ? $review->user->image->image(88,88) : '';
        $item['date'] = date('d-m-Y', strtotime($review->created_at));
        $item['review'] = $review->review;
        array_push($data, $item);
      }
    }
    return response()->json(['success'=>count($data) ? true : false, 'data'=>$data], 200);
  }

}
