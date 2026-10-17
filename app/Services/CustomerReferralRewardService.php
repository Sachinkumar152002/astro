<?php

namespace App\Services;

use App\Model\{CustomerReferral, AstrologerReferral, WalletTransition};
use App\User;

class CustomerReferralRewardService
{
    /**
     * Distribute referral rewards when user completes a service/purchase
     *
     * @param User $serviceUser The user who used the service/purchased product
     * @param float $serviceAmount The amount spent on the service/product
     * @param string $serviceType Type of service (Product Purchase, Puja, Consultation, etc.)
     * @param int $serviceId ID of the service record
     * @param string $serviceTableName Table name of the service (e.g., 'orders', 'astrologer_appointments', 'puja_astrologer_assignments')
     * @return bool Returns true if rewards were distributed, false otherwise
     */
    public static function distributeRewards($serviceUser, $serviceAmount, $serviceType, $serviceId, $serviceTableName)
    {
        // Check if user exists and refresh to get latest data including used_referral_code
        if(!$serviceUser || !$serviceUser->id) {
            \Log::warning('CustomerReferralRewardService: Service user is null or invalid', []);
            return false;
        }

        // Refresh user to get latest used_referral_code from database
        $serviceUser = User::find($serviceUser->id);
        if(!$serviceUser) {
            \Log::warning('CustomerReferralRewardService: User not found in database', [
                'user_id' => $serviceUser->id ?? 'NULL'
            ]);
            return false;
        }

        \Log::info('CustomerReferralRewardService: Starting reward distribution', [
            'service_user_id' => $serviceUser->id ?? 'NULL',
            'service_user_name' => $serviceUser->name ?? 'NULL',
            'service_user_email' => $serviceUser->email ?? 'NULL',
            'service_amount' => $serviceAmount,
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'service_table' => $serviceTableName,
            'user_referral_code' => $serviceUser->referral_code ?? 'NULL',
            'user_used_referral_code' => $serviceUser->used_referral_code ?? 'NULL',
            'user_wallet_before' => $serviceUser->wallet ?? 0
        ]);

        // Check if user used a referral code during registration
        // Check used_referral_code field (the code they used, not their own code)

        if(!$serviceUser->used_referral_code || trim($serviceUser->used_referral_code) == '') {
            \Log::warning('CustomerReferralRewardService: No referral code used during registration', [
                'service_user_id' => $serviceUser->id ?? 'NULL',
                'has_used_referral_code' => isset($serviceUser->used_referral_code),
                'used_referral_code_value' => $serviceUser->used_referral_code ?? 'NULL',
                'own_referral_code' => $serviceUser->referral_code ?? 'NULL',
                'service_type' => $serviceType,
                'service_amount' => $serviceAmount
            ]);
            return false; // No referral code used during registration
        }

        // Check if user has already received referral reward (one-time only)
        if($serviceUser->referral_reward_received == 1) {
            \Log::info('CustomerReferralRewardService: User has already received referral reward', [
                'service_user_id' => $serviceUser->id ?? 'NULL',
                'service_type' => $serviceType,
                'service_amount' => $serviceAmount
            ]);
            return false; // User has already received referral reward
        }

        $referralCode = trim($serviceUser->used_referral_code);

        // Double check that referral code is not empty after trimming
        if(empty($referralCode)) {
            \Log::warning('CustomerReferralRewardService: Referral code is empty after trimming', [
                'service_user_id' => $serviceUser->id ?? 'NULL'
            ]);
            return false;
        }

        \Log::info('CustomerReferralRewardService: Referral code found', [
            'referral_code' => $referralCode,
            'service_amount' => $serviceAmount,
            'service_type' => $serviceType
        ]);

        // Check referral code only in users and astrologers tables (not in settings tables)
        // First, check if it's an astrologer's referral code (stored in astrologers table)
        $referringAstrologer = \App\Model\Astrologer::where('referral_code', $referralCode)
            ->where('status', 1)
            ->where('is_verified', 1)
            ->first();

        // Check if it's a user's referral code (stored in users table)
        $referringUser = User::where('referral_code', $referralCode)
            ->where('status', 1)
            ->first();

        \Log::info('CustomerReferralRewardService: Referral code check', [
            'referring_astrologer_found' => $referringAstrologer ? true : false,
            'referring_astrologer_id' => $referringAstrologer->id ?? 'NULL',
            'referring_user_found' => $referringUser ? true : false,
            'referring_user_id' => $referringUser->id ?? 'NULL'
        ]);

        // If referral code not found in either users or astrologers table, return false
        if(!$referringAstrologer && !$referringUser) {
            \Log::warning('CustomerReferralRewardService: Referral code not found in users or astrologers table', [
                'referral_code' => $referralCode
            ]);
            return false; // Referral code not found
        }

        // Get reward amounts from settings tables
        $userReward = 0;
        $referrerReward = 0;

        if($referringAstrologer) {
            // Astrologer referral code found - get reward amounts from AstrologerReferral settings
            $astrologerReferralSettings = AstrologerReferral::where('status', 1)->first();
            if($astrologerReferralSettings) {
                // Check minimum price requirement
                $minimumPrice = $astrologerReferralSettings->minimum_price ?? 0;
                if($minimumPrice > 0 && $serviceAmount < $minimumPrice) {
                    \Log::info('CustomerReferralRewardService: Service amount is less than minimum price requirement', [
                        'service_amount' => $serviceAmount,
                        'minimum_price' => $minimumPrice,
                        'referring_astrologer_id' => $referringAstrologer->id
                    ]);
                    return false; // Service amount is less than minimum price
                }

                // For Astrologer Referral:
                // user_reward goes to the new user (who used the code)
                // astrologer_reward goes to the referring astrologer (whose code was used)
                $userReward = $astrologerReferralSettings->user_reward ?? 0;
                $referrerReward = $astrologerReferralSettings->astrologer_reward ?? 0;

                \Log::info('CustomerReferralRewardService: Astrologer referral - rewards set', [
                    'user_reward' => $userReward,
                    'referrer_reward' => $referrerReward,
                    'referring_astrologer_id' => $referringAstrologer->id,
                    'service_amount' => $serviceAmount,
                    'minimum_price' => $minimumPrice
                ]);
            } else {
                \Log::warning('CustomerReferralRewardService: Astrologer referral code found but no active settings', [
                    'referring_astrologer_id' => $referringAstrologer->id
                ]);
                return false; // No active settings found
            }
        } elseif($referringUser) {
            // User referral code found - get reward amounts from CustomerReferral settings
            $customerReferralSettings = CustomerReferral::where('status', 1)->first();
            if($customerReferralSettings) {
                // For Customer Referral:
                // user_reward goes to the referrer (the one whose code was used)
                // referrer_reward goes to the new user (the one who used the code)
                $userReward = $customerReferralSettings->referrer_reward ?? 0; // Goes to new user (who used the code)
                $referrerReward = $customerReferralSettings->user_reward ?? 0; // Goes to referrer (whose code was used)

                \Log::info('CustomerReferralRewardService: User referral - rewards set', [
                    'user_reward' => $userReward,
                    'referrer_reward' => $referrerReward,
                    'referring_user_id' => $referringUser->id
                ]);
            } else {
                \Log::warning('CustomerReferralRewardService: User referral code found but no active settings', [
                    'referring_user_id' => $referringUser->id
                ]);
                return false; // No active settings found
            }
        }

        // Prevent self-referral
        if($referringUser && $referringUser->id == $serviceUser->id) {
            return false; // Cannot refer yourself
        }

        // Prevent self-referral for astrologer referrals (if user is also an astrologer)
        if($referringAstrologer && $serviceUser->astrologer_id && $serviceUser->astrologer_id == $referringAstrologer->id) {
            return false; // Cannot refer yourself
        }

        // Check if rewards were already distributed for this service (prevent duplicates)
        // Check if user reward was already given for this service
        if($userReward > 0) {
            $existingUserReward = WalletTransition::where('table_id', $serviceId)
                ->where('table_name', $serviceTableName)
                ->where('user_id', $serviceUser->id)
                ->where('remark', 'like', '%Referral Reward%')
                ->where('action', 1) // credit
                ->exists();

            if($existingUserReward) {
                // User reward already given, skip user reward but continue for referrer reward
                $userReward = 0;
            }
        }

        // Check if referrer reward was already given for this specific service user
        // Referrer should get reward for each new user/astrologer, but not for same user/astrologer's multiple services
        if($referrerReward > 0) {
            if($referringUser) {
                // User referral - check if reward already given to referring user for this specific service user
                $existingReferrerReward = WalletTransition::where('user_id', $referringUser->id)
                    ->where('user_type', 'user')
                    ->where('remark', 'like', '%Referral Reward%' . $serviceUser->name . '%')
                    ->where('action', 1) // credit
                    ->exists();

                if($existingReferrerReward) {
                    \Log::info('CustomerReferralRewardService: Referrer reward already given for this service user', [
                        'referring_user_id' => $referringUser->id,
                        'service_user_id' => $serviceUser->id,
                        'service_user_name' => $serviceUser->name
                    ]);
                    $referrerReward = 0; // Skip referrer reward for this service user
                }
            } elseif($referringAstrologer) {
                // Astrologer referral - check if reward already given to referring astrologer for this specific service user
                $existingReferrerReward = WalletTransition::where('user_id', $referringAstrologer->id)
                    ->where('user_type', 'astrologer')
                    ->where('remark', 'like', '%Referral Reward%' . $serviceUser->name . '%')
                    ->where('action', 1) // credit
                    ->exists();

                if($existingReferrerReward) {
                    \Log::info('CustomerReferralRewardService: Referrer reward already given for this service user', [
                        'referring_astrologer_id' => $referringAstrologer->id,
                        'service_user_id' => $serviceUser->id,
                        'service_user_name' => $serviceUser->name
                    ]);
                    $referrerReward = 0; // Skip referrer reward for this service user
                }
            }
        }

        // If both rewards are 0, nothing to distribute
        if($userReward == 0 && $referrerReward == 0) {
            return false; // No rewards to distribute
        }

        // Distribute referrer rewards only if they are greater than 0
        if($referrerReward > 0) {
            if($referringUser) {
                // User referral - add reward to referring user's wallet (the one whose code was used)
                $oldWallet = $referringUser->wallet ?? 0;
                $referringUser->wallet += $referrerReward;
                $referringUser->save();

                \Log::info('CustomerReferralRewardService: User referrer reward credited', [
                    'referring_user_id' => $referringUser->id,
                    'old_wallet' => $oldWallet,
                    'reward_amount' => $referrerReward,
                    'new_wallet' => $referringUser->wallet
                ]);

                // Create wallet transition for referring user
                $walletTransition = new WalletTransition();
                $walletTransition->user_id = $referringUser->id;
                $walletTransition->user_type = "user";
                $walletTransition->table_id = $serviceId;
                $walletTransition->table_name = $serviceTableName;
                $walletTransition->action = 1; // credit
                $walletTransition->status = 1; // success
                $walletTransition->remark = "Referral Reward - {$serviceType} by {$serviceUser->name} (UserID:{$serviceUser->id})";
                $walletTransition->invoice = 0;
                $walletTransition->amount = $referrerReward;
                $walletTransition->save();
            } elseif($referringAstrologer) {
                // Astrologer referral - add reward to referring astrologer's wallet (the one whose code was used)
                $oldWallet = $referringAstrologer->wallet ?? 0;
                $referringAstrologer->wallet += $referrerReward;
                $referringAstrologer->save();

                \Log::info('CustomerReferralRewardService: Astrologer referrer reward credited', [
                    'astrologer_id' => $referringAstrologer->id,
                    'old_wallet' => $oldWallet,
                    'reward_amount' => $referrerReward,
                    'new_wallet' => $referringAstrologer->wallet
                ]);

                // Create wallet transition for referring astrologer
                $walletTransition = new WalletTransition();
                $walletTransition->user_id = $referringAstrologer->id;
                $walletTransition->user_type = "astrologer";
                $walletTransition->table_id = $serviceId;
                $walletTransition->table_name = $serviceTableName;
                $walletTransition->action = 1; // credit
                $walletTransition->status = 1; // success
                $walletTransition->remark = "Referral Reward - {$serviceType} by {$serviceUser->name} (UserID:{$serviceUser->id})";
                $walletTransition->invoice = 0;
                $walletTransition->amount = $referrerReward;
                $walletTransition->save();
            }
        }

        if($userReward > 0) {
            // Add reward to service user's wallet (the one who used the referral code)
            $oldWallet = $serviceUser->wallet ?? 0;
            $serviceUser->wallet += $userReward;
            // Mark that user has received referral reward (one-time only)
            $serviceUser->referral_reward_received = 1;
            $serviceUser->save();

            \Log::info('CustomerReferralRewardService: User reward credited', [
                'user_id' => $serviceUser->id,
                'old_wallet' => $oldWallet,
                'reward_amount' => $userReward,
                'new_wallet' => $serviceUser->wallet,
                'referral_reward_received_flag_set' => true
            ]);

            // Create wallet transition for service user
            $walletTransition = new WalletTransition();
            $walletTransition->user_id = $serviceUser->id;
            $walletTransition->user_type = "user";
            $walletTransition->table_id = $serviceId;
            $walletTransition->table_name = $serviceTableName;
            $walletTransition->action = 1; // credit
            $walletTransition->status = 1; // success
            $walletTransition->remark = "Referral Reward - {$serviceType}";
            $walletTransition->invoice = 0;
            $walletTransition->amount = $userReward;
            $walletTransition->save();
        }

        \Log::info('CustomerReferralRewardService: Reward distribution completed successfully', [
            'service_user_id' => $serviceUser->id,
            'service_amount' => $serviceAmount,
            'service_type' => $serviceType,
            'user_reward_distributed' => $userReward > 0 ? 'YES' : 'NO',
            'referrer_reward_distributed' => $referrerReward > 0 ? 'YES' : 'NO',
            'user_wallet_after' => $serviceUser->wallet ?? 0
        ]);
        return true;
    }
}
