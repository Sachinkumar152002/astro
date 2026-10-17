# AstroKavach – Coding Style & API Reference

## 1. Project & Coding Style

### Stack
- **Framework:** Laravel (PHP)
- **API style:** REST-like; most endpoints use **POST** with JSON/form body
- **Auth:** Token-based (`auth:api`); user token via `api_token` (body or `Authorization: Bearer`)
- **Response:** JSON with `success` (bool), `message`, and optional `data`

### Conventions
- **Routes:** `routes/api.php` → prefix `/api`; `routes/web.php` and `theme.php` for web; `routes/admin.php` for admin panel
- **Controllers:** `App\Http\Controllers\Api\*` for API; `Controller@method` syntax in routes
- **Validation:** `$request->validate([...])` in controllers; validation errors return 422
- **Success response:** `response()->json(['success' => true, 'message' => '...', ...], 200)`
- **Error response:** `response()->json(['success' => false, 'message' => '...'], 4xx/5xx)`
- **Naming:** camelCase in JSON; snake_case in DB/backend; route names use dot notation (e.g. `api.user.`)

### Auth (API)
- **Guest:** No token.
- **User:** Pass `api_token` in request body (or `Authorization: Bearer <api_token>` where supported). Obtain token via register or OTP login.
- **Astrologer:** Separate guard; `api_token` for astrologer in astrologer-specific routes.

---

## 2. Base URL
- **API base:** `{{base_url}}/api`
  Example: `https://your-domain.com/api`

---

## 3. Full API List (routes/api.php)

All paths below are relative to `/api`. **POST** unless stated otherwise.

### 3.1 Auth & User (public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/register/user` | Register user (name, email, phone) |
| POST | `/otp/user` | Get OTP for login (phone) |
| POST | `/otp/user/verify` | Verify OTP and get token |

### 3.2 General (public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/countries` | List countries |
| POST | `/states` | List states |
| POST | `/cities` | List cities |
| POST | `/banners` | Banners |
| POST | `/available-date` | Available dates for scheduling |
| POST | `/available-time` | Available times for scheduling |
| POST | `/gifts` | Gifts list |
| POST | `/payment-types` | Payment types |
| POST | `/order-cancel-reasons` | Order cancel reasons |
| POST | `/order-return-reasons` | Order return reasons |
| POST | `/search` | Search |
| POST | `/term-and-conditions` | Terms and conditions |
| POST | `/can-order` | Check if user can order |
| POST | `/verify-payment` | Verify payment |
| GET  | `/packages/` | Package data |
| POST | `/blogs` | Blogs list |
| POST | `/zodiac-signs` | Zodiac signs |
| POST | `/faqs` | FAQs |
| POST | `/ads` | Advertisements |
| POST | `/can-review` | Can review check |
| POST | `/get-in-touch` | Get in touch form |
| POST | `/astrology/detail` | Astrology detail |
| POST | `/software/detail` | Software (NG) detail |

### 3.3 Kundali (included from kundali.php under /api)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET  | `/kundali/` | Kundali index |
| POST | `/kundali/` | Kundali enquiry submit |
| GET  | `/kundali/report` | Kundali report detail |
| POST | `/kundali/gem-suggestion` | Gem suggestion |
| POST | `/kundali/rudraksh-suggestion` | Rudraksh suggestion |
| POST | `/kundali/ashtakvarga` | Ashtakvarga chart |
| POST | `/kundali/chart` | Chart |
| POST | `/kundali/basic` | Basic details |
| POST | `/kundali/jaimini-karakas` | Jaimini karakas |
| POST | `/kundali/transit-dates` | Transit dates |
| POST | `/kundali/transit-chart` | Transit chart |
| POST | `/kundali/dosha/manglik` | Manglik dosha |
| POST | `/kundali/dosha/kalsarpa` | Kalsarpa dosha |
| POST | `/kundali/dosha/sadesati` | Sadesati dosha |
| POST | `/kundali/dosha/mangal` | Mangal dosha |
| POST | `/kundali/dasha/current-mahadasha` | Current mahadasha |
| POST | `/kundali/dasha/antardasha` | Antardasha |
| POST | `/kundali/dasha/mahadasha` | Mahadasha |
| POST | `/kundali/dasha/yogini` | Yogini dasha |
| GET  | `/panchang` | Panchang |

### 3.4 User (protected: auth:api) – prefix `/user`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/user/data` | Get user data |
| POST | `/user/data/save` | Save user data |
| POST | `/user/addresses` | List addresses |
| POST | `/user/addresses-submit` | Add/Edit address |
| POST | `/user/addresses-delete` | Delete address |
| POST | `/user/book-consultaion` | Book astrologer consultation |
| POST | `/user/book-ai-consultaion` | Book AI consultation |
| POST | `/user/can-chat` | Can chat check |
| POST | `/user/chat-logs` | Chat logs |
| POST | `/user/send-message` | Send message |
| POST | `/user/can-phone-call` | Can phone call |
| POST | `/user/update-phone-call-status` | Update phone call status |
| POST | `/user/can-video-call` | Can video call |
| POST | `/user/update-video-call-status` | Update video call status |
| POST | `/user/consultations` | Consultations list |
| POST | `/user/chat-logs-ai` | AI chat logs |
| POST | `/user/send-message-ai` | Send message to AI |
| POST | `/user/update-chat-time-ai` | Update AI chat time |
| POST | `/user/ai-consultations` | AI consultations list |
| POST | `/user/coupon` | Apply coupon |
| POST | `/user/recharge-plans` | Recharge plans |
| POST | `/user/recharge` | Make recharge |
| POST | `/user/verify-recharge-payment/{user_recharge_id}/{payment_type}` | Verify recharge payment |
| POST | `/user/recharge-histories` | Recharge history |
| POST | `/user/wallet-transition-history` | Wallet transaction history |
| POST | `/user/wishlist/list` | Wishlist list |
| POST | `/user/wishlist/add` | Add to wishlist |
| POST | `/user/wishlist/remove` | Remove from wishlist |
| POST | `/user/checkout` | Checkout |
| POST | `/user/place-order` | Place order |
| POST | `/user/verify-order-payment/{order_id}/{payment_type}` | Verify order payment |
| POST | `/user/submit-review` | Submit review |
| POST | `/user/orders` | Orders list |
| POST | `/user/order-detail` | Order detail |
| POST | `/user/order-request` | Order request (cancel/return etc.) |
| POST | `/user/support/types` | Support types |
| POST | `/user/support/list` | Support list |
| POST | `/user/support/submit-ticket` | Submit ticket |
| POST | `/user/support/messages` | Support messages |
| POST | `/user/support/submit-message` | Submit support message |
| POST | `/user/live/astrologers` | Live astrologers |
| POST | `/user/join/live/astrologer` | Join live astrologer |
| POST | `/user/update/joined/user` | Update joined user (live) |
| POST | `/user/gifts/send` | Send gift |
| POST | `/user/live-chat/send` | Send live chat |
| POST | `/user/notify-user/save` | Save user notification |
| POST | `/user/notifications` | Get notifications |
| POST | `/user/logout` | Logout |

### 3.5 Astrologer (public) – prefix `/astrologer`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/astrologer/types` | Astrologer types |
| POST | `/astrologer/experiences` | Experiences |
| POST | `/astrologer/prices` | Prices |
| POST | `/astrologer/ratings` | Ratings |
| POST | `/astrologer/services` | Services |
| POST | `/astrologer/languages` | Languages |
| POST | `/astrologer/sort-by` | Sort options |
| POST | `/astrologer/list/{type?}` | Astrologer list |
| POST | `/astrologer/detail` | Astrologer detail |
| POST | `/astrologer/update-status` | Update status |
| POST | `/astrologer/review` | Review |
| POST | `/astrologer/verify-astrologer-mobile-number` | Verify astrologer mobile OTP (send OTP to phone) |
| POST | `/astrologer/verify-astrologer-otp` | Verify astrologer OTP (after OTP, user can call register) |
| GET  | `/astrologer/register/options` | Registration form options (languages, services, countries, states) |
| GET  | `/astrologer/register/cities?state_id=1` | Cities for state (for address dropdown) |
| GET  | `/astrologer/ifsc/{ifsc}` | IFSC lookup – returns BANK, CITY, STATE (e.g. YESB0DNB002) |
| POST | `/astrologer/register/step/1` | Step 1: basic info + profile image; returns draft_token |
| POST | `/astrologer/register/step/2` | Step 2: skills, languages, about (send draft_token) |
| POST | `/astrologer/register/step/3` | Step 3: bank details (send draft_token) |
| POST | `/astrologer/register` | Astrologer register (single-shot or step 4 with draft_token; see §4) |
| POST | `/astrologer/otp` | Astrologer OTP (login) |
| POST | `/astrologer/verify-otp` | Astrologer verify OTP |
| POST | `/astrologer/data` | Astrologer data |
| GET/POST | `/astrologer/earnings` | Astrologer earnings (wallet summary + transaction history; auth required) |
| GET/POST | `/astrologer/puja` | Pujas assigned to astrologer (one-to-one + group); requires api_token |
| GET/POST | `/astrologer/booked-puja` | Booked puja assignments; requires api_token |
| GET/POST | `/astrologer/puja-earnings` | Puja earnings (commission history); requires api_token |
| GET/POST | `/astrologer/referral-listing` | Referral listing (users/astrologers referred); requires api_token |
| POST | `/astrologer/logout` | Astrologer logout |

### 3.6 Product – prefix `/product`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/product/categories` | Categories |
| POST | `/product/types` | Types |
| POST | `/product/subtypes` | Subtypes |
| POST | `/product/sortby` | Sort by |
| POST | `/product/list` | Product list |
| POST | `/product/detail` | Product detail |
| POST | `/product/review` | Product review |

### 3.7 Cart – prefix `/cart`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/cart/list` | Cart list |
| POST | `/cart/add` | Add to cart |
| POST | `/cart/update` | Update cart item |
| POST | `/cart/remove` | Remove item |
| POST | `/cart/increase` | Increase quantity |
| POST | `/cart/decrease` | Decrease quantity |

### 3.8 Blog – prefix `/blog`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/blog/categories` | Blog categories |
| POST | `/blog/list` | Blog list |
| POST | `/blog/detail` | Blog detail |

### 3.9 Zodiac / Horoscope – prefix `/zodiac`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/zodiac/list` | Zodiac list |
| POST | `/zodiac/time-frames` | Time frames |
| POST | `/zodiac/detail` | Zodiac detail |

### 3.10 Panchang – prefix `/panchang`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/panchang/detail` | Panchang detail |

### 3.11 Location
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/location/` | Locations (kundali) |

### 3.12 Kundali Matching – prefix `/kundali-matching`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/kundali-matching/detail` | Kundali matching detail |

### 3.13 Course – prefix `/course`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/course/categories` | Course categories |
| POST | `/course/types` | Course types |
| POST | `/course/package-types` | Package types |
| POST | `/course/sortby` | Sort by |
| POST | `/course/list` | Course list |
| POST | `/course/detail/{slug}` | Course detail |
| POST | `/course/{slug}/review` | Course review |

### 3.14 Testimonials – prefix `/testimonials`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/testimonials/text` | Text testimonials |
| POST | `/testimonials/video` | Video testimonials |

### 3.15 Vastu – prefix `/vastu`
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/vastu/detail` | Vastu detail |
| POST | `/vastu/service/detail` | Vastu service detail |

### 3.16 Other (GET)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/{id}/chat/{user_token}` | Make chat |
| GET | `/chat-logs/{chat_id}` | Chat logs (GET) |
| GET | `/consultation/{user_token}` | All consultations |
| GET | `/search-location/{title?}` | Search location |

---

## 4. Web & Admin

- **Web routes:** `routes/web.php`, `routes/theme.php` – used by the website (GET/POST with views, sessions).
- **Admin panel:** `routes/admin.php` – prefix `astrokavachadmin`, session auth.
- **Astrologer panel:** `routes/astrologers.php` – prefix `astrologer`, astrologer auth.

These are not included in the Postman API collection below; the collection covers **API** (`/api/*`) only.

---

## 4. Astrologer Registration API (Flutter)

Base path: `/api/astrologer`. No auth required for these endpoints.

### 4.1 Get registration options
**GET** `/api/astrologer/register/options`

Returns dropdown data for the registration form.

**Response:**
`{ "success": true, "data": { "languages": [...], "services": [...], "countries": [...], "states": [...] } }`

### 4.2 Get cities by state
**GET** `/api/astrologer/register/cities?state_id=1`

**Response:**
`{ "success": true, "data": [ { "id", "name", "state_id" }, ... ] }`

### 4.3 IFSC lookup
**GET** `/api/astrologer/ifsc/{ifsc}`
Example: `GET /api/astrologer/ifsc/YESB0DNB002`

**Response (200):**
`{ "BANK": "Yes Bank", "STATE": "Maharashtra", "CITY": "Mumbai", ... }`
Use `BANK`, `STATE`, `CITY` for bank-details fields.

**Errors:** 422 invalid IFSC, 404 not found, 502 network error.

### 4.4 Step-wise registration (app)

Use these when building a multi-step registration flow in the app. After **Step 1** the API returns a **draft_token**. Send this token in **Step 2**, **Step 3**, and in the final **Register** (Step 4) so the server can merge all data. Draft expires after **60 minutes**.

| Step | Endpoint | Method | Purpose |
|------|----------|--------|--------|
| 1 | `/api/astrologer/register/step/1` | POST | Basic info + profile image. Returns `draft_token`. |
| 2 | `/api/astrologer/register/step/2` | POST | Skills, languages, about. Send `draft_token` (body or header `X-Draft-Token`). |
| 3 | `/api/astrologer/register/step/3` | POST | Bank details. Send `draft_token`. |
| 4 | `/api/astrologer/register` | POST | Documents + terms. Send `draft_token` + step 4 fields only. |

**Step 1 – Basic info**
**POST** `/api/astrologer/register/step/1`
**Content-Type:** `multipart/form-data`

| Field | Type | Notes |
|-------|------|--------|
| profile_image | file | Required. Image jpg/png/webp/gif. Max 5MB. |
| first_name, last_name, email | string | Required. |
| phone_code, phone | string | Required. Phone unique. |
| gender | int | 1=Male, 2=Female, 3=Other. |
| dob | date | Y-m-d. |
| qualification, job_start_year | string / int | Experience in years. |
| working_with_other_app | int | 0 or 1. |
| address, landmark | string | |
| country_id, state_id, city_id | int | From register/options and cities. |
| pincode | string | 6 digits. |
| area | string | Optional. |
| instagram, facebook, youtube | url | Optional. |
| has_website | int | Optional. 0 or 1. |
| website_url | string | Required if has_website=1. |
| associate_with_temple | int | Optional. 0 or 1. |
| temple_name | string | Required if associate_with_temple=1. |

**Response (200):**
`{ "success": true, "message": "Step 1 saved.", "draft_token": "uuid-string" }`
Store `draft_token` and send it in Step 2, Step 3, and final Register.

**Step 2 – Skills**
**POST** `/api/astrologer/register/step/2`
**Content-Type:** `application/json` or `multipart/form-data`

| Field | Type | Notes |
|-------|------|--------|
| draft_token | string | Or send in header `X-Draft-Token`. |
| languages | array or JSON string | e.g. [1,2]. From register/options. |
| services | array or JSON string | Service ids from options. |
| any_healing | int | 0 or 1. |
| about | string | Min 10 chars. |

**Step 3 – Bank**
**POST** `/api/astrologer/register/step/3`

| Field | Type | Notes |
|-------|------|--------|
| draft_token | string | Or header `X-Draft-Token`. |
| bank_account_number, bank_account_number_confirm | string | Must match. |
| bank_account_name, bank_name, bank_ifsc_code | string | |
| bank_city, bank_state | string | Optional. |

**Step 4 – Submit (documents)**
**POST** `/api/astrologer/register`
Send **draft_token** (body or `X-Draft-Token` header) plus only Step 4 fields:

| Field | Type | Notes |
|-------|------|--------|
| draft_token | string | Or header `X-Draft-Token`. |
| aadhaar_number | string | 12 digits. |
| pan_card | string | 10 chars, e.g. ABCDE1234F. |
| aadhaar_front, aadhaar_back, pancard, cancel_cheque, astrology_certificate | file | Image or PDF. Max 5MB each. |
| terms_and_conditions | int | 1 = accepted. |
| referral_code | string | Optional. |

**Success (200):**
`{ "success": true, "message": "Registered successfully. Your application is under review.", "application_id": "..." }`

If `draft_token` is missing or expired, response is 422: complete steps 1–3 first.

### 4.5 Register (single-shot)
**POST** `/api/astrologer/register`
**Content-Type:** `multipart/form-data`

**Without** `draft_token`: send all registration data in one request. Phone should be verified first via `verify-astrologer-mobile-number` and `verify-astrologer-otp`.

**Form fields (required unless marked optional):**

| Field | Type | Notes |
|-------|------|--------|
| profile_image | file | Image: jpg, png, webp, gif. Max 5MB. |
| first_name, last_name | string | |
| email | string | Unique. |
| phone_code | string | e.g. 91. |
| phone | string | 10 digits, unique. Must be OTP-verified. |
| gender | int | 1=Male, 2=Female, 3=Other. |
| dob | date | Y-m-d. |
| qualification | string | e.g. MA, PhD. |
| job_start_year | int | Experience in years (0–50). |
| working_with_other_app | int | 0 or 1. |
| address, landmark | string | |
| country_id, state_id, city_id | int | From register/options (and cities). |
| pincode | string | 6 digits. |
| area | string | Optional. |
| instagram, facebook, youtube | url | Optional. |
| languages | array or JSON string | e.g. [1,2] or "[1,2]". Language ids from options. |
| services | array or JSON string | Service ids from options. |
| any_healing | int | 0 or 1. |
| about | string | |
| bank_account_number | string | |
| bank_account_number_confirm | string | Must match bank_account_number. |
| bank_account_name, bank_name | string | bank_name can come from IFSC lookup. |
| bank_ifsc_code | string | 11 chars. |
| bank_city, bank_state | string | Optional; from IFSC. |
| aadhaar_number | string | 12 digits. |
| pan_card | string | 10 chars, e.g. ABCDE1234F. |
| aadhaar_front, aadhaar_back | file | Image or PDF. Max 5MB each. |
| pancard | file | PAN document. Image or PDF. Max 5MB. |
| cancel_cheque | file | Image or PDF. Max 5MB. |
| astrology_certificate | file | Image or PDF. Max 5MB. |
| terms_and_conditions | int | 1 = accepted. |
| referral_code | string | Optional. |
| has_website | int | Optional. 0 or 1. |
| website_url | string | Required if has_website=1. |
| associate_with_temple | int | Optional. 0 or 1. |
| temple_name | string | Required if associate_with_temple=1. |

**Success (200):**
`{ "success": true, "message": "Registered successfully. Your application is under review.", "application_id": "MA-202603- A0001" }`

**Errors (422):**
`{ "errors": { "field_name": ["Error message"] } }`
Validation errors; use for inline form errors in Flutter.

---

### 4.6 Astrologer Earnings API (Flutter)

**GET** or **POST** `/api/astrologer/earnings`
**Auth:** Required. Send `api_token` in request body (POST) or `Authorization: Bearer <api_token>` (GET).

Returns the same data as the web page `/astrologer/earnings`: wallet summary and paginated transaction list.

**Query/body (optional):**
| Field | Type | Notes |
|-------|------|--------|
| api_token | string | Required if not using Bearer header. |
| per_page | int | Transactions per page (5–50, default 15). |
| page | int | Page number for pagination. |

**Response (200):**
```json
{
  "success": true,
  "data": {
    "summary": {
      "chat_balance": 0,
      "call_balance": 0,
      "video_balance": 0,
      "total_revenue": 0,
      "redeem_balance": 0,
      "available_balance": 0,
      "hold_amount": 0,
      "minimum_withdraw": 0,
      "maximum_withdraw": 0
    },
    "transitions": {
      "data": [
        {
          "id": 1,
          "remark": "Chat consultation",
          "amount": 100,
          "action": 1,
          "created_at": "2026-03-07 12:00:00"
        }
      ],
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 0,
      "next_page_url": null,
      "prev_page_url": null
    }
  }
}
```
- **action:** 1 = credit (incoming), 0 = debit (outgoing).
- **summary:** All amounts in rupees (float). `available_balance` = total_revenue − hold_amount.

**Error (401):**
`{ "success": false, "message": "Unauthorized. Invalid or missing api_token." }`

---

## 5. Postman

Import `AstroKavach-API.postman_collection.json` and set:

- **base_url:** your base URL (e.g. `https://your-domain.com`)
- **api_token:** user token for protected `/user/*` requests (obtain via register or OTP verify).
