<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Punchang;
use App\Model\PanchangApi;
use App\Model\Testimonial;
class PunchangController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'panchang';
        $page = Punchang::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Panchang', 'url'=>route('panchang.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $today = date('Y-m-d');
        $apisData = PanchangApi::where('date', $today)->orderBy('time', 'desc')->first();
        $apisData = $apisData ? json_decode($apisData->response, true)['response'] : [];

        $subPages = Punchang::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'panchang.index', compact('page', 'breads', 'subPages', 'apisData','textTestimonials','videoTestimonials'));
    }
    public function page(Request $request){

        $urlslug = $request->slug;
        $page = Punchang::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Panchangs', 'url'=>route('panchang.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $subPages = Punchang::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'panchang.page', compact('page', 'breads','textTestimonials','videoTestimonials','subPages'));
    }
}
