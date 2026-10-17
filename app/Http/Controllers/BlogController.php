<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\Category;
use App\Model\OnPage;
use App\Model\TemplateFaq;
use Session;
use App\Services\{Meta};

class BlogController extends Controller
{
     /**
     * Home Page
     */
    public function index(Request $request)
    {
        $pageName = 'Articles';
        $category = null;
        $parent_category = null;
        $nav="blog";
        $slug = '';
        $categories = Category::where('status', 1)->where('parent_id', 0)->orderBy('name', "ASC")->get();
        $nav = 'blog';
        $articles = Article::where('status', 1)->orderBy('order', 'asc')->orderBy('posted_on', 'desc')->paginate(12);

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_blog");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "blog_{$timestamp}";
          $body["action_source"] = "website";
          $body["event_source_url"] = $request->fullUrl();
          $body["user_data"]["client_ip_address"] = $request->ip();
          $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
          $body["user_data"]["fn"] = sha256("guest");
          if($user){
            $body["user_data"]["em"] = sha256($user->email);
            $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
            $body["user_data"]["fn"] = sha256($user->name);
            $user_type = "user";
          }
          // $body["custom_data"]["content_ids"] = [""];
          // $body["custom_data"]["content_name"] = "";
          // $body["custom_data"]["content_type"] = "";
          // $body["custom_data"]["currency"] = "";
          // $body["custom_data"]["value"] = "0.00";
          $metaService = new Meta();
          $metaService->sendEvent($body);
          Session::put("view_content_blog", $user_type);
        }
        $breads[]=["name"=>"Articles","url"=>route("blog.index")];
        return view(themeView().'blog.index', compact('nav','articles', 'breads', 'pageName', 'categories', 'slug', 'category', 'parent_category', 'nav'));
    }


     /**
     * Category Page
     */
    public function category(Request $request)
    {
      $slug = $request->slug;
      $nav="blog";
      $category = Category::where('status', 1)->where('slug', $slug)->first();
      $parent_category = null;
      if(!$category) return redirect()->route('blog.index', [], 301);
      if(Category::find($category->parent_id)){
        $parent_category = Category::find($category->parent_id);
      }
      $articles = Article::where('status', 1)->where('category_id', $category->id)->orderBy('order', 'asc')->orderBy('posted_on', 'desc')->paginate(12);
      if(count($articles)==0){
        $category_ids = Category::where('status', 1)->where('parent_id', $category->id)->pluck('id')->toArray();
        $articles = Article::where('status', 1)->whereIn('category_id', $category_ids)->orderBy('order', 'asc')->orderBy('posted_on', 'desc')->paginate(12);
      }
      $pageName = $category->name. ' Articles';
      // $categories = Category::where('status', 1)->orderBy('name', "ASC")->get();
      $categories = Category::where('status', 1)->where('parent_id', 0)->orderBy('name', "ASC")->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_blog");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "blog_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $category->id;
        $body["custom_data"]["content_name"] = "List - ".$category->name;
        $body["custom_data"]["content_type"] = "Blog";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_blog", $user_type);
      }
      $breads[]=["name"=>"Articles","url"=>route("blog.index")];
      $breads[]=["name"=>"Articles List","url"=>route("blog.category",[$category->slug])];
      return view(themeView().'blog.index', compact('nav','articles', 'breads', 'pageName', 'categories', 'slug', 'category', 'parent_category'));
    }

    /**
     * Blog post page
     */
    public function post(Request $request)
    {
        $post = Article::where('slug', $request->post)->where('status', 1)->first();
        if(!$post) return redirect()->route('blog.index', [], 301);
        $nav="blog";
        $category = Category::where('status', 1)->where('slug', $request->slug)->first();
        if(!$category) return redirect()->route('blog.index', [], 301);
        $relatedPost = Article::where('category_id', $category->id)->where('id', '!=', $post->id)->where('status', 1)->get();
        $onPage = OnPage::where('table', 'template_faqs')->where('page', 4)->pluck('table_id')->toArray();
        $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_blog_detail");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "blog_detail_{$timestamp}";
          $body["action_source"] = "website";
          $body["event_source_url"] = $request->fullUrl();
          $body["user_data"]["client_ip_address"] = $request->ip();
          $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
          $body["user_data"]["fn"] = sha256("guest");
          if($user){
            $body["user_data"]["em"] = sha256($user->email);
            $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
            $body["user_data"]["fn"] = sha256($user->name);
            $user_type = "user";
          }
          $body["custom_data"]["content_id"] = $post->id;
          $body["custom_data"]["content_name"] = "Detail - ".$post->title;
          $body["custom_data"]["content_type"] = "Blog";
          $metaService = new Meta();
          $metaService->sendEvent($body);
          Session::put("view_content_blog_detail", $user_type);
        }
        $breads[]=["name"=>"Articles","url"=>route("blog.index")];
        $breads[]=["name"=>"Articles List","url"=>route("blog.category",[$category->slug])];
        $breads[]=["name"=>"Articles Detail","url"=>route("blog.post",[$category->slug,$post->slug])];
        return view(themeView().'blog.post', compact('nav','post', 'breads', 'category', 'relatedPost', 'faqs'));
    }
}
