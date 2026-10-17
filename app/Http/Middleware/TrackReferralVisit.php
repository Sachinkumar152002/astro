<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\ReferralVisit;

class TrackReferralVisit
{
    private const VISITOR_COOKIE = 'ref_visitor_id';
    private const SESSION_KEY = 'referral_code';

    public function handle(Request $request, Closure $next)
    {
        $referralCode = $this->extractReferralCode($request);

        $visitorId = $request->cookie(self::VISITOR_COOKIE);
        $shouldQueueCookie = false;
        if(!$visitorId) {
            $visitorId = (string) Str::uuid();
            $shouldQueueCookie = true;
        }

        if($referralCode) {
            // Persist referral code in session for later signup forms if needed
            try {
                $request->session()->put(self::SESSION_KEY, $referralCode);
            } catch (\Throwable $e) {
                // session may not be available in some contexts; ignore
            }

            $visitedOn = now()->toDateString();

            // Create one visit per (referral_code, visitor_id, date)
            ReferralVisit::firstOrCreate(
                [
                    'referral_code' => $referralCode,
                    'visitor_id' => $visitorId,
                    'visited_on' => $visitedOn,
                ],
                [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'landing_url' => $request->fullUrl(),
                    'referrer_url' => $request->headers->get('referer'),
                ]
            );
        }

        $response = $next($request);

        if($shouldQueueCookie) {
            // 2 years
            $minutes = 60 * 24 * 365 * 2;
            $response->headers->setCookie(cookie(self::VISITOR_COOKIE, $visitorId, $minutes));
        }

        return $response;
    }

    private function extractReferralCode(Request $request): ?string
    {
        // Primary: ?ref=CODE (used in several places already)
        $code = $request->query('ref');
        if(!$code) {
            // Alternate: ?referral_code=CODE
            $code = $request->query('referral_code');
        }

        if(!$code) return null;

        $code = trim((string) $code);
        if($code === '') return null;

        // Basic safety: keep it simple, max 20 like DB columns
        $code = substr($code, 0, 20);

        return $code;
    }
}
