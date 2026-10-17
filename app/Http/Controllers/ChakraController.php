<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Chakra;

class ChakraController extends Controller
{
    public function index(Request $request){

        $urlslug = $request->slug?:'chakra';
        $page = Chakra::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        if($page->type=='page'){
            $breads = [
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Chakra', 'url'=>route('chakra.index')],
                ['name'=> $page->name, 'is_last'=>true]
            ];
        }
        $subPages = Chakra::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'chakra.index', compact('page', 'breads', 'subPages'));
    }
}
