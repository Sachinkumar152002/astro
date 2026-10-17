<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ColorTherapy;

class ColorTherapyController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'color-therapy';
        $page = ColorTherapy::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'ColorTherapy', 'url'=>route('color.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = ColorTherapy::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'color.index', compact('page', 'breads', 'subPages'));
    }
}
