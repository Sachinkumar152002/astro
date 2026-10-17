<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Banner;
use App\Model\ZodiacSign;
use App\Model\Article;
use Carbon\Carbon;
use DB;
use App\Model\{Course, Testimonial, VideoTestimonial, TemplateFaq, ProductCategory, Astrologer, AstrologerReview, AstrologerAppointment, ContactEnquiry, Gift, Advertisement, OnPage, Cms};
use App\{User, CourseCategory, Product, ProductReview, Order, OrderItem};
use App\Model\Notification as NotificationModel;

class GeneralController extends Controller{

  public function countries(){
    $countries = DB::table('master_countries')->orderBy('name', 'asc')->get();
    $data = [
      // ['id'=>'0', 'name'=>'Select']
    ];
    foreach ($countries as $key => $value) {
      $item['id'] = $value->id;
      $item['name'] = $value->name;
      array_push($data, $item);
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function states(Request $request){
    $this->validate($request, [
      'country_id'=>'required',
    ]);
    $id = $request->country_id;
    $data = [
      ['id'=>'0', 'name'=>'Select']
    ];
    $states = DB::table('master_states')->where('country_id', $id)->orderBy('name', 'asc')->get();
    foreach ($states as $key => $value) {
      $item['id'] = $value->id;
      $item['name'] = $value->name;
      array_push($data, $item);
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function cities(Request $request){
    $this->validate($request, [
      'state_id'=>'required',
    ]);
    $id = $request->state_id;
    $data = [
      ['id'=>'0', 'name'=>'Select']
    ];
    $cities = DB::table('master_cities')->where('state_id', $id)->orderBy('name', 'asc')->get();
    foreach ($cities as $key => $value) {
      $item['id'] = $value->id;
      $item['name'] = $value->name;
      array_push($data, $item);
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function banners(Request $request){
    $type = $request->type ?: 'home';
    $width = $request->width ? : 600;
    $today = date('Y-m-d');
    $banners = Banner::where('status', 1)
    ->where('type', $type)
    ->where(function($query) use ($today){
      $query->where('start_date', '<=', $today)
      ->orWhereNull('start_date')
      ->orWhere('start_date', '1970-01-01')
      ->orWhere('start_date', '0000-00-00');
    })
    ->where(function($query) use ($today){
      $query->where('end_date', '>=', $today)
      ->orWhereNull('end_date')
      ->orWhere('end_date', '1970-01-01')
      ->orWhere('end_date', '0000-00-00');
    })
    ->orderBy('order', 'asc')
    ->get();
    $data = $banners->map(function($item) use($width){
      return [
        'title' => $item->title,
        'description' => $item->caption,
        'image' => $item->image->image(1200, 400),
      ];
    });
    return response()->json(['status' => 'success', 'data' => $data], 200);
  }

    public function zodiacSigns(Request $request)
    {
        $signs = ZodiacSign::where('status',1)->select('name', 'image_id', 'slug', 'sign_date_sd', 'sign_date_sm','sign_date_ed','sign_date_em','lucky_colors','lucky_gemstones','lucky_days','symbol','lucky_number','ruiling_planet','compatible_zodiac_signs','description','banner_image_id')->get();
        $signs = $signs->map(function($item){
            $item->icon = $item->image ? $item->image->image(200) : null;
            $item->banner = $item->bannerImage ? $item->bannerImage->image(600) : null;
            unset($item['image'], $item['image_id']);
            unset($item['banner_image_id'], $item['bannerImage']);
            return $item;
        });
        return $signs;
    }

    public function blogAll(Request $request) {
      $blogs = Article::where('status', 1)->get();
      $data = $blogs->map(function($item){
        return [
        'id' => $item->id,
        'image' => $item->image?$item->image->image(300):no_image(),
        'title' => $item->title,
        'sub_title' => $item->sub_title,
        'slug' => $item->slug,
        'description' => $item->description,
        'status' => $item->status,
        'order' => $item->order,
        'user_id' => $item->user_id,
        'type' => $item->type,
        'admin_id' => $item->admin_id,
        'youtube' => $item->youtube,
        'category_id' => $item->category_id,
        'trending' => $item->trending,
        'lang' => $item->lang,
        'created_at' => Carbon::parse($item->created_at)->format('d M Y'),
      ];
      });
      return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function textTestimonial(Request $request){
      $page = $request->page ? strtolower($request->page) : "";
      $page_value = [2, 3];
      if($page){
        if($page == 'astrologer'){
          $page_value = [2];
        }elseif($page == 'product'){
          $page_value = [3];
        }elseif($page == 'puja'){
          $page_value = [4];
        }elseif($page == 'service'){
          $page_value = [5];
        }
      }

      $onPage = OnPage::where('table', 'testimonials')
      ->whereIn('page', $page_value)
      ->pluck('table_id')
      ->toArray();

      $results = Testimonial::where('status', 1)
      ->whereIn('id', $onPage)
      ->orderBy('order', 'ASC')
      ->limit(8)
      ->get();

      $data = [
        'heading'=>'Testimonials',
        'sub_heading'=>'What our customers say about us',
        'data' => [],
      ];

      foreach ($results as $index => $item) {
        $item_data['title'] = $item->title;
        // $item_data['city'] = $item->city;
        // $item_data['rating'] = rand(4, 5);
        // $item_data['image_url'] = $item->image ? $item->image->image(50, 50) : '';
        $item_data['description'] = $item->description;
        $data['data'][] = $item_data;
      }
      return response()->json(['success'=>true, 'data'=>$data], 200);
    }

    public function videoTestimonial(Request $request){
      $page = $request->page ? strtolower($request->page) : "";
      $page_value = [2, 3];
      if($page){
        if($page == 'astrologer'){
          $page_value = [2];
        }elseif($page == 'product'){
          $page_value = [3];
        }elseif($page == 'puja'){
          $page_value = [4];
        }elseif($page == 'service'){
          $page_value = [5];
        }
      }

      $onPage = OnPage::where('table', 'video_testimonials')
      ->whereIn('page', $page_value)
      ->pluck('table_id')
      ->toArray();

      $results = VideoTestimonial::where('status', 1)
      ->whereIn('id', $onPage)
      ->orderBy('order', 'ASC')
      ->limit(8)
      ->get();

      $data = [
        'heading'=>'Video Testimonials',
        'sub_heading'=>'What our customers say about us',
        'data' => [],
      ];

      foreach ($results as $index => $item){
        $item_data['title'] = $item->title;
        // $item_data['city'] = $item->city;
        // $item_data['rating'] = rand(4,5);
        $item_data['video_id'] = $item->video_id;
        $item_data['thumbnail'] = $item->image;
        $item_data['video_url'] = 'https://www.youtube.com/embed/'.$item->video_id;
        // $item_data['description'] = $item->description;
        $data['data'][] = $item_data;
      }

      return response()->json(['success'=>true, 'data'=>$data], 200);
    }

    public function faqs(Request $request){
      $page = $request->page ? strtolower($request->page) : "";
      // $category_id = $request->category_id ?: 0;
      $page_value = [0, 1, 2, 3, 4, 5];
      if($page){
        if($page == 'product'){
          $page_value = [0, 1];
        }elseif($page == 'puja'){
          $page_value = [0, 2];
        }elseif($page == 'service'){
          $page_value = [0, 3];
        }elseif($page == 'astrologer'){
          $page_value = [0, 4];
        }elseif($page == 'ai'){
          $page_value = [0, 5];
        }
      }

      $onPage = OnPage::where('table', 'template_faqs')
      ->whereIn('page', $page_value)
      ->pluck('table_id')
      ->toArray();

      $results = TemplateFaq::whereIn('id', $onPage)
      ->where('status', 1)
      // ->where('category_id', $product->category_id)
      ->orderBy('order', 'ASC')
      ->limit(8)
      ->get();

      $data = [
        'heading'=>'Frequently Asked Questions',
        'sub_heading'=> "Get answers to all your queries with our FAQs.",
        'data' => [],
      ];

      foreach($results as $index => $item) {
        $item_data['question'] = $item->question;
        $item_data['answer'] = $item->answer;
        $data['data'][] = $item_data;
      }

      return response()->json(['success'=>true, 'data'=>$data], 200);
    }

    public function advertisements(Request $request){
      $request->validate([
        'page' => 'required'
      ]);
      $page = $request->page;
      $todayDate = date('Y-m-d');

      $result = Advertisement::where('status', 1)
      ->where('page', $page)
      ->where(function ($query) use($todayDate){
        $query->orWhereNull('start_date')
        ->orWhere('start_date', '<=', $todayDate);
      })
      ->where(function ($query) use($todayDate){
        $query->orWhereNull('end_date')
        ->orWhere('end_date', '>=', $todayDate);
      })
      ->orderBy('order', 'asc')
      ->get();

      $data["list"] = [];

      foreach($result as $key => $value){
        $image = $value->image;
        $item['title'] = $value->title ?: "";
        $item['content'] = $value->content ?: "";
        $item['image'] = $image ? $image->image() : no_image();
        $item['link'] = $value->link ?: "";
        $item['button_text'] = $value->button_text ?: "";
        $item['display_on'] = $value->display_on; // page, popup
        $item['duration'] = $value->display_on == "page" ? $value->duration : 0;
        $data['list'][] = $item;
      }
      return response()->json(['success'=>true, 'data'=>$data], 200);
    }

    public function canReview(Request $request){
      $request->validate([
        // "api_token" => "required",
        "page" => "required",
        "id" => "required|gt:0"
      ]);
      $api_token = $request->api_token ?: "";
      $user = User::where('api_token', $api_token)->first();
      if($user){
        $page = $request->page;
        $id = $request->id;
        $orderIds = [];
        $orders = [];
        if($page == 'shop'){
          $item = Product::find($id);
          if($item){
            $orderIds = OrderItem::where('user_id', $user->id)->where('user_type', 'user')->where('product_id', $item->id)->where('type', 'shop')->pluck('order_id')->toArray();
            $orders = Order::whereIn('id', $orderIds)->where('order_status', 'delivered')->get();
          }
        }elseif($page == 'astrologer'){
          $item = Astrologer::find($id);
          if($item){
            $orders = AstrologerAppointment::where('user_id', $user->id)->where('user_type', 'user')->where('astrologer_id', $item->id)->whereNotIn('user_status', ['pending', 'failed', 'cancel', 'booked'] )->get();
          }
        }
        if(count($orders) > 0){
          return response()->json(['success'=>true, 'message'=>'yes'], 200);
        }
      }
      return response()->json(['success'=>false, 'message'=>'no'],200);
    }

    public function submitReview(Request $request){
      $this->validate($request, [
        'page' => 'required',
        "id" => "required|gt:0",
        'rating' => 'required|gt:0',
        'review' => 'required',
      ]);
      $user = $request->user();
      $message = "Not authorized";
      $statusCode = 401;
      $success = false;
      if($user){
        $message = "Item not found";
        $statusCode = 404;
        $page = $request->page;
        $id = $request->id;
        if($page == 'shop'){
          $order_id = $request->order_id ?: 0;
          $item = Product::find($id);
          if($item){
            $message = "Review submit successfully";
            $statusCode = 200;
            $success = true;
            $review = new ProductReview();
            $review->order_id = $request->order_id;
            $review->product_id = $item->id;
            $review->review_by = $user->id;
            $review->rating = $request->rating;
            $review->review = $request->review;
            $review->status = 0;
            $review->save();
          }
        }elseif($page == 'astrologer'){
          $item = Astrologer::find($id);
          if($item){
            $appointment_id = $request->appointment_id ?: 0;
            $message = "Review submit successfully";
            $statusCode = 200;
            $success = true;
            $review = new AstrologerReview();
            $review->appointment_id = $request->appointment_id;
            $review->astrologer_id = $item->id;
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->review = $request->rating;
            $review->comment = $request->review;
            $review->status = 0;
            $review->rating = $request->rating;
            $review->save();
          }
        }
      }
      return response()->json(['success' => $success, 'message' => $message], $statusCode);
    }

    public function getInTouch(Request $request){
      $this->validate($request, [
          'name' => 'required',
          'email' => 'required|email',
          'phone' => 'required',
      ]);
      $enquiry = new ContactEnquiry();
      $enquiry->name = $request->name;
      $enquiry->phone = $request->phone;
      $enquiry->email = $request->email;
      $enquiry->message = $request->message?:'';
      $enquiry->type = $request->type;
      $enquiry->save();
      return response()->json(['success'=>true, 'message'=> 'Thankyou for contacting us!'],200);
    }

    public function gifts(Request $request){
      $results = Gift::where('status', 1)->get();
      $data = $results->map(function ($item){
        $image=$item->image;
        return [
          'id' => $item->id,
          'icon' => $image?$image->image(50, 50):no_image(),
          'name' => $item->name,
          'price' => $item->price,
        ];
      });
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function searchdata(Request $request){
      $request->validate([
        "name"=>"required"
      ]);
      $data = $request->name;
      $astrologers = Astrologer::where('display_name', 'LIKE', "%$data%")
      ->where('type', 'Astrologer')
      ->where('is_verified', 1)
      ->where('status', 1)
      ->where('is_delete_requested', 0)
      ->get()
      ->map(function($astrologer){
          $image=$astrologer->image;
        return [
            'id' => $astrologer->id,
            'name' => $astrologer->display_name,
            'slug' => $astrologer->slug,
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            // 'page' => "astrologer",
        ];
      });
      $aiAstrologers = Astrologer::where('display_name', 'LIKE', "%$data%")
      ->where('type', 'AI')
      ->where('is_verified', 1)
      ->where('status', 1)
      ->where('is_delete_requested', 0)
      ->get()
      ->map(function($astrologer){
          $image=$astrologer->image;
        return [
            'id' => $astrologer->id,
            'name' => $astrologer->display_name,
            'slug' => $astrologer->slug,
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            // 'page' => "ai",
        ];
      });
      $products = Product::where('title', 'LIKE', "%$data%")
      ->where('type', 1)
      ->where('status', 1)
      ->where('parent_id',0)
      ->get()
      ->map(function($product){
          $image=$product->image;
        return [
            'id' => $product->id,
            'name' => $product->title,
            'slug' => $product->slug,
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            // 'page' => "product",
        ];
      });

      $poojas = Product::where('title', 'LIKE', "%$data%")
      ->where('type', 2)
      ->where('status', 1)
      ->where('parent_id', 0)
      ->get()
      ->map(function($product){
          $image=$product->image;
        return [
            'id' => $product->id,
            'name' => $product->title,
            'slug' => $product->slug,
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            // 'page' => "pooja",
        ];
      });

      $services = Product::where('title', 'LIKE', "%$data%")
      ->where('type', 3)
      ->where('status', 1)
      ->where('parent_id',0)
      ->get()
      ->map(function($product){
          $image=$product->image;
        return [
            'id' => $product->id,
            'name' => $product->title,
            'slug' => $product->slug,
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            // 'page' => "service",
        ];
      });
      $result = [
        'astrologers' => $astrologers,
        'aiastrologers' => $aiAstrologers,
        'products' => $products,
        'poojas' => $poojas,
        'services' => $services
      ];
      return response()->json(["success"=>true,"data"=>$result]);
    }
    public function getNotification(Request $request){
      $user=$request->user();
      $user_type='user';
      $on=$request->on && $request->on=='top'?1:0;
      $for=[0, 1];
      if($user==null){
        return response()->json(['success'=>true,'message'=>'unauthorized'],402);
      }
      $userIdIn=[$user->id, 0];
      $query=NotificationModel::whereIn('user_id', $userIdIn)
      ->whereIn('for',$for)
      ->where('on',$on)
      ->where('created_at','>=',$user->created_at);
      $results=$query->orderBy('created_at','desc')
      ->paginate(10);
      $query->update(['view'=>1]);
      $data['list']=$results->map(function($item,$index){
        return [
          'message'=>$item->message,
          'created_at'=>$item->created_at
        ];
      });
      $data['count'] = $results->total();
      $data['per_page'] = $results->perPage();
      $data['has_more_pages'] = $results->hasMorePages();
      $data['current_page'] = $results->currentPage();
      $data['previous_page_url'] = $results->previousPageUrl();
      $data['next_page_url'] = $results->nextPageUrl();
      $data['last_page_url'] = $results->url($results->lastPage());
      return response()->json(['success'=>true,'message'=>'notifications list','data'=>$data],200);
    }

    public function termAndConditions(Request $request){
      $cmsPages = Cms::where('status', 1)
                      ->where('template', 'content')
                      ->get();

      $data = $cmsPages->map(function($cms){
        return [
          'id' => $cms->id,
          'name' => $cms->name,
          'slug' => $cms->slug,
          'template' => $cms->template,
          'content' => $cms->content,
          'meta_title' => $cms->meta_title,
          'meta_keywords' => $cms->meta_keywords,
          'meta_description' => $cms->meta_description,
          'created_at' => $cms->created_at ? Carbon::parse($cms->created_at)->format('Y-m-d H:i:s') : null,
          'updated_at' => $cms->updated_at ? Carbon::parse($cms->updated_at)->format('Y-m-d H:i:s') : null,
        ];
      });

      return response()->json([
        'success' => true,
        'data' => $data
      ], 200);
    }

}
