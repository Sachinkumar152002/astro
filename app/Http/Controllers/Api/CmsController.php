<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cms;
use Carbon\Carbon;

class CmsController extends Controller
{
    /**
     * List CMS pages allowed for the given app type.
     * POST api/pages/list with app_type=user|customer
     */
    public function list(Request $request)
    {
        $query = Cms::where('status', 1)->where('hide_from_list', 0);

        if (\Schema::hasColumn('cms', 'display_in_user_app') && \Schema::hasColumn('cms', 'display_in_customer_app')) {
            $appType = $request->input('app_type', 'user');
            $column = $appType === 'customer' ? 'display_in_customer_app' : 'display_in_user_app';
            $query->where($column, 1);
        }

        $pages = $query->orderBy('name')->get(['id', 'name', 'slug', 'short_description']);

        $data = $pages->map(function ($page) {
            return [
                'id' => $page->id,
                'title' => $page->name,
                'slug' => $page->slug,
                'short_description' => $page->short_description,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Pages list',
            'data' => $data,
        ], 200);
    }

    /**
     * Get single page content by slug.
     * POST api/pages/detail with slug=page-slug
     * Returns title, body (HTML with absolute URLs for images/videos), responsive-ready for native render.
     * List API controls which pages appear in app menu; detail returns any published page by slug so app always gets data.
     */
    public function detail(Request $request)
    {
        $slug = $request->input('slug');

        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug is required',
            ], 422);
        }

        $page = Cms::where('slug', $slug)->where('status', 1)->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }

        $body = $this->makeContentUrlsAbsolute($page->content);

        $data = [
            'id' => $page->id,
            'title' => $page->name,
            'slug' => $page->slug,
            'body' => $body,
            'content' => $body, // alias for app compatibility
            'short_description' => $page->short_description,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'updated_at' => $page->updated_at ? Carbon::parse($page->updated_at)->format('Y-m-d H:i:s') : null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Page detail',
            'data' => $data,
        ], 200);
    }

    /**
     * Convert relative URLs in HTML content to absolute URLs so the app can load
     * images/videos without webview. Also adds responsive styling for img/video.
     */
    protected function makeContentUrlsAbsolute(string $html): string
    {
        $base = rtrim(config('app.url'), '/');

        if (empty($html)) {
            return $html;
        }

        // Make src and href absolute: "/media/..." or "/storage/..." or "media/..."
        $html = preg_replace_callback(
            '/(<(?:img|video|audio|source)\s[^>]*\ssrc=)(["\'])([^"\']+)(["\'])/i',
            function ($m) use ($base) {
                $url = $m[3];
                if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
                    return $m[0];
                }
                $url = $url[0] === '/' ? $url : '/' . $url;
                return $m[1] . $m[2] . $base . $url . $m[4];
            },
            $html
        );

        // href on <a> tags that point to same-origin assets
        $html = preg_replace_callback(
            '/(<a\s[^>]*\shref=)(["\'])([^"\']+)(["\'])/i',
            function ($m) use ($base) {
                $url = $m[3];
                if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, '#') === 0 || strpos($url, 'mailto:') === 0) {
                    return $m[0];
                }
                $url = $url[0] === '/' ? $url : '/' . $url;
                return $m[1] . $m[2] . $base . $url . $m[4];
            },
            $html
        );

        // Add responsive style to img tags when no style present (for native app render)
        $html = preg_replace_callback(
            '/<img([^>]*)>/i',
            function ($m) {
                $attrs = $m[1];
                if (stripos($attrs, 'style=') === false) {
                    $attrs .= ' style="max-width:100%;height:auto;"';
                }
                return '<img' . $attrs . '>';
            },
            $html
        );

        // Add responsive style to video tags when no style present
        $html = preg_replace_callback(
            '/<video([^>]*)>/i',
            function ($m) {
                $attrs = $m[1];
                if (stripos($attrs, 'style=') === false) {
                    $attrs .= ' style="max-width:100%;height:auto;"';
                }
                return '<video' . $attrs . '>';
            },
            $html
        );

        return $html;
    }
}
