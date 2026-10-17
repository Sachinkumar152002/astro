<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Badge;
use Auth;

class BadgeController extends Controller
{
    public $module = 'badge';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'badge';
        $breads = [
            ['name'=>'Badges']
        ];
        return view('admin.badges.list', compact('nav','breads'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $badges = Badge::orderBy($request->sort_by ?: 'id', $request->sort_dir ?: 'desc');

        if($name = $request->name) {
            $badges->where('name', 'like', "%$name%");
        }

        if(isset($request->status) && is_numeric($request->status)) {
            $badges->where('status', $request->status);
        }

        if($created_at = $request->created_at) {
            $badges->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ?", [$created_at]);
        }

        if($updated_at = $request->updated_at) {
            $badges->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ?", [$updated_at]);
        }

        $badges = $badges->paginate(10);

        $data = [
            'rows' => view('admin.badges.listrows', ['badges'=>$badges])->render(),
            'pagination' => view('admin._partials.pagination', ['results'=>$badges])->render(),
        ];
        return response()->json($data, 200);
    }

    public function form(Request $request)
    {
        $id = $request->id;

        if($id && !adminCan($this->module, 'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module, 'add')) return dd('No Permission');

        $nav = 'badge';
        $breads = [
            ['name'=>'Badges', 'url'=> route('admin.badge.index')],
            ['name'=>($id ? 'Edit': 'Add')]
        ];
        $badge = false;
        if($id) {
            $badge = Badge::find($id);
        }
        $_status = statusArray();

        return view('admin.badges.form', compact('nav', 'breads', 'badge', '_status'));
    }

    public function submit(Request $request)
    {
        $id = $request->id ?? 0;

        if(!$id && !adminCan($this->module, 'add')){
            return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($this->module, 'update')){
            return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

        $this->validate($request, [
            'name' => 'required|max:255|unique:badges,name'.($id ? ','.$id.',id' : ''),
            'status' => 'required|in:0,1'
        ]);

        if($id) {
            $badge = Badge::find($id);
            // $badge->updated_by = Auth::guard('admin')->user()->id;
        } else {
            $badge = new Badge();
            // $badge->created_by = Auth::guard('admin')->user()->id;
        }

        $badge->name = $request->name;
        $badge->status = $request->status ?? 1;
        $badge->save();

        return response()->json(['message'=>'Successfully saved!', 'redirectTo' => route('admin.badge.index')], 200);
    }

    public function delete(Request $request)
    {
        if(!adminCan($this->module, 'delete')) return response()->json(['message'=>'No Permission'], 401);

        if($id = $request->id) {
            $badge = Badge::find($id);
            if($badge) {
                $badge->delete();
                return response()->json(['message'=>'Deleted successfully!'], 200);
            }
        }
        return response()->json(['message'=>'Error in delete'], 401);
    }

    public function duplicate(Request $request, $id)
    {
        if(!adminCan($this->module, 'add')) return dd('No Permission');

        $badge = Badge::find($id);
        if($badge) {
            $newBadge = $badge->replicate();
            $newBadge->name = $badge->name . ' (Copy)';
            $newBadge->created_by = Auth::guard('admin')->user()->id;
            $newBadge->save();
            return redirect()->route('admin.badge.edit', $newBadge->id);
        }
        return redirect()->route('admin.badge.index');
    }
}
