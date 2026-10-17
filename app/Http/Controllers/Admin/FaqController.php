<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TemplateFaq;
use App\Model\Media;
use App\Model\OnPage;
use App\CourseCategory;
use App\Model\{ProductCategory, Service};

class FaqController extends Controller
{
    private $module = 'faq';
    public function index(Request $request)
     {
       if(!adminCan('faq','view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>'FAQ Template']
       ];
       return view('admin.faq.list', compact('nav','breads'));
     }

     public function all(Request $request)
     {
        // $templateFaq = TemplateFaq::orderBy($request->sort_by,$request->sort_dir);
        $templateFaq = TemplateFaq::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

        if($question = $request->question) {
            $templateFaq->where('question', 'like', "%$question%");
        }
        if($request->has('type') && is_numeric($request->type)){
          $onPage = OnPage::where('table', 'template_faqs')->where('page', $request->type)->pluck('table_id')->toArray();
          $templateFaq->whereIn('id', $onPage);
        }

        if($updated_at = $request->updated_at) {
            $templateFaq->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $templateFaq->where('status', $status);
        }

        $templateFaqs = $templateFaq->paginate(10);

        $data = [
            'rows' => view('admin.faq.listrows',['results'=>$templateFaqs])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$templateFaqs])->render(),
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $id = $request->id;
        $nav = $this->module;
         $breads = [
             ['name'=>'Faq', 'url'=> route('admin.faq.index')],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $templateFaq = false;
         if($id) {
           $templateFaq = TemplateFaq::find($id);
          }
         $medias  = Media::get();
         return view('admin.faq.form', compact('nav', 'breads', 'templateFaq', 'medias'));
     }

     public function getCategory(Request $request){
        $type = $request->input('type');
        if($type == '1'){
          $categories = ProductCategory::where('status', 1)->where("type", 1)->orderBy("name", 'ASC')->get();
        }elseif($type == '2'){
          $categories = ProductCategory::where('status', 1)->where("type", 2)->orderBy("name", 'ASC')->get();
        }elseif($type == '3'){
          $categories = ProductCategory::where('status', 1)->where("type", 3)->orderBy("name", 'ASC')->get();
        }elseif($type == '4'){
          $categories = Service::orderBy("name", 'ASC')->get();
        }elseif($type == '5'){
          $categories = Service::orderBy("name", 'ASC')->get();
        }else{
          $categories = [];
        }
        return response()->json($categories);
     }

     public function submit(Request $request) {
         $id = $request->id;
         if(!$id && !adminCan('faq', 'add')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         if($id && !adminCan('faq', 'update')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         $this->validate($request, [
             'question' => 'required',
             'answer' => 'required',
             'type' => 'required|array',
         ],[
             'question.required' => 'The Question is Required',
             'answer.required' => 'The Answer is Required',
             'type.required' => 'The Type is Required',
             'type.array' => 'The Type is Required',

         ]);
         if(!in_array('0', $request->type)){
           $this->validate($request, [
               'category_id' => 'required|gt:0',
           ],[
               'category_id.required' => 'The Category is Required',
               'category_id.gt' => 'The Category is Required',
           ]);
         }
        $id = $request->id;
        if($id){
            $templateFaq = TemplateFaq::find($id);
        }else {
            $templateFaq = new TemplateFaq();
        }

         $templateFaq->question 		= $request->question;
         $templateFaq->answer 			= $request->answer;
         $templateFaq->order 			= $request->order?$request->order:0;
         $templateFaq->status 			= $request->status?$request->status:0;
         // $templateFaq->type 			= $request->type;
         $templateFaq->type 			= 0;
         $templateFaq->category_id 			= $request->category_id?$request->category_id:0;
         $templateFaq->overwrite 		= json_encode($request->overwrite ?  $request->overwrite : []);
         $templateFaq->save();
         OnPage::where('table', 'template_faqs')->where('table_id', $templateFaq->id)->delete();
         foreach ($request->type as $key => $value){
           $onPage = new OnPage();
           $onPage->table='template_faqs';
           $onPage->table_id=$templateFaq->id;
           $onPage->page=$value;
           $onPage->save();
         }
         return response()->json(['message'=>'FAQ template Saved Successfully', 'redirectto'=>route('admin.faq.edit', [$templateFaq->id])], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan('faq','delete')) return dd('No Permission');
        if($id = $request->id) {
            $templateFaq = TemplateFaq::find($id);
            $templateFaq->delete();
            OnPage::where('table', 'template_faqs')->where('table_id', $templateFaq->id)->delete();
            return response()->json(['message'=>'FAQ template Deleted successfully!'], 200);
        }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
      {
        if(!adminCan('faq','add')) return dd('No Permission');
    if($id = $request->id) {
    $item = TemplateFaq::find($id);
    $nitem = $item->replicate();
    $nitem->question = $item->question.' Cloned';
    $nitem->status = 0;
          $nitem->save();

          $onPage = OnPage::where('table', 'template_faqs')->where('table_id', $item->id)->get();
          foreach ($onPage as $index => $item){
            $onPage = new OnPage();
            $onPage->table='template_faqs';
            $onPage->table_id=$nitem->id;
            $onPage->page=$item->page;
            $onPage->save();
          }
    return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }

 }
