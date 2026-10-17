<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Instructor;
use App\Model\Media;
use App\Model\Language;
use App\Model\Service;
use App\Model\AstrologerLanguage;
use App\Model\AstrologerService;
use App\Model\AstrologerTimeTable;
use DB;
class InstructorController extends Controller
{

  private $module = 'instructor';
  private $moduleName = 'Instructor';
  private $moduleNameSingle = 'Instructor';

  public function index(Request $request)
   {
      if(!adminCan($this->module,'view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>$this->moduleName]
       ];
       $moduleName = $this->moduleName;
       $course =false;
       return view('admin.instructor.list', compact('nav','breads', 'course','moduleName'));
   }

   public function all(Request $request)
   {
       // Check if the admin has the 'view' permission for this module
       if (!adminCan($this->module, 'view')) {
           return response()->json(['message' => 'No Permission'], 403);
       }

       // Query to fetch instructors and paginate the results
       $results = Instructor::paginate(13); // Fetch with pagination (13 items per page)

       // Prepare the view for rows and pagination
       $data = [
           'rows' => view('admin.' . $this->module . '.listrows', [
               'results' => $results
           ])->render(),

           'pagination' => view('admin._partials.pagination', [
               'results' => $results
           ])->render(),
       ];

       // Return the data as a JSON response
       return response()->json($data, 200);
   }



   public function form(Request $request)
   {

      $id = $request->id;

      if($id && !adminCan($this->module,'update')) return dd('No Permission');
      if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

      $nav = $this->module;
      $moduleName = $this->moduleNameSingle;
      $breads = [
           ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
           ['name'=>($id ? 'Edit': 'Add')]
      ];
       $item = Instructor::findOrNew($id);
       $medias  = Media::where('type','instructor')->get();
       $countries = DB::table('master_countries')->select('id', 'name')->orderBy('name','asc')->get();
       $languages = Language::orderBy('position', 'asc')->get();
       $services = Service::orderBy('name', 'asc')->get();
       $course = false;
       $timeTable = $item->timetable?$item->timetable->groupBy('week_day')->toArray():[];
       foreach(weekDays() as $wd=>$dayName) {
           if(!isset($timeTable[$wd])) {
             $timeTable[$wd] = [[
                 'week_day'=> $wd,
                 'from_date' => '',
                 'to_date' => ''
             ]];
           }
       }

       return view('admin.'.$this->module.'.form', compact('nav', 'breads','course', 'item', 'medias', 'moduleName', 'countries', 'languages', 'services', 'timeTable'));
   }

   public function submit(Request $request, $id = null) {
     // Ensure correct permissions for either adding or updating
     if ($id && !adminCan($this->module, 'update')) {
         return response()->json(['message' => 'No Permission'], 403);
     }
     if (!$id && !adminCan($this->module, 'add')) {
         return response()->json(['message' => 'No Permission'], 403);
     }


     // Validate the incoming request data
     $this->validate($request, [
         'name'    => 'required|string|max:255',
         'service' => 'required|string|max:255',
     ]);
     $id = $request->id;

      $id = $request->id;
      if($id){
          $item = Instructor::find($id);
      }else {
          $item = new Instructor();
      }

     // Retrieve or create a new instance of the Instructor model
     // $item = Instructor::findOrNew($id);

     // Assign the request data to the model attributes
     $item->name = $request->input('name');
     $item->services = $request->input('service');
     $item->image_id =  $request->image_id?:0;// Image ID hardcoded, adjust as needed

     // Save the model
     $item->save();

     // Redirect to the index page after saving
     $redirectTo = route('admin.' . $this->module . '.index');

     // Return a success response in JSON format
     return response()->json([
         'message' => $this->moduleNameSingle . ' Saved Successfully',
         'redirectto' => $redirectTo
     ], 200);
 }


   public function delete(Request $request)
   {
     if($article_id = $request->id) {
       $article = Instructor::find($article_id);
       $article->delete();
        return response()->json(['message'=>'Testimonial Deleted successfully!'], 200);
     }
     return response()->json(['message'=>'Error in deleting data'], 401);
   }

   public function restore(Request $request)
   {
      if($id = $request->id) {
        $item = Astrologer::withTrashed()->find($id);
        $item->restore();
      }
      return redirect()->route('admin.'.$this->module.'.index');
   }

}
