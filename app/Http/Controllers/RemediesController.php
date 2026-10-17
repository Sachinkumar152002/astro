<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Remedie;

class RemediesController extends Controller
{
    public function index(Request $request){

        $urlslug = 'remedies';
        $page = Remedie::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $subPages = Remedie::where('type','sub-page')->where('status',1)->get();
        return view(themeView().'remedies.index', compact('page', 'breads', 'subPages'));
    }

    public function page(Request $request){

        $urlslug = $request->slug;
        $page = Remedie::where('slug', $urlslug)->where('type', 'sub-page')->where('status',1)->first();
        if(!$page) return abort(404);
        $breads = [
            ['name'=> 'Remedies', 'url'=>route('remedies.index')],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $subPages = $page->childPages->where('status', 1);
        return view(themeView().'remedies.page', compact('page', 'breads', 'subPages'));
    }

    public function subPage(Request $request){

        $urlslug = $request->slug;
        $type = $request->type;
        $page = Remedie::where('slug', $urlslug)->where('type', $type)->where('status',1)->first();
        if(!$page) return abort(404);
        $parentPage = Remedie::where('slug', $type)->where('type', 'sub-page')->where('status',1)->first();
        $breads = [
            ['name'=> 'Remedies', 'url'=>route('remedies.index')],
            ['name'=> $parentPage->name, 'url'=>route('remedies.page', $parentPage->slug)],
            ['name'=> $page->name, 'is_last'=>true]
        ];
        $subPages = $parentPage->childPages->where('status', 1);
        return view(themeView().'remedies.page', compact('page', 'breads', 'subPages'));
    }
}
