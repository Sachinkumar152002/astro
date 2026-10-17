<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Meditation;

class MeditationController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'meditation';
        $page = Meditation::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Meditation', 'url'=>route('meditation.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Meditation::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'meditation.index', compact('page', 'breads', 'subPages'));
    }
}
