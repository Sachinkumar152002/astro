<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ReferralConfiguration;
use Illuminate\Support\Facades\DB;

class ReferralConfigurationController extends Controller
{
    private $module = 'referral_configuration';
    private $moduleName = 'App Referral Configuration';
    private $moduleNameSingle = 'App Referral Configuration';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'referral_configuration';
        $breads = [
            ['name' => $this->moduleName]
        ];

        // Get the first/only record or create a new one
        $item = ReferralConfiguration::first();
        if(!$item) {
            $item = new ReferralConfiguration();
            $item->enable_app_referral = false;
        }

        return view('admin.referral-configuration.form', compact('nav', 'breads', 'item'));
    }

    public function submit(Request $request)
    {
        if(!adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $validations = [
            'enable_app_referral' => 'required|boolean',

            // Astrologer to Astrologer
            'astrologer_to_astrologer_reward_amount' => 'required|numeric|min:0',
            'astrologer_to_astrologer_trigger' => 'required|in:signup,account_verify,first_consultation,min_consultation_amount',
            'astrologer_to_astrologer_min_consultation_amount' => 'nullable|numeric|min:0|required_if:astrologer_to_astrologer_trigger,min_consultation_amount',
            'astrologer_to_astrologer_max_referrals' => 'nullable|integer|min:0',
            'astrologer_to_astrologer_max_earning' => 'nullable|numeric|min:0',
            'astrologer_to_astrologer_expiry_days' => 'nullable|integer|min:0',
            'astrologer_to_astrologer_status' => 'required|in:0,1',

            // Astrologer to Customer
            'astrologer_to_customer_reward_amount' => 'required|numeric|min:0',
            'astrologer_to_customer_trigger' => 'required|in:signup,first_purchase',
            'astrologer_to_customer_min_purchase_amount' => 'nullable|numeric|min:0|required_if:astrologer_to_customer_trigger,first_purchase',
            'astrologer_to_customer_max_referrals' => 'nullable|integer|min:0',
            'astrologer_to_customer_max_earning' => 'nullable|numeric|min:0',
            'astrologer_to_customer_expiry_days' => 'nullable|integer|min:0',
            'astrologer_to_customer_status' => 'required|in:0,1',

            // Customer
            'customer_reward_amount' => 'required|numeric|min:0',
            'customer_trigger' => 'required|in:signup,first_purchase',
            'customer_min_purchase_amount' => 'nullable|numeric|min:0|required_if:customer_trigger,first_purchase',
            'customer_max_referrals' => 'nullable|integer|min:0',
            'customer_max_earning' => 'nullable|numeric|min:0',
            'customer_expiry_days' => 'nullable|integer|min:0',
            'customer_status' => 'required|in:0,1'
        ];

        $request->validate($validations);

        try {
            DB::beginTransaction();

            // Get the first/only record or create a new one
            $item = ReferralConfiguration::first();
            if(!$item) {
                $item = new ReferralConfiguration();
            }

            $item->enable_app_referral = $request->enable_app_referral;

            // Astrologer to Astrologer
            $item->astrologer_to_astrologer_reward_amount = $request->astrologer_to_astrologer_reward_amount;
            $item->astrologer_to_astrologer_trigger = $request->astrologer_to_astrologer_trigger;
            $item->astrologer_to_astrologer_min_consultation_amount = $request->astrologer_to_astrologer_min_consultation_amount;
            $item->astrologer_to_astrologer_max_referrals = $request->astrologer_to_astrologer_max_referrals;
            $item->astrologer_to_astrologer_max_earning = $request->astrologer_to_astrologer_max_earning;
            $item->astrologer_to_astrologer_expiry_days = $request->astrologer_to_astrologer_expiry_days;
            $item->astrologer_to_astrologer_status = $request->astrologer_to_astrologer_status;

            // Astrologer to Customer
            $item->astrologer_to_customer_reward_amount = $request->astrologer_to_customer_reward_amount;
            $item->astrologer_to_customer_trigger = $request->astrologer_to_customer_trigger;
            $item->astrologer_to_customer_min_purchase_amount = $request->astrologer_to_customer_min_purchase_amount;
            $item->astrologer_to_customer_max_referrals = $request->astrologer_to_customer_max_referrals;
            $item->astrologer_to_customer_max_earning = $request->astrologer_to_customer_max_earning;
            $item->astrologer_to_customer_expiry_days = $request->astrologer_to_customer_expiry_days;
            $item->astrologer_to_customer_status = $request->astrologer_to_customer_status;

            // Customer
            $item->customer_reward_amount = $request->customer_reward_amount;
            $item->customer_trigger = $request->customer_trigger;
            $item->customer_min_purchase_amount = $request->customer_min_purchase_amount;
            $item->customer_max_referrals = $request->customer_max_referrals;
            $item->customer_max_earning = $request->customer_max_earning;
            $item->customer_expiry_days = $request->customer_expiry_days;
            $item->customer_status = $request->customer_status;

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

        $redirectTo = route('admin.referral_configuration.index');
        return response()->json(['message' => $this->moduleNameSingle . ' Updated Successfully', 'redirectto' => $redirectTo], 200);
    }
}
