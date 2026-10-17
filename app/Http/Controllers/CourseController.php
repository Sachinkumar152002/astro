<?php

namespace App\Http\Controllers;

use App\Model\Coupon;
use App\Model\CourseModule;
use App\Model\StudentCourse;
use App\{Wishlist,CoursePreview,CourseBenifits, CourseCategory, CourseReview, Specification};
use Illuminate\Http\Request;
use App\Model\Course;
use App\Model\{Banner, Testimonial, VideoTestimonial, OnPage};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Model\TemplateFaq;
use \DB;
class CourseController extends Controller
{
  public function index()
{
  $today = date('Y-m-d');
  $banners = Banner::where('status', 1)->where('type','courses')->orderBy('order', 'asc')
  ->where(function($start_date) use ($today){
      $start_date->where('start_date', '<=', $today)->orWhereNull('start_date')->orWhere('start_date', '1970-01-01')->orWhere('start_date', '0000-00-00');
  })
  ->where(function($end_date) use ($today){
      $end_date->where('end_date', '>=', $today)->orWhereNull('end_date')->orWhere('end_date', '1970-01-01')->orWhere('end_date', '0000-00-00');
  })->take(10)->get();
  $categories = CourseCategory::where('status', 1)->orderBy('order', 'asc')->get();
  $onlineCourses = Course::where('status', 1)->whereIn('type', [3,2])->orderBy('order', 'asc')->groupBy('course_category_id')->limit(5)->get();
  $liveCourses = Course::where('status', 1)->where('type', 1)->orderBy('order', 'asc')->groupBy('course_category_id')->limit(5)->get();
  $onPage = OnPage::where('table', 'testimonials')->where('page', 1)->pluck('table_id')->toArray();
  $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
  $onPage = OnPage::where('table', 'video_testimonials')->where('page', 1)->pluck('table_id')->toArray();
  $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
  $onPage = OnPage::where('table', 'template_faqs')->where('page', 1)->pluck('table_id')->toArray();
  $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();

  return view('main.courses.index', [
      'nav' => 'courses',
      'categories' => $categories,
      'liveCourses' => $liveCourses,
      'onlineCourses' => $onlineCourses,
      'title' => 'Courses',
      'textTestimonials' => $textTestimonials,
      'videoTestimonials' => $videoTestimonials,
      'banners' => $banners,
      'faqs' => $faqs,
  ]);
}

public function detail(Request $request)
{
    $slug = $request->slug;
    $data['nav'] = 'courses';
    $data['hasWishlist'] = false;
    $course = Course::where('slug', $slug)->where('status', 1)->first();
    // return Specification::where('item_id', $course->id)->where('item_type', 'course')->get();
    // return $course->specifications;
    $liveCourses = Course::where('course_category_id', $course->course_category_id)->where('status', 1)->where('id','!=', $course->id)->where('type', 1)->orderBy('order', 'ASC')->get();
    $onlineCourses = Course::where('course_category_id', $course->course_category_id)->where('status', 1)->where('id','!=', $course->id)->where('type', 3)->orderBy('order', 'ASC')->get();
    $rating = CourseReview::where('course_id',$course->id)->where('status', 1)->orderBy('rating', 'DESC')->select('rating')->first();
    $stars = ['0'];
    $stars[5] = CourseReview::where('course_id',$course->id)->where('status', 1)->where('rating','>=', 5)->count();
    $stars[4] = CourseReview::where('course_id',$course->id)->where('status', 1)->where('rating','=', 4)->where('rating','>', 3)->count();
    $stars[3] = CourseReview::where('course_id',$course->id)->where('status', 1)->where('rating','=', 3)->where('rating','>', 2)->count();
    $stars[2] = CourseReview::where('course_id',$course->id)->where('status', 1)->where('rating','=', 2)->where('rating','>', 1)->count();
    $stars[1] = CourseReview::where('course_id',$course->id)->where('status', 1)->where('rating','<=', 1)->count();
    if($rating){
      $rating = $rating->rating;
    }else{
      $rating = 4.5;
    }
    $onPage = OnPage::where('table', 'testimonials')->where('page', 1)->pluck('table_id')->toArray();
    $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
    $onPage = OnPage::where('table', 'video_testimonials')->where('page', 1)->pluck('table_id')->toArray();
    $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
    $onPage = OnPage::where('table', 'template_faqs')->where('page', 1)->pluck('table_id')->toArray();
    $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->where('category_id', $course->course_category_id)->orderBy('order', 'asc')->limit(5)->get();
    return view('main.courses.detail', compact('data','course', 'liveCourses', 'onlineCourses', 'rating', 'stars', 'textTestimonials', 'videoTestimonials', 'faqs' ));
}

    public function checkCouponAvail(Request $request) {

        $couponcode = $request->coupon;
        $coupondisc = 0;
        $totalPrice = $request->total;
        $couponObj = Coupon::where('code', $couponcode)->where('start_date','<=',date('Y-m-d'))->where('end_date','>=',date('Y-m-d'))->where('status',1)->first();
        $request->session()->put('sst_coupon_code', $couponcode);
        if($couponObj){
            $discount  = $couponcode ? $totalPrice * ($couponObj->amount/100) : 0;

            $request->session()->put('sst_discount', $couponObj->amount);
            $request->session()->put('check_discount', $discount);
            Session::flash('success_message', "You will get huge discount of Rs. {$discount}/-");
            return Redirect::back();

            //return redirect()->back()->with('sucess_message','Valid Coupon code');
            //return response()->json(['message'=>'Valid Coupon code'], 200);
        }
        Session::flash('success_message', "Invalid Coupon Code");
        return Redirect::back();
        //return redirect()->back()->with('success_message','Invalid Coupon code');
        //return response()->json(['message'=>'Invalid Coupon code'], 401);

    }

    public function handleCourse(Request $request) {
      // dd(\Request::getRequestUri());
      $slug = $request->slug;
      if($course = Course::where('slug', $slug)->first()){
        return $this->detail($request);
      }elseif($slug){

        $sortBy = 'order_wise';
        $type =  '';
        $purchaseType = 0;
        $maxPrice = $minPrice = $priceFrom = $priceTo = 0;
        $courses = Course::where('courses.status', 1)->join('course_categories', 'course_categories.id', '=', 'courses.course_category_id');
        if($category = CourseCategory::where('slug', $slug)->whereStatus(1)->first()){
          // return $slug;
          $courses->where('courses.course_category_id', $category->id);
          // $minPrice = Course::where('course_category_id', $category->id)->where('status', 1)->min('price');
          // $maxPrice = Course::where('course_category_id', $category->id)->where('status', 1)->max('price');
        }

        if($type = $request->type){
          if($type=='live'){
            $type = 1;
          }elseif($type=='online'){
            $type = 3;
          }else{
            $type = 2;
          }
        }else{
          $type = 3;
        }
        // dd($request->purchaseType);
        $courses = $courses->where('courses.type', $type);
        if($request->purchaseType){
          if($request->purchaseType=='advance'){
            $purchaseType = 1;
          }elseif($request->purchaseType=='intermediate'){
            $purchaseType = 2;
          }elseif($request->purchaseType=='basic'){
            $purchaseType = 3;
          }elseif($request->purchaseType=='introduction'){
            $purchaseType = 4;
          }else{
            $purchaseType = 0;
          }
        }
        if($purchaseType){
          $courses = $courses->where('courses.purchase_type', $purchaseType);
        }
        $coursesForPrices = $courses;
        $minPrice = $coursesForPrices->min(DB::raw('IF(courses.special_from_date <= CURDATE() AND courses.special_to_date >= CURDATE(), courses.special_price, courses.price)')) ?: 0;
        $maxPrice = $coursesForPrices->max(DB::raw('IF(courses.special_from_date <= CURDATE() AND courses.special_to_date >= CURDATE(), courses.special_price, courses.price)')) ?: 0;
        if($priceFrom = $request->priceFrom){
          $courses = $courses->whereRaw('IF(courses.special_from_date <= CURDATE() AND courses.special_to_date >= CURDATE(), courses.special_price, courses.price) >= ?', [$request->priceFrom]);
        }
        if($priceTo = $request->priceTo){
          $courses = $courses->whereRaw('IF(courses.special_from_date <= CURDATE() AND courses.special_to_date >= CURDATE(), courses.special_price, courses.price) <= ?', [$request->priceTo]);
        }
        if($slug!='all'){
          $courses = $courses->orderBy('course_categories.order', 'ASC');
        }
        if($request->sortBy){
          if($request->sortBy == 'whats_new'){
            $sortBy = 'whats_new';
            $courses = $courses->orderBy('courses.created_at', 'DESC');
          }elseif($request->sortBy == 'price_high_to_low'){
            $sortBy = 'price_high_to_low';
            // $courses = $courses->orderBy('courses.special_price', 'DESC');
            $courses = $courses->orderByRaw('price_order DESC');
          }elseif($request->sortBy == 'order_wise'){
            $sortBy = 'order_wise';
            $courses = $courses->orderBy('courses.order', 'asc');
          }elseif($request->sortBy == 'price_low_to_high'){
            $sortBy = 'price_low_to_high';
            // $courses = $courses->orderBy('courses.special_price', 'ASC');
            $courses = $courses->orderByRaw('price_order ASC');
          }elseif($request->sortBy == 'a_to_z'){
            $sortBy = 'a_to_z';
            $courses = $courses->orderBy('courses.name', 'ASC');
          }
        }else{
          $courses = $courses->orderBy('courses.order', 'ASC');
        }

        // return $courses->toSql();
        $courses = $courses->selectRaw('courses.*, IF(courses.special_from_date IS NOT NULL AND courses.special_from_date <= CURDATE() AND courses.special_to_date >= CURDATE(), courses.special_price, courses.price) as price_order ');
        $count = $courses;
        $count = $count->count();
        $page = 0;
        $take = 12;
        $courses = $courses->paginate($take);
        $page =$page+1;
        if(!$priceFrom){
          $priceFrom = $minPrice;
        }
        if(!$priceTo){
          $priceTo = $maxPrice;
        }
        if(!$count) $count = 0;
        $categories = CourseCategory::whereStatus(1)->orderBy('order', 'ASC')->get();
        $url = \Request::getRequestUri();
        if($request->ajax()){
          $data = ['rows' => view('main.courses.category-list',['courses'=>$courses])->render(),
      ];
          return response()->json($data,200);
        }else{
          return view('main.courses.category', compact('courses', 'categories', 'slug', 'type', 'purchaseType','count', 'maxPrice', 'minPrice', 'priceFrom', 'priceTo', 'sortBy','page', 'url', 'take'));
        }
      }else{
          return redirect()->route('courses');
      }
    }

    public function reviewSubmit(Request $request) {
      $user_id = '';
      $user_type = '';
      if(auth()->guard("web")->check()){
        $user_id = auth()->guard('web')->user()->id;
        $user_type = 'user';
      }elseif(auth()->guard("astrologer")->check()){
        $user_id = auth()->guard('astrologer')->user()->id;
        $user_type = 'astrologer';
      }
      $course = Course::where('slug', $request->slug)->first();
      $review = new CourseReview();
      $review->rating = $request->rating;
      $review->review = $request->comments;
      $review->review_by = $user_id;
      $review->course_id = $course->id;
      $review->user_type = $user_type;
      $review->status = 0;
      if($review->save()) {
        Session::flash('message');
        return redirect()->back();
      }else{
        Session::flash('message');
        return redirect()->back();
      }
    }
}
