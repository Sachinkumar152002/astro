<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralSignupController extends Controller
{
    /**
     * We reuse referral_transactions permission for access,
     * so we don't need to introduce a new permission module.
     */
    private string $module = 'referral_transactions';
    private string $moduleName = 'Referral Signups (Users)';

    public function index(Request $request)
    {
        if (!adminCan($this->module, 'view')) return dd('No Permission');

        $nav = 'referral_signups';
        $breads = [
            ['name' => $this->moduleName]
        ];

        return view('admin.referral-signups.list', compact('nav', 'breads'));
    }

    public function all(Request $request)
    {
        if (!adminCan($this->module, 'view')) return dd('No Permission');

        $sortBy = $request->sort_by ?: 'u.created_at';
        $sortDir = $request->sort_dir ?: 'desc';
        $allowedSorts = [
            'u.id',
            'u.name',
            'u.email',
            'u.phone',
            'u.used_referral_code',
            'u.created_at',
        ];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'u.created_at';
        }
        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        $results = DB::table('users as u')
            ->leftJoin('users as ru', 'ru.referral_code', '=', 'u.used_referral_code')
            ->leftJoin('astrologers as ra', 'ra.referral_code', '=', 'u.used_referral_code')
            ->whereNotNull('u.used_referral_code')
            ->where('u.used_referral_code', '!=', '')
            ->select([
                'u.id',
                'u.name',
                'u.email',
                'u.phone',
                'u.phone_code',
                'u.used_referral_code',
                'u.created_at',
                DB::raw('ru.id as ref_user_id'),
                DB::raw('ru.name as ref_user_name'),
                DB::raw('ra.id as ref_astrologer_id'),
                DB::raw("COALESCE(NULLIF(ra.display_name,''), NULLIF(CONCAT(TRIM(COALESCE(ra.firstname,'')), ' ', TRIM(COALESCE(ra.lastname,''))), ''), NULL) as ref_astrologer_name"),
            ])
            ->orderBy($sortBy, $sortDir);

        if ($code = trim((string) $request->used_referral_code)) {
            $results->where('u.used_referral_code', $code);
        }

        if ($refType = $request->referrer_type) {
            if ($refType === 'user') {
                $results->whereNotNull('ru.id');
            } elseif ($refType === 'astrologer') {
                $results->whereNotNull('ra.id');
            }
        }

        if ($q = trim((string) $request->q)) {
            $results->where(function ($w) use ($q) {
                $w->where('u.name', 'like', "%{$q}%")
                    ->orWhere('u.email', 'like', "%{$q}%")
                    ->orWhere('u.phone', 'like', "%{$q}%")
                    ->orWhere('u.used_referral_code', 'like', "%{$q}%");
            });
        }

        if ($createdFrom = $request->created_at_from) {
            $results->whereRaw("DATE_FORMAT(u.created_at, '%Y-%m-%d') >= ?", [$createdFrom]);
        }

        if ($createdTo = $request->created_at_to) {
            $results->whereRaw("DATE_FORMAT(u.created_at, '%Y-%m-%d') <= ?", [$createdTo]);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.referral-signups.listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }
}
