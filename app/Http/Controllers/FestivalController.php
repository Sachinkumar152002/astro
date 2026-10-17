<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Festival;
use App\Model\Testimonial;
class FestivalController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'festival';
        $page = Festival::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Festival', 'url'=>route('festival.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Festival::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'festival.index', compact('page', 'breads', 'subPages','textTestimonials','videoTestimonials'));
    }

    public function page(Request $request){

        $urlslug = $request->slug;
        $page = Festival::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Festivals', 'url'=>route('festival.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $festivals = Festival::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'festival.page', compact('page', 'breads', 'textTestimonials','videoTestimonials','festivals'));
    }
}
