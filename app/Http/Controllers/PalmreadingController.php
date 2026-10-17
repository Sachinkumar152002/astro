<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PalmReading;
class PalmreadingController extends Controller
{
    public function index(Request $request){
        $urlslug = $request->slug?:'palmreading';
        $page = PalmReading::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Palm Reading', 'url'=>route('palmreading.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }

        $subPages = PalmReading::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'palmreading.index', compact('page', 'breads', 'subPages'));
    }
}
