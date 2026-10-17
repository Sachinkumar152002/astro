<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ProductReferralConfiguration;
use Illuminate\Support\Facades\DB;

class ProductReferralConfigurationController extends Controller
{
    private $module = 'product_referral_configuration';
    private $moduleName = 'Product Referral Configuration';
    private $moduleNameSingle = 'Product Referral Configuration';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'product_referral_configuration';
        $breads = [
            ['name' => $this->moduleName]
        ];

        // Get the first/only record or create a new one
        $item = ProductReferralConfiguration::first();
        if(!$item) {
            $item = new ProductReferralConfiguration();
            $item->enable_product_referral = false;
            $item->applicable_user_types = ['user', 'astrologer'];
            $item->commission_mode = 'PERCENTAGE';
            $item->trigger = 'payment_success';
            $item->credit_time = 'instant';
            $item->status = 1;
        }

        return view('admin.product-referral-configuration.form', compact('nav', 'breads', 'item'));
    }

    public function submit(Request $request)
    {
        if(!adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $validations = [
            'enable_product_referral' => 'required|boolean',
            'applicable_user_types' => 'required|array',
            'applicable_user_types.*' => 'in:user,astrologer',
            'commission_mode' => 'required|in:PERCENTAGE,FIXED',
            'trigger' => 'required|in:payment_success',
            'credit_time' => 'required|in:instant,after_completion',
            'status' => 'required|in:0,1'
        ];

        $request->validate($validations, [
            'enable_product_referral.required' => 'Enable Product Referral is required',
            'applicable_user_types.required' => 'Applicable User Types is required',
            'commission_mode.required' => 'Commission mode is required',
            'credit_time.required' => 'Credit time is required',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be Active or Inactive'
        ]);

        try {
            DB::beginTransaction();

            // Get the first/only record or create a new one
            $item = ProductReferralConfiguration::first();
            if(!$item) {
                $item = new ProductReferralConfiguration();
            }

            $item->enable_product_referral = $request->enable_product_referral;
            $item->applicable_user_types = $request->applicable_user_types ?? [];
            $item->commission_mode = $request->commission_mode;
            $item->trigger = $request->trigger;
            $item->credit_time = $request->credit_time;
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

        $redirectTo = route('admin.product_referral_configuration.index');
        return response()->json(['message' => $this->moduleNameSingle . ' Updated Successfully', 'redirectto' => $redirectTo], 200);
    }
}
