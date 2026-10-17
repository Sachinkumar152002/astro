<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting;

class SocialController extends Controller
{
    /**
     * Return social media links configured in admin settings.
     *
     * GET/POST: /api/social-links
     *
     * Response:
     * {
     *   "success": true,
     *   "data": [
     *     { "key": "facebook", "label": "Facebook", "url": "...", "icon": "facebook" },
     *     { "key": "twitter", "label": "Twitter", "url": "...", "icon": "twitter" },
     *     { "key": "instagram", "label": "Instagram", "url": "...", "icon": "instagram" },
     *     { "key": "linkedin", "label": "LinkedIn", "url": "...", "icon": "linkedin" },
     *     { "key": "youtube", "label": "YouTube", "url": "...", "icon": "youtube" }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        // Keys used in admin settings social tab
        $map = [
            'fb_link'        => ['key' => 'facebook',  'label' => 'Facebook',  'icon' => 'facebook'],
            'tw_link'        => ['key' => 'twitter',   'label' => 'Twitter',   'icon' => 'twitter'],
            'instagram_link' => ['key' => 'instagram', 'label' => 'Instagram', 'icon' => 'instagram'],
            'linkedin_link'  => ['key' => 'linkedin',  'label' => 'LinkedIn',  'icon' => 'linkedin'],
            'youtube_link'   => ['key' => 'youtube',   'label' => 'YouTube',   'icon' => 'youtube'],
        ];

        $settings = Setting::whereIn('key', array_keys($map))->get();

        $data = [];
        foreach ($settings as $setting) {
            $meta = $map[$setting->key] ?? null;
            $url = trim($setting->value ?? '');
            if (!$meta || $url === '') {
                continue;
            }

            $data[] = [
                'key'   => $meta['key'],
                'label' => $setting->name ?: $meta['label'],
                'url'   => $url,
                'icon'  => $meta['icon'],
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 200);
    }
}
