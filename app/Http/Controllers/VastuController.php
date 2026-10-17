<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Vastu;
use App\Model\Testimonial;
use App\Model\TemplateFaq;
use App\Model\EnquiryVastu;
use App\Model\VastuProcedure;
use App\Model\Article;
use App\Model\Category;
use App\Model\VideoTestimonial;
use App\Model\OnPage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnquiryNotification;
use File;
class VastuController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'vastu';
        $page = Vastu::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Vastu', 'url'=>route('vastu.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $services = Vastu::where('type','sub-page')->where('status',1)->get();
        // $services = Vastu::where('type','sub-page')->where('show_on_detail', 1)->where('status',1)->get();
        $procedures = VastuProcedure::where('type','vastu')->where('status',1)->get();
        $onPage = OnPage::where('table', 'template_faqs')->where('page', 2)->pluck('table_id')->toArray();
        $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();
        $onPage = OnPage::where('table', 'testimonials')->where('page', 4)->pluck('table_id')->toArray();
        $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
        $onPage = OnPage::where('table', 'video_testimonials')->where('page', 4)->pluck('table_id')->toArray();
        $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
        return view(themeView().'vastu.index', compact('page', 'breads', 'services', 'faqs','procedures', 'textTestimonials', 'videoTestimonials'));
    }

    public function page(Request $request){

        $slug = $request->slug;
        $page = Vastu::where('slug', $slug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Vastus Services', 'url'=>route('vastu.vastuservice')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $services = Vastu::where('type','sub-page')->where('status',1)->where('id','!=' , $page->id)->orderBy('id', 'DESC')->get();
        $procedures = VastuProcedure::where('type','vastu')->where('show_on_services', 1)->where('status',1)->get();
        return view(themeView().'vastu.page', compact('page', 'breads', 'services', 'procedures'));
    }

    public function vastuService(Request $request){
      $services = Vastu::where('type','sub-page')->where('status',1)->get();
      if(!$services) return abort(404);
        $breads = [
            ['name'=> 'Vastu Services', 'is_last'=>true]
        ];
      $page = '';
      return view(themeView().'vastuservice.index', compact('services', 'breads', 'page'));
    }

    public function submitEnquiry(Request $request){
      $validdation = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        // 'country' => 'required|numeric',
        // 'states' => 'required|numeric',
        // 'cities' => 'required|numeric',
        // 'pincode' => 'required|numeric',
        // 'services' => 'required|numeric',
        // 'service_type' => 'required',
        // 'file' => 'required',
        // 'length' => 'required',
        // 'breadth' => 'required',
    ];
      $request->validate($validdation);
      // dd($request->all());
      $user_id = 0;
      $user_type = '';
      if(auth()->guard("web")->check()){
        $user_id = auth()->guard('web')->user()->id;
        $user_type = 'user';
      }elseif(auth()->guard("astrologer")->check()){
        $user_id = auth()->guard('astrologer')->user()->id;
        $user_type = 'astrologer';
      }
      $country = \DB::table('master_countries')->where('id', $request->country)->first();
      $state = \DB::table('master_states')->where('id', $request->states)->first();
      $city = \DB::table('master_cities')->where('id', $request->cities)->first();
      $service = Vastu::find($request->services);
      $fileName = '';
      if($request->hasFile('file')){
        $filePath = public_path('enquiry');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }
        $filePath = public_path('enquiry/vastu');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }
        $time = time();
        $fileName = $time.'.'.$request->file->getClientOriginalExtension();
        $request->file->move($filePath, $fileName);
      }
      $enquiry = new EnquiryVastu();
      $enquiry->user_id = $user_id;
      $enquiry->user_type = $user_type;
      $enquiry->name = $request->name;
      $enquiry->email = $request->email;
      $enquiry->phone = $request->phone;
      $enquiry->country = $country && $country->name ? $country->name : $request->country;
      $enquiry->state = $state && $state->name ? $state->name : $request->states;
      $enquiry->city = $city && $city->name ? $city->name : $request->cities;
      $enquiry->pincode = $request->pincode?:'';
      $enquiry->service_id = $service ? $service->id : $request->services;
      $enquiry->service_name = $service ? $service->name : $request->services;
      $enquiry->service_type = $request->service_type?:'';
      $enquiry->file = $fileName?'/enquiry/vastu/'.$fileName:'';
      $enquiry->length = $request->length;
      $enquiry->breadth = $request->breadth;
      $enquiry->message = $request->message;
      $enquiry->save();
      Notification::route('mail', [
        $enquiry->email => $enquiry->name, //user
        config("vrdcms.admin_email") => 'Customer Care', //admin
      ])->notify(new EnquiryNotification($enquiry, 'vastu'));

      $fields['campaign type'] = 'Vastu Enquiry';
      $fields['name'] = $enquiry->name;
      $fields['email'] = $enquiry->email;
      $fields['phone'] = $enquiry->phone;
      $fields['city'] = $enquiry->location;
      if($enquiry->file){
        $fields['description'] = asset($enquiry->file);
      }
      $body['fields'] = $fields;
      sendDataToCrm($body);

      return response()->json([
          'status' => 'success',
          'message' => 'Thank You For Contacting Us.'
      ]);
    }
}
