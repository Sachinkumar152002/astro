<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'rzrresponse',
        'mcuberesponse',
        'lp/form/submit',
        '/verify-payment/*/*',
        '/call/*/status-callback',
        '/account/rzrresponse/*/*',
        '/astrologer/rzrresponse/*/*',
        '/user/rzrresponse/*/*',
        '/verify-urgent-payment-razorpay/*/*/*/*/*',
    ];
}
