<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Mantra;
use App\Model\Testimonial;

class MantraController extends Controller
{

    public function index(Request $request){

        $urlslug = $request->slug?:'mantra';
        $page = Mantra::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Mantra', 'url'=>route('mantra.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Mantra::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'mantra.index', compact('page', 'breads', 'subPages','textTestimonials','videoTestimonials'));
    }
    public function page(Request $request){

        $urlslug = $request->slug;
        $page = Mantra::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Mantras', 'url'=>route('mantra.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $mantras = Mantra::where('type','sub-page')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'mantra.page', compact('page', 'breads', 'mantras','textTestimonials','videoTestimonials'));
    }
}
