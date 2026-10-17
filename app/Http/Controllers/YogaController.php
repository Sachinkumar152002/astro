<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Yoga;

class YogaController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'yoga';
        $page = Yoga::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Yoga Vidhi', 'url'=>route('yoga.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Yoga::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'yoga.index', compact('page', 'breads', 'subPages'));
    }
}
