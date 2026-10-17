<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CategoryCommission;
use App\Model\Category;
use App\Model\ProductCategory;
use App\Model\ProductReferralConfiguration;
use Illuminate\Support\Facades\DB;

class CategoryCommissionController extends Controller
{
    private $module = 'category_commissions';
    private $moduleName = 'Category Commissions';
    private $moduleNameSingle = 'Category Commission';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'category_commissions';
        $breads = [
            ['name' => $this->moduleName]
        ];

        // Get all product categories grouped by type
        $productCategories = ProductCategory::where('status', 1)
            ->where('type', 1)
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get();

        $serviceCategories = ProductCategory::where('status', 1)
            ->where('type', 3)
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get();

        $pujaCategories = ProductCategory::where('status', 1)
            ->where('type', 2)
            ->where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get();

        // Get existing commissions mapped by category_id
        $existingCommissions = CategoryCommission::all()->keyBy('category_id');

        // Get commission mode from Product Referral Configuration
        $productReferralConfig = ProductReferralConfiguration::where('status', 1)->first();
        $commissionMode = $productReferralConfig && $productReferralConfig->commission_mode ? $productReferralConfig->commission_mode : 'PERCENTAGE';

        return view('admin.category-commissions.list', compact('nav', 'breads', 'productCategories', 'serviceCategories', 'pujaCategories', 'existingCommissions', 'commissionMode'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $results = CategoryCommission::with('category')
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($category_id = $request->category_id) {
            $results->where('category_id', $category_id);
        }

        if(isset($request->status) && is_numeric($request->status)) {
            $results->where('status', $request->status);
        }

        if($updated_at_from = $request->updated_at_from) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ?", [$updated_at_from]);
        }

        if($updated_at_to = $request->updated_at_to) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ?", [$updated_at_to]);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.category-commissions.listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }

    public function form(Request $request)
    {
        $id = $request->id;
        $nav = 'category_commissions';
        $breads = [
            ['name' => $this->moduleName, 'url' => route('admin.category_commissions.index')],
            ['name' => ($id ? 'Edit' : 'Add')]
        ];

        $item = CategoryCommission::findOrNew($id);
        $categories = ProductCategory::where('status', 1)
            ->orderBy('type', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->mapWithKeys(function($cat) {
                $typeName = $cat->type == 1 ? 'Product' : ($cat->type == 2 ? 'Puja' : 'Service');
                return [$cat->id => "{$typeName} - {$cat->name}"];
            })
            ->toArray();

        return view('admin.category-commissions.form', compact('nav', 'breads', 'item', 'categories'));
    }

    public function submit(Request $request)
    {
        // Bulk save for Excel-like format
        if($request->has('commissions') && is_array($request->commissions)) {
            if(!adminCan($this->module, 'update') && !adminCan($this->module, 'add')) {
                return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
            }

            try {
                DB::beginTransaction();

                foreach($request->commissions as $commissionData) {
                    if(!isset($commissionData['category_id']) || !$commissionData['category_id']) {
                        continue;
                    }

                    $categoryId = $commissionData['category_id'];
                    $commissionValue = isset($commissionData['commission_value']) ? (float)$commissionData['commission_value'] : 0;
                    $maxCap = isset($commissionData['max_cap']) && $commissionData['max_cap'] !== '' ? (float)$commissionData['max_cap'] : null;
                    $status = isset($commissionData['status']) ? (int)$commissionData['status'] : 0;

                    // Validate category exists in product_categories
                    $productCategory = ProductCategory::find($categoryId);
                    if(!$productCategory) {
                        continue;
                    }

                    // Check if commission exists
                    $item = CategoryCommission::where('category_id', $categoryId)->first();
                    if(!$item) {
                        $item = new CategoryCommission();
                        $item->category_id = $categoryId;
                    }

                    $item->commission_value = $commissionValue;
                    $item->max_cap = $maxCap;
                    $item->status = $status;
                    $item->save();
                }

                DB::commit();
                return response()->json(['message' => 'Category Commissions Updated Successfully', 'redirectto' => route('admin.category_commissions.index')], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => ['general' => ['An error occurred: ' . $e->getMessage()]]], 422);
            }
        }

        // Single save (for backward compatibility)
        $id = $request->id;

        if($id && !adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        if(!$id && !adminCan($this->module, 'add')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $validations = [
            'category_id' => 'required|exists:product_categories,id|unique:category_commissions,category_id,' . ($id ?? 'NULL'),
            'commission_value' => 'required|numeric|min:0',
            'max_cap' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1'
        ];

        $request->validate($validations, [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'category_id.unique' => 'Commission already exists for this category',
            'commission_value.required' => 'Commission value is required',
            'commission_value.numeric' => 'Commission value must be a number',
            'commission_value.min' => 'Commission value must be 0 or greater',
            'max_cap.numeric' => 'Max cap must be a number',
            'max_cap.min' => 'Max cap must be 0 or greater',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be Active or Inactive'
        ]);

        try {
            DB::beginTransaction();

            $item = CategoryCommission::findOrNew($id);
            $item->category_id = $request->category_id;
            $item->commission_value = $request->commission_value;
            $item->max_cap = $request->max_cap;
            $item->status = $request->status;
            $item->save();

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if($e->getCode() == 23000) {
                return response()->json(['errors' => ['general' => ['Database error: ' . $e->getMessage()]]], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => ['An error occurred: ' . $e->getMessage()]]], 422);
        }

        $redirectTo = route('admin.category_commissions.index');
        return response()->json(['message' => $this->moduleNameSingle . ' ' . ($id ? 'Updated' : 'Created') . ' Successfully', 'redirectto' => $redirectTo], 200);
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        if($id && !adminCan($this->module, 'delete')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        if($id) {
            $item = CategoryCommission::find($id);
            if($item) {
                $item->delete();
                return response()->json(['message' => 'Deleted successfully!'], 200);
            }
        }

        return response()->json(['message' => 'Error in deleting data'], 401);
    }
}
