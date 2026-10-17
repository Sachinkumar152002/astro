<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Planet;
use App\Model\Testimonial;

class PlanetController extends Controller
{
    public function index(Request $request){

        $urlslug = 'planet';
        $page = Planet::where('page_type', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $planets = Planet::where('page_type','planet')->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();

        return view(themeView().'planet.index', compact('page', 'breads', 'planets','videoTestimonials','textTestimonials'));
    }

    public function page(Request $request){

        $urlslug = $request->slug;
        $page = Planet::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Planets', 'url'=>route('planet.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $pageTypes = ['planet'];
        if($page->slug=='transit' || $page->page_type=='transit') {
            $pageTypes = ['transit', 'grahan'];
        }
        elseif($page->slug=='retrograde'|| $page->page_type=='retrograde') {
            $pageTypes = ['retrograde'];
        }
        $planets = Planet::whereIn('page_type',$pageTypes)->where('status',1)->get();
        $textTestimonials = Testimonial::where('status', 1)->where('type',0)->get();
        $videoTestimonials = Testimonial::where('vStatus', 1)->where('type',1)->get();
        return view(themeView().'planet.page', compact('page', 'breads', 'planets','textTestimonials','videoTestimonials'));
    }

    public function subPage(Request $request){

        $urlslug = $request->slug;
        $type = $request->type;
        $page = Planet::where('slug', $urlslug)->where('page_type', $type)->where('status',1)->first();
        if(!$page) return abort(404);
        $parentPage = Planet::find($page->id);
        $breads = [
            ['name'=> 'Planet', 'url'=>route('planet.index')],
            ['name'=> $parentPage->name, 'url'=>route('planet.page', $parentPage->slug)],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $planets = Planet::where('page_type',$type)->where('status',1)->get();
        return view(themeView().'planet.page', compact('page', 'breads', 'planets'));
    }
}
