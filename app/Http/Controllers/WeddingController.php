<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wedding;
class WeddingController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'wedding';
        $page = Wedding::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Wedding', 'url'=>route('wedding.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Wedding::where('type','sub-page')->where('sub_type','rituals')->where('status',1)->get();
        return view(themeView().'wedding.index', compact('page', 'breads', 'subPages'));
    }
}
