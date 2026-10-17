<?php

namespace App\Services;

use App\Model\{AstrologerReferral, Astrologer, WalletTransition};

class AstrologerReferralRewardService
{
    /**
     * Distribute referral rewards when astrologer completes a service
     *
     * @param Astrologer $serviceProviderAstrologer The astrologer who provided the service
     * @param float $serviceAmount The amount earned from the service
     * @param string $serviceType Type of service (consultation, puja, etc.)
     * @param int $serviceId ID of the service record
     * @param string $serviceTableName Table name of the service (e.g., 'astrologer_appointments', 'puja_astrologer_assignments')
     * @return bool Returns true if rewards were distributed, false otherwise
     */
    public static function distributeRewards($serviceProviderAstrologer, $serviceAmount, $serviceType, $serviceId, $serviceTableName)
    {
        // Check if service provider astrologer used a referral code during registration
        // Check used_referral_code field (the code they used, not their own code)
        if(!$serviceProviderAstrologer || !$serviceProviderAstrologer->used_referral_code || trim($serviceProviderAstrologer->used_referral_code) == '') {
            return false; // No referral code used during registration
        }

        // Check if astrologer has already received referral reward (one-time only)
        if($serviceProviderAstrologer->referral_reward_received == 1) {
            \Log::info('AstrologerReferralRewardService: Astrologer has already received referral reward', [
                'astrologer_id' => $serviceProviderAstrologer->id ?? 'NULL',
                'service_type' => $serviceType,
                'service_amount' => $serviceAmount
            ]);
            return false; // Astrologer has already received referral reward
        }

        $referralCode = trim($serviceProviderAstrologer->used_referral_code);

        // Check if it's an individual astrologer's referral code (stored in astrologers table)
        // Referral codes are now only stored in astrologers table, not in astrologer_referrals table
        $referringAstrologer = Astrologer::where('referral_code', $referralCode)
            ->where('status', 1)
            ->where('is_verified', 1)
            ->first();

        if(!$referringAstrologer) {
            return false; // Referring astrologer not found or inactive
        }

        // Prevent self-referral
        if($referringAstrologer->id == $serviceProviderAstrologer->id) {
            return false; // Cannot refer yourself
        }

        // Get global referral settings (from astrologer_referrals table where status = 1)
        $referralSettings = AstrologerReferral::where('status', 1)->first();

        if(!$referralSettings) {
            return false; // No active referral settings found
        }

        $astrologerReward = $referralSettings->astrologer_reward ?? 0;
        $userReward = $referralSettings->user_reward ?? 0;

        // Distribute rewards only if they are greater than 0
        if($astrologerReward > 0) {
            // Add reward to referring astrologer's wallet (the one who owns the referral code)
            $referringAstrologer->wallet += $astrologerReward;
            $referringAstrologer->save();

            // Create wallet transition for referring astrologer
            $walletTransition = new WalletTransition();
            $walletTransition->user_id = $referringAstrologer->id;
            $walletTransition->user_type = "astrologer";
            $walletTransition->table_id = $serviceId;
            $walletTransition->table_name = $serviceTableName;
            $walletTransition->action = 1; // credit
            $walletTransition->status = 1; // success
            $walletTransition->remark = "Referral Reward - {$serviceType} by {$serviceProviderAstrologer->display_name} (AstrologerID:{$serviceProviderAstrologer->id})";
            $walletTransition->invoice = 0;
            $walletTransition->amount = $astrologerReward;
            $walletTransition->save();
        }

        if($userReward > 0) {
            // Add reward to service provider astrologer's wallet (the one who used the referral code)
            $serviceProviderAstrologer->wallet += $userReward;
            // Mark that astrologer has received referral reward (one-time only)
            $serviceProviderAstrologer->referral_reward_received = 1;
            $serviceProviderAstrologer->save();

            \Log::info('AstrologerReferralRewardService: Astrologer reward credited', [
                'astrologer_id' => $serviceProviderAstrologer->id,
                'reward_amount' => $userReward,
                'referral_reward_received_flag_set' => true
            ]);

            // Create wallet transition for service provider astrologer
            $walletTransition = new WalletTransition();
            $walletTransition->user_id = $serviceProviderAstrologer->id;
            $walletTransition->user_type = "astrologer";
            $walletTransition->table_id = $serviceId;
            $walletTransition->table_name = $serviceTableName;
            $walletTransition->action = 1; // credit
            $walletTransition->status = 1; // success
            $walletTransition->remark = "Referral Reward - {$serviceType}";
            $walletTransition->invoice = 0;
            $walletTransition->amount = $userReward;
            $walletTransition->save();
        }

        return true;
    }
}
