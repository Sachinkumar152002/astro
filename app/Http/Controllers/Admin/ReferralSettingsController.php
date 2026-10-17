<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AstrologerReferral;
use App\Model\CustomerReferral;

class ReferralSettingsController extends Controller
{
    /**
     * Combined Referral Settings screen with tabs:
     * - App Referral Management (Customer referral)
     * - Product / Service Referral Configuration (Astrologer referral)
     */
    public function index(Request $request)
    {
        // Permission: allow if admin can manage either customer or astrologer referral
        $canAstrologerReferral = adminCan('astrologer_referral', 'view');
        $canCustomerReferral   = adminCan('customer_referral', 'view');

        if(!$canAstrologerReferral && !$canCustomerReferral){
            return dd('No Permission');
        }

        $nav = 'referral_settings';
        $breads = [
            ['name' => 'Referral Management'],
            ['name' => 'Referral Settings'],
        ];

        // Load/create single global AstrologerReferral settings
        $astrologerReferral = AstrologerReferral::first();
        if(!$astrologerReferral){
            $astrologerReferral = new AstrologerReferral();
            $astrologerReferral->astrologer_reward = 0;
            $astrologerReferral->user_reward = 0;
            $astrologerReferral->minimum_price = 0;
            $astrologerReferral->product_discount = 0;
            $astrologerReferral->status = 1;
        }

        // Load/create single global CustomerReferral settings
        $customerReferral = CustomerReferral::first();
        if(!$customerReferral){
            $customerReferral = new CustomerReferral();
            $customerReferral->user_reward = 0;
            $customerReferral->referrer_reward = 0;
            $customerReferral->status = 1;
        }

        return view('admin.referral.settings', compact(
            'nav',
            'breads',
            'astrologerReferral',
            'customerReferral',
            'canAstrologerReferral',
            'canCustomerReferral'
        ));
    }
}
