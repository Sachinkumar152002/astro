# Referral Module - Complete Build Plan

## Module Overview
Comprehensive referral system supporting:
- **App Referral**: Fixed amount rewards for user signups/referrals
- **Product Referral**: Category-based commissions (Percentage or Fixed)
- **Dual Wallet System**: Separate Referral Wallet and Commission Wallet
- **User Types**: Client Users and Astrologers

## Database Schema

### 1. `referral_configurations` (App Referral Settings)
```sql
- id
- enable_app_referral (boolean, default: false)
- applicable_user_types (json: ['user', 'astrologer'])
- reward_amount (decimal 10,2)
- reward_trigger (enum: 'signup', 'first_purchase')
- max_referrals_per_user (integer, nullable)
- max_total_earning (decimal 10,2, nullable)
- reward_expiry_days (integer, nullable)
- status (tinyint, default: 1)
- created_at, updated_at
```

### 2. `product_referral_configurations` (Product Referral Settings)
```sql
- id
- enable_product_referral (boolean, default: false)
- applicable_user_types (json: ['user', 'astrologer'])
- commission_mode (enum: 'PERCENTAGE', 'FIXED')
- trigger (enum: 'payment_success')
- credit_time (enum: 'instant', 'after_completion')
- status (tinyint, default: 1)
- created_at, updated_at
```

### 3. `category_commissions` (Category Commission Rates)
```sql
- id
- category_id (foreign key to categories)
- commission_value (decimal 10,2)
- max_cap (decimal 10,2, nullable)
- status (tinyint, default: 1)
- created_at, updated_at
```

### 4. `referral_wallets` (Referral Wallet Balances)
```sql
- id
- user_id (integer)
- user_type (enum: 'user', 'astrologer')
- balance (decimal 10,2, default: 0)
- lifetime_earnings (decimal 10,2, default: 0)
- created_at, updated_at
- unique(user_id, user_type)
```

### 5. `commission_wallets` (Commission Wallet Balances)
```sql
- id
- user_id (integer)
- user_type (enum: 'user', 'astrologer')
- balance (decimal 10,2, default: 0)
- lifetime_earnings (decimal 10,2, default: 0)
- created_at, updated_at
- unique(user_id, user_type)
```

### 6. `referral_transactions` (App Referral Transactions)
```sql
- id
- referrer_id (integer)
- referrer_type (enum: 'user', 'astrologer')
- referred_id (integer)
- referred_type (enum: 'user', 'astrologer')
- reward_amount (decimal 10,2)
- reward_trigger (enum: 'signup', 'first_purchase')
- status (enum: 'pending', 'credited', 'expired', 'cancelled')
- expiry_date (datetime, nullable)
- credited_at (datetime, nullable)
- order_id (integer, nullable) - for first_purchase trigger
- created_at, updated_at
```

### 7. `commission_transactions` (Product Commission Transactions)
```sql
- id
- referrer_id (integer)
- referrer_type (enum: 'user', 'astrologer')
- order_id (integer, foreign key to orders)
- order_item_id (integer, nullable, foreign key to order_items)
- category_id (integer, foreign key to categories)
- order_amount (decimal 10,2)
- commission_rate (decimal 10,2)
- commission_amount (decimal 10,2)
- max_cap_applied (decimal 10,2, nullable)
- commission_mode (enum: 'PERCENTAGE', 'FIXED')
- credit_time (enum: 'instant', 'after_completion')
- status (enum: 'pending', 'credited', 'cancelled', 'refunded')
- credited_at (datetime, nullable)
- refunded_at (datetime, nullable)
- created_at, updated_at
```

### 8. `wallet_ledgers` (Unified Ledger for Both Wallets)
```sql
- id
- wallet_type (enum: 'referral', 'commission')
- user_id (integer)
- user_type (enum: 'user', 'astrologer')
- transaction_type (enum: 'credit', 'debit')
- amount (decimal 10,2)
- balance_after (decimal 10,2)
- reference_type (enum: 'referral_transaction', 'commission_transaction', 'withdrawal', 'adjustment')
- reference_id (integer, nullable)
- description (text)
- created_at, updated_at
```

### 9. `referral_withdrawals` (Withdrawal Requests - Astrologers Only)
```sql
- id
- astrologer_id (integer, foreign key to astrologers)
- wallet_type (enum: 'referral', 'commission', 'both')
- referral_amount (decimal 10,2, default: 0)
- commission_amount (decimal 10,2, default: 0)
- total_amount (decimal 10,2)
- status (enum: 'pending', 'approved', 'rejected', 'processed')
- admin_notes (text, nullable)
- processed_at (datetime, nullable)
- created_at, updated_at
```

## Models to Create

1. `ReferralConfiguration`
2. `ProductReferralConfiguration`
3. `CategoryCommission`
4. `ReferralWallet`
5. `CommissionWallet`
6. `ReferralTransaction`
7. `CommissionTransaction`
8. `WalletLedger`
9. `ReferralWithdrawal`

## Controllers Structure

### Admin Controllers:
1. `Admin/ReferralConfigurationController` - App Referral Settings
2. `Admin/ProductReferralConfigurationController` - Product Referral Settings
3. `Admin/CategoryCommissionController` - Category Commission Management
4. `Admin/ReferralTransactionController` - View App Referral Transactions
5. `Admin/CommissionTransactionController` - View Product Commission Transactions
6. `Admin/ReferralWithdrawalController` - Manage Withdrawal Requests

### Frontend Controllers:
1. `ReferralController` - Refer & Earn, App Referrals
2. `CommissionController` - Product Commissions, Commission Detail
3. `WalletController` - Earnings Dashboard, Wallet Ledger
4. `WithdrawalController` - Withdrawal (Astrologers)

## Services to Create

1. `ReferralService` - Handle app referral logic
2. `CommissionService` - Handle product commission logic
3. `WalletService` - Manage both wallets and ledger
4. `PayoutService` - Handle withdrawals

## Static Screens to Create (Frontend)

### User Screens:
1. **Refer & Earn** (`/account/refer-earn`)
   - Share referral code
   - Referral link
   - Social sharing buttons
   - Referral rules summary

2. **Earnings Dashboard** (`/account/earnings`)
   - Total earnings (both wallets)
   - Referral wallet balance
   - Commission wallet balance
   - Recent transactions
   - Quick stats

3. **App Referrals** (`/account/app-referrals`)
   - List of referred users
   - Status of each referral
   - Rewards earned per referral
   - Filter by status

4. **Product Commissions** (`/account/commissions`)
   - List of commission transactions
   - Filter by category, date, status
   - Total commissions earned
   - Pending vs credited

5. **Commission Detail** (`/account/commissions/{id}`)
   - Commission transaction details
   - Order information
   - Category details
   - Calculation breakdown

6. **Wallet Ledger** (`/account/wallet-ledger`)
   - Unified ledger for both wallets
   - Filter by wallet type, transaction type, date
   - Export functionality

7. **Withdrawal** (`/astrologers/account/withdrawal`) - Astrologers Only
   - Withdrawal form
   - Available balances
   - Withdrawal history
   - Minimum withdrawal amount

8. **Referral Rules** (`/account/referral-rules`)
   - Complete rules and terms
   - FAQ section
   - Commission structure

### Astrologer Screens (Same as User but with withdrawal):
- All user screens plus withdrawal page

## Admin Screens

1. **App Referral Configuration** (`/admin/referral-configuration`)
2. **Product Referral Configuration** (`/admin/product-referral-configuration`)
3. **Category Commission Management** (`/admin/category-commissions`)
4. **Referral Transactions** (`/admin/referral-transactions`)
5. **Commission Transactions** (`/admin/commission-transactions`)
6. **Withdrawal Requests** (`/admin/referral-withdrawals`)

## Implementation Phases

### Phase 1: Database & Models (Current)
- Create all migrations
- Create all models with relationships
- Add fillable, casts, relationships

### Phase 2: Static Screens (Current Focus)
- Create all frontend views (static)
- Create all admin views (static)
- Add navigation links
- Style with existing theme

### Phase 3: Admin Configuration
- Admin configuration controllers
- Settings forms
- Category commission CRUD

### Phase 4: Backend Services
- ReferralService implementation
- CommissionService implementation
- WalletService implementation
- PayoutService implementation

### Phase 5: Frontend Controllers
- Connect views to controllers
- Add data fetching
- Implement filters and pagination

### Phase 6: Integration
- Hook into order completion
- Hook into user registration
- Handle refunds
- Test complete flow

## Key Business Logic

### App Referral Flow:
1. User A shares referral code
2. User B registers using code → `used_referral_code` set
3. On trigger (signup/first_purchase):
   - Check if referral valid (not expired, not self, limits not exceeded)
   - Create `referral_transaction`
   - Credit `referral_wallet`
   - Create `wallet_ledger` entry

### Product Commission Flow:
1. User A shares referral code
2. User B makes purchase using code
3. On payment success:
   - Get order items and categories
   - Calculate commission per category
   - Create `commission_transaction` for each
   - If instant: Credit `commission_wallet` immediately
   - If after_completion: Mark as pending, credit on completion
   - Create `wallet_ledger` entry

### Commission Calculation:
```
IF commission_mode = 'PERCENTAGE':
    commission = order_amount * (commission_value / 100)
ELSE:
    commission = commission_value

IF commission > max_cap:
    commission = max_cap
```

### Withdrawal Flow (Astrologers):
1. Astrologer requests withdrawal
2. Admin reviews and approves
3. Process payment
4. Debit wallets
5. Update ledger

## Validation Rules

- Self-referral blocked
- Max referrals per user check
- Max total earning check
- Reward expiry check
- Refund cancels commission
- Minimum withdrawal amount
- Commission cap enforcement

## Next Steps

1. ✅ Create complete plan
2. ⏳ Create all migrations
3. ⏳ Create all models
4. ⏳ Create all static screens (frontend + admin)
5. ⏳ Implement admin configuration
6. ⏳ Implement backend services
7. ⏳ Connect frontend controllers
8. ⏳ Integration and testing
