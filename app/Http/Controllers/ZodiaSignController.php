<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Cms;
use App\Model\ZodiacSign;
use App\Model\SubPage;

class ZodiaSignController extends Controller
{
    public function index(Request $request){

        $urlslug = 'zodiac-signs';
        $page = Cms::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $signs = ZodiacSign::where('status',1)->get();
        return view(themeView().'zodiac-signs.index', compact('page', 'breads', 'signs'));
    }

    public function signOrPage(Request $request){
        $pageArrary = ['sunsigns','moonsigns','compatibility'];
        $slug = $request->slug;
        if(in_array($slug, $pageArrary)){
            $page = Cms::where('slug', $slug)->where('status',1)->first();
            if(!$page) abort(404);
            $breads = [
                ['name'=> 'Zodiac Signs', 'url'=>route('zodiac-signs.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
            return view(themeView().'zodiac-signs.page', compact('page', 'breads'));
        }
        $page = ZodiacSign::where('slug', $slug)->where('status',1)->first();

        if(!$page) abort(404);
        $breads = [
            ['name'=> 'Zodiac Signs', 'url'=>route('zodiac-signs.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $signs = ZodiacSign::where('status',1)->get();
        $signsArray = $signs->pluck('name', 'id');
        return view(themeView().'zodiac-signs.sign', compact('page', 'breads', 'signs', 'signsArray'));

    }

    public function signPage(Request $request) {
        $page = SubPage::where([
            'module_key' =>  'zodiac-signs',
            'parent_slug' => $request->parent_page,
            'slug' => $request->slug
        ])->where('status',1)->first();

        $sign = ZodiacSign::where('slug', $request->parent_page)->where('status',1)->first();
        $breads = [
            ['name'=> 'Zodiac Signs', 'url'=>route('zodiac-signs.index')],
            ['name'=> $sign->name, 'url'=>route('zodiac-signs.sign', $sign->slug)],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        if(!$page) abort(404);
        return view(themeView().'zodiac-signs.signpage', compact('page', 'breads', 'sign'));
    }

}
