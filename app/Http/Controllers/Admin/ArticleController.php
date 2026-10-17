<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\Media;
use App\Model\Category;
use App\Model\SlideShowArticle;
use Auth;

class ArticleController extends Controller
{

    private $module = 'article';
    private $moduleName = 'Posts';
    private $moduleNameSingle = 'Post';

    public function index(Request $request)
     {
        if(!adminCan('blog','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $lang = $request->lang;
         $moduleName = $this->moduleName;
         return view('admin.articles.list', compact('nav','breads', 'lang', 'moduleName'));
     }

     public function all(Request $request)
     {
          $lang = $request->lang;
          $article = Article::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          $article->where('lang', $lang);

          if($title = $request->name) {
              $article->where('title', 'like', "%$title%");
          }
          if(isset($request->status) && is_numeric($request->status)) {
              $status = $request->status;
              $article->where('status', $status);
          }
          if(isset($request->type) && is_numeric($request->type)) {
            $type = $request->type;
            $article->where('type', $type);
        }

          if($author = $request->author) {
                $article->where(function($q) use($author) {
                    $q->whereHas('user', function($sq)use($author){
                        $sq->where('name', 'like', "%$author%");
                    })
                    ->orWhereHas('admin', function($sq)use($author){
                        $sq->where('name', 'like', "%$author%");
                    });
                });
           }

          if($updated_at = $request->updated_at) {
              $article->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }

           $article = $article->paginate(10);

           $data = [
               'rows' => view('admin.articles.listrows',['articles'=>$article, 'lang'=>$lang])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$article])->render(),
           ];
           return response()->json($data, 200);
     }

    //  public function add(Request $request) {

    //     $nav = 'article';
    //     $lang = $request->lang;
    //     $breads = [
    //         ['name'=>'Articles', 'url'=> route('admin.article.index', $lang)],
    //         ['name'=> 'Add new article']
    //     ];
    //     return view('admin.articles.add', compact('nav', 'breads', 'lang'));
    //  }

     public function form(Request $request)
     {
        $id = $request->id;
        $nav = 'article';

         $lang = $request->lang;
         $moduleName = $this->moduleNameSingle;
         $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.article.index', $lang)],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $article = false;

         if($id) { $article = Article::find($id); }
         $medias  = Media::where('type','article')->get();
         $categories = Category::selectTreeArray(0, '', $lang);
         // $categories[0] = 'Select';
         // ksort($categories);


         return view('admin.articles.form', compact('nav', 'breads', 'article', 'medias', 'categories', 'lang', 'moduleName'));
     }

     public function save(Request $request) {
       if(!adminCan('blog', 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
        $this->validate($request, [
            'title' => 'required|max:255|unique:articles,title',
            // 'sub_title' => 'required|min:10|max:255',
            'image_id' => 'required|numeric|min:1',
            'type' => 'required|numeric',
            'posted_on' => 'required|date',
        ], [
          // 'sub_title.required'=>'Sub title is required.',
          'image_id.required'=>'Image is required.',
          'image_id.numeric'=>'Image is required.',
          'image_id.min'=>'Image is required.',
        ]);
        $lang = $request->lang;
        $article = new Article();
        $article->title = $request->title;
        $article->type = $request->type;
        $article->slug 	= str_slug($request->title);
        $article->lang 	= $request->lang;
        $article->admin_id = Auth::guard('admin')->user()->id;
        $article->save();

        return response()->json(['message'=>'Article Saved Successfully', 'redirectto'=>route('admin.article.edit', [$lang, $article->id])], 200);
    }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('blog', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('blog', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
         $this->validate($request, [
             'title' => 'required|max:255|unique:articles,title,'.$id.',id',
             // 'sub_title' => 'required|min:10|max:255',
             'image_id' => 'required|numeric|min:1',
             // 'short_description' => 'required',
             'description' => 'required',
             'posted_on' => 'required',
             'type' => 'required|numeric',
             'category_id' => 'required|numeric',
         ], [
           // 'sub_title.required'=>'Sub title is required.',
           'image_id.required'=>'Image is required.',
           'image_id.numeric'=>'Image is required.',
           'image_id.min'=>'Image is required.',
         ]);
         $lang = $request->lang;
         if($id){
             $article = Article::find($id);
         }else {
             $article = new Article();
         }
        // $article = Article::find($id);
        $article->image_id 		= $request->image_id?:0;
        $article->type          = $request->type;
        $article->title 		= $request->title;

        $article->sub_title 	= $request->sub_title;
        $article->slug 	        = $request->slug ? str_slug($request->slug) : str_slug($request->title);
        $article->description 		= $request->short_description?:"";
        $article->description 		= $request->description;
        $article->status 		    = $request->status;
        $article->order 		    = $request->order?:0;
        $article->meta_title       = $request->meta_title;
        $article->meta_keywords    = $request->meta_keywords;
        $article->meta_description = $request->meta_description;
        $article->lang = $request->lang;
        $article->trending = $request->trending;
        $article->category_id = $request->category_id;
        $article->posted_on 		= $request->posted_on ? : date('Y-m-d');
        $article->admin_id = Auth::guard('admin')->user()->id;
        if($article->type==2){
            $article->youtube = $request->youtube;
        }
        $article->save();
        if($article->type==1){
            $slides = json_decode($request->slides);
            SlideShowArticle::where('article_id', $article->id)->delete();
            foreach($slides as $slide) {
                $slide = (array) $slide;
                $slide['article_id'] = $article->id;
                SlideShowArticle::create($slide);
            }
        }

        return response()->json(['message'=>'Article Saved Successfully', 'redirectto'=>route('admin.article.edit', [$lang, $article->id])], 200);
     }

    public function delete(Request $request)
     {
        if(!adminCan('blog','delete')) return dd('No Permission');
       if($article_id = $request->id) {
        $lang = $request->lang;
         $article = Article::find($article_id);
         $article->delete();
          return response()->json(['message'=>'Article Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }
     public function duplicate(Request $request)
      {
         if(!adminCan('blog','add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Article::find($id);
    $nitem = $item->replicate();
    $nitem->title = $item->title.' Cloned';
    $nitem->slug = $item->slug.'-'.$item->id;
          $nitem->save();
    return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }
 }
