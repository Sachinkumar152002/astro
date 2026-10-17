<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{ReferralWithdrawal, Astrologer};
use Illuminate\Support\Facades\DB;

class ReferralWithdrawalController extends Controller
{
    private $module = 'referral_withdrawals';
    private $moduleName = 'Withdrawal Requests';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'referral_withdrawals';
        $breads = [
            ['name' => $this->moduleName]
        ];
        return view('admin.referral-withdrawals.list', compact('nav', 'breads'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $results = ReferralWithdrawal::with('astrologer')
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($astrologer_name = $request->astrologer_name) {
            $results->whereHas('astrologer', function($q) use ($astrologer_name) {
                $q->where('firstname', 'like', '%' . $astrologer_name . '%')
                  ->orWhere('lastname', 'like', '%' . $astrologer_name . '%')
                  ->orWhere('display_name', 'like', '%' . $astrologer_name . '%');
            });
        }

        if($status = $request->status) {
            $results->where('status', $status);
        }

        if($wallet_type = $request->wallet_type) {
            $results->where('wallet_type', $wallet_type);
        }

        if($created_at_from = $request->created_at_from) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$created_at_from]);
        }

        if($created_at_to = $request->created_at_to) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$created_at_to]);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.referral-withdrawals.listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }

    public function approve(Request $request)
    {
        if(!adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $id = $request->id;
        $withdrawal = ReferralWithdrawal::findOrFail($id);

        try {
            DB::beginTransaction();
            $withdrawal->status = 'approved';
            $withdrawal->admin_notes = $request->admin_notes ?? $withdrawal->admin_notes;
            $withdrawal->save();
            DB::commit();

            return response()->json(['message' => 'Withdrawal request approved successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => ['An error occurred: ' . $e->getMessage()]]], 422);
        }
    }

    public function reject(Request $request)
    {
        if(!adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $id = $request->id;
        $withdrawal = ReferralWithdrawal::findOrFail($id);

        try {
            DB::beginTransaction();
            $withdrawal->status = 'rejected';
            $withdrawal->admin_notes = $request->admin_notes ?? $withdrawal->admin_notes;
            $withdrawal->save();
            DB::commit();

            return response()->json(['message' => 'Withdrawal request rejected successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => ['An error occurred: ' . $e->getMessage()]]], 422);
        }
    }

    public function process(Request $request)
    {
        if(!adminCan($this->module, 'update')) {
            return response()->json(['errors' => ['permission' => ['No Permission']]], 402);
        }

        $id = $request->id;
        $withdrawal = ReferralWithdrawal::findOrFail($id);

        if($withdrawal->status != 'approved') {
            return response()->json(['errors' => ['general' => ['Withdrawal must be approved before processing']]], 422);
        }

        try {
            DB::beginTransaction();
            $withdrawal->status = 'processed';
            $withdrawal->processed_at = now();
            $withdrawal->admin_notes = $request->admin_notes ?? $withdrawal->admin_notes;
            $withdrawal->save();
            DB::commit();

            return response()->json(['message' => 'Withdrawal processed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => ['An error occurred: ' . $e->getMessage()]]], 422);
        }
    }

    public function getAstrologerSuggestions(Request $request)
    {
        $query = $request->get('q', '');

        if(strlen($query) < 2) {
            return response()->json([]);
        }

        $astrologers = Astrologer::where(function($q) use ($query) {
                $q->where('firstname', 'like', '%' . $query . '%')
                  ->orWhere('lastname', 'like', '%' . $query . '%')
                  ->orWhere('display_name', 'like', '%' . $query . '%');
            })
            ->select('id', 'firstname', 'lastname', 'display_name')
            ->limit(10)
            ->get()
            ->map(function($astrologer) {
                return [
                    'id' => $astrologer->id,
                    'name' => $astrologer->display_name ?: ($astrologer->firstname . ' ' . $astrologer->lastname),
                    'display_name' => $astrologer->display_name,
                    'firstname' => $astrologer->firstname,
                    'lastname' => $astrologer->lastname
                ];
            });

        return response()->json($astrologers);
    }
}
