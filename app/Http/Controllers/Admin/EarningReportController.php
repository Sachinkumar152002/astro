<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Order, OrderItem, User};
use App\Model\{Astrologer, AstrologerAppointment, WalletTransition, Withdraw, PujaAstrologerAssignment, CommissionTransaction};
use PDF;
use DB;

class EarningReportController extends Controller
{
    /**
     * Astrologer Report Index Page
     */
    public function astrologerReport(Request $request)
    {
        if(!adminCan('order', 'view')) return dd('No Permission');

        $nav = 'earning-reports-astrologer';
        $breads = [
            ['name' => 'Earning Reports', 'url' => ''],
            ['name' => 'Astrologer Report']
        ];

        return view('admin.earning-reports.astrologer', compact('nav', 'breads'));
    }

    /**
     * Astrologer Report List (AJAX)
     */
    public function astrologerReportList(Request $request)
    {
        if(!adminCan('order', 'view')) {
            return response()->json(['error' => 'No Permission'], 403);
        }

        // Base query for astrologer transactions
        // Get transactions from WalletTransition (consultations, puja commissions, withdrawals)
        $query = DB::table('wallet_transitions as wt')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('wt.user_id', '=', 'a.id')
                     ->where('wt.user_type', '=', 'astrologer');
            })
            ->leftJoin('astrologer_appointments as aa', function($join) {
                $join->on('wt.table_id', '=', 'aa.id')
                     ->where('wt.table_name', '=', 'astrologer_appointments');
            })
            ->leftJoin('puja_astrologer_assignments as paa', function($join) {
                $join->on('wt.table_id', '=', 'paa.id')
                     ->where('wt.table_name', '=', 'puja_astrologer_assignments');
            })
            ->leftJoin('products as p', function($join) {
                $join->on('paa.product_id', '=', 'p.id');
            })
            ->where('wt.user_type', 'astrologer')
            ->where('wt.action', 1) // credit transactions
            ->where('wt.status', 1) // successful transactions
            ->select(
                'wt.id',
                'wt.user_id',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'wt.table_name',
                'wt.remark',
                'wt.amount',
                'wt.created_at',
                'aa.package as consultation_type',
                DB::raw("CASE
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 2 THEN 'Chat Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 3 THEN 'Call Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 1 THEN 'Video Consultation'
                    WHEN wt.table_name = 'puja_astrologer_assignments' THEN
                        CASE
                            WHEN p.is_one_to_one_puja = 1 THEN 'One-to-One Puja Commission'
                            ELSE 'Group Puja Commission'
                        END
                    WHEN wt.table_name = 'withdraws' THEN 'Withdrawal'
                    ELSE 'Other'
                END as service_type")
            );

        // Filters
        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->service_type) {
            if($request->service_type == 'Chat Consultation') {
                $query->where('aa.package', 2);
            } elseif($request->service_type == 'Call Consultation') {
                $query->where('aa.package', 3);
            } elseif($request->service_type == 'Video Consultation') {
                $query->where('aa.package', 1);
            } elseif($request->service_type == 'Puja Commission' || $request->service_type == 'One-to-One Puja Commission' || $request->service_type == 'Group Puja Commission') {
                $query->where('wt.table_name', 'puja_astrologer_assignments');
                if($request->service_type == 'One-to-One Puja Commission') {
                    $query->where('p.is_one_to_one_puja', 1);
                } elseif($request->service_type == 'Group Puja Commission') {
                    $query->where(function($q) {
                        $q->where('p.is_one_to_one_puja', '!=', 1)
                          ->orWhereNull('p.is_one_to_one_puja');
                    });
                }
            } elseif($request->service_type == 'Withdrawal') {
                $query->where('wt.table_name', 'withdraws');
            }
        }

        if($request->date_from) {
            $query->whereDate('wt.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('wt.created_at', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?: 'wt.created_at';
        $sortDir = $request->sort_dir ?: 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $perPage = $request->per_page ?: 10;
        $results = $query->paginate($perPage);

        $data = [
            'rows' => view('admin.earning-reports.astrologer-listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }

    /**
     * User Report Index Page
     */
    public function userReport(Request $request)
    {
        if(!adminCan('order', 'view')) return dd('No Permission');

        $nav = 'earning-reports-user';
        $breads = [
            ['name' => 'Earning Reports', 'url' => ''],
            ['name' => 'User Report']
        ];

        return view('admin.earning-reports.user', compact('nav', 'breads'));
    }

    /**
     * User Report List (AJAX)
     */
    public function userReportList(Request $request)
    {
        if(!adminCan('order', 'view')) {
            return response()->json(['error' => 'No Permission'], 403);
        }

        // Base query for user orders
        $query = OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'Success')
                  ->where('status', 'Success');
            })
            ->where('type', 'shop');

        // Filters
        if($request->customer_name) {
            $query->whereHas('order.user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->customer_name}%")
                  ->orWhere('email', 'like', "%{$request->customer_name}%");
            });
        }

        if($request->order_item) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->order_item}%");
            });
        }

        if($request->status) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if($request->date_from) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if($request->date_to) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        // Apply sorting
        $sortBy = $request->sort_by ?: 'created_at';
        $sortDir = $request->sort_dir ?: 'desc';

        if($sortBy == 'customer_name') {
            $query->join('orders', 'order_items.order_id', '=', 'orders.id')
                  ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                  ->select('order_items.*')
                  ->orderBy('users.name', $sortDir);
        } elseif($sortBy == 'created_at') {
            $query->orderBy('order_items.created_at', $sortDir);
        } else {
            $query->orderBy('order_items.' . $sortBy, $sortDir);
        }

        // Paginate - 15 records per page for user report
        $perPage = $request->per_page ?: 15;
        $results = $query->paginate($perPage);

        $data = [
            'rows' => view('admin.earning-reports.user-listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];

        return response()->json($data, 200);
    }

    /**
     * Astrologer Earnings Report Index Page
     */
    public function astrologerEarningsReport(Request $request)
    {
        if(!adminCan('order', 'view')) return dd('No Permission');

        $nav = 'earning-reports-astrologer-earnings';
        $breads = [
            ['name' => 'Earning Reports', 'url' => ''],
            ['name' => 'Astrologer Earnings Report']
        ];

        return view('admin.earning-reports.astrologer-earnings', compact('nav', 'breads'));
    }

    /**
     * Astrologer Earnings Report List (AJAX)
     */
    public function astrologerEarningsReportList(Request $request)
    {
        if(!adminCan('order', 'view')) {
            return response()->json(['error' => 'No Permission'], 403);
        }

        // Base query for astrologer earnings with order details
        $query = DB::table('wallet_transitions as wt')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('wt.user_id', '=', 'a.id')
                     ->where('wt.user_type', '=', 'astrologer');
            })
            ->leftJoin('astrologer_appointments as aa', function($join) {
                $join->on('wt.table_id', '=', 'aa.id')
                     ->where('wt.table_name', '=', 'astrologer_appointments');
            })
            ->leftJoin('puja_astrologer_assignments as paa', function($join) {
                $join->on('wt.table_id', '=', 'paa.id')
                     ->where('wt.table_name', '=', 'puja_astrologer_assignments');
            })
            ->leftJoin('products as p', function($join) {
                $join->on('paa.product_id', '=', 'p.id');
            })
            ->leftJoin('order_items as oi', function($join) {
                $join->on('paa.order_item_id', '=', 'oi.id');
            })
            ->leftJoin('orders as o', function($join) {
                $join->on('oi.order_id', '=', 'o.id');
            })
            ->where('wt.user_type', 'astrologer')
            ->where('wt.action', 1) // credit transactions
            ->where('wt.status', 1) // successful transactions
            ->select(
                'wt.id',
                'wt.user_id',
                'wt.table_id',
                'wt.table_name',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'wt.table_name',
                'wt.remark',
                'wt.amount as astrologer_revenue',
                'wt.created_at',
                'aa.package as consultation_type',
                'o.order_number',
                'oi.id as order_item_id',
                DB::raw("CASE
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 2 THEN 'Chat Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 3 THEN 'Call Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 1 THEN 'Video Consultation'
                    WHEN wt.table_name = 'puja_astrologer_assignments' THEN
                        CASE
                            WHEN p.is_one_to_one_puja = 1 THEN 'One-to-One Puja Commission'
                            ELSE 'Group Puja Commission'
                        END
                    ELSE 'Other'
                END as service_type")
            );

        // Filters
        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->service_type) {
            if($request->service_type == 'Chat Consultation') {
                $query->where('aa.package', 2);
            } elseif($request->service_type == 'Call Consultation') {
                $query->where('aa.package', 3);
            } elseif($request->service_type == 'Video Consultation') {
                $query->where('aa.package', 1);
            } elseif($request->service_type == 'Puja Commission' || $request->service_type == 'One-to-One Puja Commission' || $request->service_type == 'Group Puja Commission') {
                $query->where('wt.table_name', 'puja_astrologer_assignments');
                if($request->service_type == 'One-to-One Puja Commission') {
                    $query->where('p.is_one_to_one_puja', 1);
                } elseif($request->service_type == 'Group Puja Commission') {
                    $query->where(function($q) {
                        $q->where('p.is_one_to_one_puja', '!=', 1)
                          ->orWhereNull('p.is_one_to_one_puja');
                    });
                }
            }
        }

        if($request->order_number) {
            $query->where('o.order_number', 'like', "%{$request->order_number}%");
        }

        if($request->date_from) {
            $query->whereDate('wt.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('wt.created_at', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?: 'wt.created_at';
        $sortDir = $request->sort_dir ?: 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $perPage = $request->per_page ?: 10;
        $results = $query->paginate($perPage);

        $data = [
            'rows' => view('admin.earning-reports.astrologer-earnings-listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];

        return response()->json($data, 200);
    }

    /**
     * Astrologer Withdrawals Report Index Page
     */
    public function astrologerWithdrawalsReport(Request $request)
    {
        if(!adminCan('order', 'view')) return dd('No Permission');

        $nav = 'earning-reports-astrologer-withdrawals';
        $breads = [
            ['name' => 'Earning Reports', 'url' => ''],
            ['name' => 'Astrologer Withdrawals Report']
        ];

        return view('admin.earning-reports.astrologer-withdrawals', compact('nav', 'breads'));
    }

    /**
     * Astrologer Withdrawals Report List (AJAX)
     */
    public function astrologerWithdrawalsReportList(Request $request)
    {
        if(!adminCan('order', 'view')) {
            return response()->json(['error' => 'No Permission'], 403);
        }

        // Base query for astrologer withdrawals
        $query = DB::table('withdraws as w')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('w.user_id', '=', 'a.id')
                     ->where('w.user_type', '=', 'astrologer');
            })
            ->leftJoin('wallet_transitions as wt', function($join) {
                $join->on('wt.table_id', '=', 'w.id')
                     ->where('wt.table_name', '=', 'withdraws')
                     ->where('wt.user_type', '=', 'astrologer');
            })
            ->where('w.user_type', 'astrologer')
            ->select(
                'wt.id',
                'w.id as withdraw_id',
                'w.user_id',
                'w.amount as request_amount',
                'w.status',
                'w.created_at',
                'w.updated_at',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'a.wallet as wallet_balance',
                DB::raw("(SELECT COALESCE(SUM(amount), 0) FROM wallet_transitions WHERE user_id = w.user_id AND user_type = 'astrologer' AND action = 1 AND status = 1) -
                         (SELECT COALESCE(SUM(amount), 0) FROM withdraws WHERE user_id = w.user_id AND user_type = 'astrologer' AND status = 'done') as available_balance")
            );

        // Filters
        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->status) {
            $query->where('w.status', $request->status);
        }

        if($request->date_from) {
            $query->whereDate('w.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('w.created_at', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?: 'w.created_at';
        $sortDir = $request->sort_dir ?: 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $perPage = $request->per_page ?: 10;
        $results = $query->paginate($perPage);

        $data = [
            'rows' => view('admin.earning-reports.astrologer-withdrawals-listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];

        return response()->json($data, 200);
    }

    /**
     * Download Astrologer Report as PDF
     */
    public function astrologerReportPdf(Request $request)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        $query = DB::table('wallet_transitions as wt')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('wt.user_id', '=', 'a.id')
                     ->where('wt.user_type', '=', 'astrologer');
            })
            ->leftJoin('astrologer_appointments as aa', function($join) {
                $join->on('wt.table_id', '=', 'aa.id')
                     ->where('wt.table_name', '=', 'astrologer_appointments');
            })
            ->leftJoin('puja_astrologer_assignments as paa', function($join) {
                $join->on('wt.table_id', '=', 'paa.id')
                     ->where('wt.table_name', '=', 'puja_astrologer_assignments');
            })
            ->leftJoin('products as p', function($join) {
                $join->on('paa.product_id', '=', 'p.id');
            })
            ->where('wt.user_type', 'astrologer')
            ->where('wt.action', 1)
            ->where('wt.status', 1)
            ->select(
                'wt.id',
                'wt.user_id',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'wt.table_name',
                'wt.remark',
                'wt.amount',
                'wt.created_at',
                'aa.package as consultation_type',
                DB::raw("CASE
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 2 THEN 'Chat Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 3 THEN 'Call Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 1 THEN 'Video Consultation'
                    WHEN wt.table_name = 'puja_astrologer_assignments' THEN
                        CASE
                            WHEN p.is_one_to_one_puja = 1 THEN 'One-to-One Puja Commission'
                            ELSE 'Group Puja Commission'
                        END
                    WHEN wt.table_name = 'withdraws' THEN 'Withdrawal'
                    ELSE 'Other'
                END as service_type")
            );

        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->service_type) {
            if($request->service_type == 'Chat Consultation') {
                $query->where('aa.package', 2);
            } elseif($request->service_type == 'Call Consultation') {
                $query->where('aa.package', 3);
            } elseif($request->service_type == 'Video Consultation') {
                $query->where('aa.package', 1);
            } elseif($request->service_type == 'Puja Commission' || $request->service_type == 'One-to-One Puja Commission' || $request->service_type == 'Group Puja Commission') {
                $query->where('wt.table_name', 'puja_astrologer_assignments');
                if($request->service_type == 'One-to-One Puja Commission') {
                    $query->where('p.is_one_to_one_puja', 1);
                } elseif($request->service_type == 'Group Puja Commission') {
                    $query->where(function($q) {
                        $q->where('p.is_one_to_one_puja', '!=', 1)
                          ->orWhereNull('p.is_one_to_one_puja');
                    });
                }
            } elseif($request->service_type == 'Withdrawal') {
                $query->where('wt.table_name', 'withdraws');
            }
        }

        if($request->date_from) {
            $query->whereDate('wt.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('wt.created_at', '<=', $request->date_to);
        }

        $query->orderBy('wt.created_at', 'desc');
        $results = $query->get();

        $pdf = PDF::loadView('admin.earning-reports.astrologer-pdf', [
            'results' => $results,
            'filters' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->download('astrologer-report-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Download Astrologer Earnings Report as PDF
     */
    public function astrologerEarningsReportPdf(Request $request)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        $query = DB::table('wallet_transitions as wt')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('wt.user_id', '=', 'a.id')
                     ->where('wt.user_type', '=', 'astrologer');
            })
            ->leftJoin('astrologer_appointments as aa', function($join) {
                $join->on('wt.table_id', '=', 'aa.id')
                     ->where('wt.table_name', '=', 'astrologer_appointments');
            })
            ->leftJoin('puja_astrologer_assignments as paa', function($join) {
                $join->on('wt.table_id', '=', 'paa.id')
                     ->where('wt.table_name', '=', 'puja_astrologer_assignments');
            })
            ->leftJoin('products as p', function($join) {
                $join->on('paa.product_id', '=', 'p.id');
            })
            ->leftJoin('order_items as oi', function($join) {
                $join->on('paa.order_item_id', '=', 'oi.id');
            })
            ->leftJoin('orders as o', function($join) {
                $join->on('oi.order_id', '=', 'o.id');
            })
            ->where('wt.user_type', 'astrologer')
            ->where('wt.action', 1)
            ->where('wt.status', 1)
            ->select(
                'wt.id',
                'wt.user_id',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'wt.table_name',
                'wt.remark',
                'wt.amount as astrologer_revenue',
                'wt.created_at',
                'aa.package as consultation_type',
                'o.order_number',
                DB::raw("CASE
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 2 THEN 'Chat Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 3 THEN 'Call Consultation'
                    WHEN wt.table_name = 'astrologer_appointments' AND aa.package = 1 THEN 'Video Consultation'
                    WHEN wt.table_name = 'puja_astrologer_assignments' THEN
                        CASE
                            WHEN p.is_one_to_one_puja = 1 THEN 'One-to-One Puja Commission'
                            ELSE 'Group Puja Commission'
                        END
                    ELSE 'Other'
                END as service_type")
            );

        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->service_type) {
            if($request->service_type == 'Chat Consultation') {
                $query->where('aa.package', 2);
            } elseif($request->service_type == 'Call Consultation') {
                $query->where('aa.package', 3);
            } elseif($request->service_type == 'Video Consultation') {
                $query->where('aa.package', 1);
            } elseif($request->service_type == 'Puja Commission' || $request->service_type == 'One-to-One Puja Commission' || $request->service_type == 'Group Puja Commission') {
                $query->where('wt.table_name', 'puja_astrologer_assignments');
                if($request->service_type == 'One-to-One Puja Commission') {
                    $query->where('p.is_one_to_one_puja', 1);
                } elseif($request->service_type == 'Group Puja Commission') {
                    $query->where(function($q) {
                        $q->where('p.is_one_to_one_puja', '!=', 1)
                          ->orWhereNull('p.is_one_to_one_puja');
                    });
                }
            }
        }

        if($request->order_number) {
            $query->where('o.order_number', 'like', "%{$request->order_number}%");
        }

        if($request->date_from) {
            $query->whereDate('wt.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('wt.created_at', '<=', $request->date_to);
        }

        $query->orderBy('wt.created_at', 'desc');
        $results = $query->get();

        $pdf = PDF::loadView('admin.earning-reports.astrologer-earnings-pdf', [
            'results' => $results,
            'filters' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->download('astrologer-earnings-report-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Download Astrologer Withdrawals Report as PDF
     */
    public function astrologerWithdrawalsReportPdf(Request $request)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        $query = DB::table('withdraws as w')
            ->leftJoin('astrologers as a', function($join) {
                $join->on('w.user_id', '=', 'a.id')
                     ->where('w.user_type', '=', 'astrologer');
            })
            ->where('w.user_type', 'astrologer')
            ->select(
                'w.id',
                'w.user_id',
                'w.amount as request_amount',
                'w.status',
                'w.created_at',
                'a.display_name as astrologer_name',
                'a.firstname',
                'a.lastname',
                'a.wallet as wallet_balance',
                DB::raw("(SELECT COALESCE(SUM(amount), 0) FROM wallet_transitions WHERE user_id = w.user_id AND user_type = 'astrologer' AND action = 1 AND status = 1) -
                         (SELECT COALESCE(SUM(amount), 0) FROM withdraws WHERE user_id = w.user_id AND user_type = 'astrologer' AND status = 'done') as available_balance")
            );

        if($request->astrologer_name) {
            $query->where(function($q) use ($request) {
                $q->where('a.display_name', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.firstname', 'like', "%{$request->astrologer_name}%")
                  ->orWhere('a.lastname', 'like', "%{$request->astrologer_name}%");
            });
        }

        if($request->status) {
            $query->where('w.status', $request->status);
        }

        if($request->date_from) {
            $query->whereDate('w.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('w.created_at', '<=', $request->date_to);
        }

        $query->orderBy('w.created_at', 'desc');
        $results = $query->get();

        $pdf = PDF::loadView('admin.earning-reports.astrologer-withdrawals-pdf', [
            'results' => $results,
            'filters' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->download('astrologer-withdrawals-report-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Download User Report as PDF
     */
    public function userReportPdf(Request $request)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        $query = OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'Success')
                  ->where('status', 'Success');
            })
            ->where('type', 'shop');

        if($request->customer_name) {
            $query->whereHas('order.user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->customer_name}%")
                  ->orWhere('email', 'like', "%{$request->customer_name}%");
            });
        }

        if($request->order_item) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->order_item}%");
            });
        }

        if($request->status) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if($request->date_from) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if($request->date_to) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        $query->orderBy('created_at', 'desc');
        $results = $query->get();

        $pdf = PDF::loadView('admin.earning-reports.user-pdf', [
            'results' => $results,
            'filters' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->download('user-report-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * View Transaction Detail
     */
    public function viewDetail(Request $request, $id)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        // Check if it's a commission ID (for influencer report) - format: commission_123
        if(strpos($id, 'commission_') === 0) {
            $commissionId = str_replace('commission_', '', $id);

            // Use direct query like in listing to ensure we get the name correctly
            $result = DB::table('commission_transactions as ct')
                ->leftJoin('users as u', function($join) {
                    $join->on('ct.referrer_id', '=', 'u.id')
                         ->where('ct.referrer_type', '=', 'user')
                         ->where('u.is_influencer', '=', 1);
                })
                ->leftJoin('orders as o', 'ct.order_id', '=', 'o.id')
                ->leftJoin('order_items as oi', 'ct.order_item_id', '=', 'oi.id')
                ->leftJoin('products as p', 'oi.product_id', '=', 'p.id')
                ->where('ct.id', $commissionId)
                ->where('ct.referrer_type', 'user')
                ->where('ct.status', 'credited')
                ->where('u.is_influencer', 1)
                ->select(
                    'ct.*',
                    'u.name as influencer_name',
                    'u.email as influencer_email',
                    'p.title as product_name',
                    'o.order_number',
                    'o.created_at as purchase_date',
                    'p.id as product_id'
                )
                ->first();

            if($result) {
                // Get the commission model for relationships
                $commission = CommissionTransaction::with(['referrer', 'order', 'orderItem.product'])
                    ->where('id', $commissionId)
                    ->first();

                $detailData = [];
                $detailData['commission'] = $commission;
                $detailData['influencer'] = $commission ? $commission->referrer : null;
                $detailData['influencer_name'] = $result->influencer_name ?? 'N/A';
                $detailData['order'] = $commission ? $commission->order : null;
                $detailData['order_item'] = $commission ? $commission->orderItem : null;
                $detailData['product'] = $commission && $commission->orderItem ? $commission->orderItem->product : null;

                $nav = 'earning-reports';
                $breads = [
                    ['name' => 'Earning Reports', 'url' => route('admin.earning-reports.influencer')],
                    ['name' => 'Transaction Detail']
                ];

                $user = auth()->guard('web')->user();

                return view('admin.earning-reports.view-detail', compact('detailData', 'nav', 'breads', 'user'));
            } else {
                abort(404, 'Transaction not found');
            }
        }

        // Check if it's a withdraw ID (for withdrawals report) - format: withdraw_123
        if(strpos($id, 'withdraw_') === 0) {
            $withdrawId = str_replace('withdraw_', '', $id);
            $withdraw = Withdraw::find($withdrawId);

            if($withdraw) {
                // Get astrologer info
                $astrologer = Astrologer::find($withdraw->user_id);

                // Calculate available balance
                $availableBalance = DB::table('wallet_transitions')
                    ->where('user_id', $withdraw->user_id)
                    ->where('user_type', 'astrologer')
                    ->where('action', 1)
                    ->where('status', 1)
                    ->sum('amount') -
                    DB::table('withdraws')
                    ->where('user_id', $withdraw->user_id)
                    ->where('user_type', 'astrologer')
                    ->where('status', 'done')
                    ->sum('amount');

                $detailData = [];
                $detailData['withdraw'] = $withdraw;
                $detailData['astrologer'] = $astrologer;
                $detailData['available_balance'] = $availableBalance ?? 0;
                $detailData['wallet_balance'] = $astrologer->wallet ?? 0;

               $nav = 'earning-reports';
               $breads = [
                   ['name' => 'Earning Reports', 'url' => route('admin.earning-reports.astrologer-withdrawals')],
                   ['name' => 'Transaction Detail']
               ];

               $user = auth()->guard('web')->user();

               return view('admin.earning-reports.view-detail', compact('detailData', 'nav', 'breads', 'user'));
            } else {
                abort(404, 'Transaction not found');
            }
        }

        // Get wallet transition record
        $walletTransition = WalletTransition::find($id);

        if(!$walletTransition) {
            abort(404, 'Transaction not found');
        }

        $detailData = [];
        $detailData['wallet_transition'] = $walletTransition;

        // Get related data based on table_name
        if($walletTransition->table_name == 'astrologer_appointments') {
            $appointment = AstrologerAppointment::find($walletTransition->table_id);
            if($appointment) {
                $detailData['appointment'] = $appointment;
                $detailData['user'] = $appointment->user;
                $detailData['astrologer'] = $appointment->astrologer;
            }
        } elseif($walletTransition->table_name == 'puja_astrologer_assignments') {
            $assignment = PujaAstrologerAssignment::find($walletTransition->table_id);
            if($assignment) {
                $detailData['assignment'] = $assignment;
                $detailData['product'] = $assignment->product;
                $detailData['order'] = $assignment->order;
                $detailData['order_item'] = $assignment->orderItem;
                $detailData['astrologer'] = $assignment->astrologer;
            }
        } elseif($walletTransition->table_name == 'withdraws') {
            $withdraw = Withdraw::find($walletTransition->table_id);
            if($withdraw) {
                $detailData['withdraw'] = $withdraw;
            }
        }

        // Get astrologer/user info
        if($walletTransition->user_type == 'astrologer') {
            $detailData['account_holder'] = Astrologer::find($walletTransition->user_id);
        } else {
            $detailData['account_holder'] = User::find($walletTransition->user_id);
        }

           $nav = 'earning-reports';
           $breads = [
               ['name' => 'Earning Reports', 'url' => route('admin.earning-reports.astrologer')],
               ['name' => 'Transaction Detail']
           ];

           $user = auth()->guard('web')->user();

           return view('admin.earning-reports.view-detail', compact('detailData', 'nav', 'breads', 'user'));
    }

    /**
     * Influencer Report Index Page
     */
    public function influencerReport(Request $request)
    {
        if(!adminCan('order', 'view')) return dd('No Permission');

        $nav = 'earning-reports-influencer';
        $breads = [
            ['name' => 'Earning Reports', 'url' => ''],
            ['name' => 'Influencer Report']
        ];

        return view('admin.earning-reports.influencer', compact('nav', 'breads'));
    }

    /**
     * Influencer Report List (AJAX)
     */
    public function influencerReportList(Request $request)
    {
        if(!adminCan('order', 'view')) {
            return response()->json(['error' => 'No Permission'], 403);
        }

        // Get commission transactions for influencers (users with is_influencer = 1)
        $query = DB::table('commission_transactions as ct')
            ->leftJoin('users as u', function($join) {
                $join->on('ct.referrer_id', '=', 'u.id')
                     ->where('ct.referrer_type', '=', 'user')
                     ->where('u.is_influencer', '=', 1);
            })
            ->leftJoin('orders as o', 'ct.order_id', '=', 'o.id')
            ->leftJoin('order_items as oi', 'ct.order_item_id', '=', 'oi.id')
            ->leftJoin('products as p', 'oi.product_id', '=', 'p.id')
            ->where('ct.referrer_type', 'user')
            ->where('ct.status', 'credited')
            ->where('u.is_influencer', 1)
            ->select(
                'ct.id',
                'u.name as influencer_name',
                'u.email as influencer_email',
                'p.title as product_name',
                'o.order_number',
                'o.created_at as purchase_date',
                'ct.commission_amount',
                'ct.credited_at',
                'ct.status'
            );

        // Filters
        if($request->influencer_name) {
            $query->where('u.name', 'like', "%{$request->influencer_name}%");
        }

        if($request->product_name) {
            $query->where('p.title', 'like', "%{$request->product_name}%");
        }

        if($request->date_from) {
            $query->whereDate('o.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('o.created_at', '<=', $request->date_to);
        }

        $query->orderBy('o.created_at', 'desc');
        $perPage = $request->per_page ?: 10;
        $results = $query->paginate($perPage);

        $data = [
            'rows' => view('admin.earning-reports.influencer-listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }

    /**
     * Download Influencer Report as PDF
     */
    public function influencerReportPdf(Request $request)
    {
        if(!adminCan('order', 'view')) {
            abort(403, 'No Permission');
        }

        // Get commission transactions for influencers
        $query = DB::table('commission_transactions as ct')
            ->leftJoin('users as u', function($join) {
                $join->on('ct.referrer_id', '=', 'u.id')
                     ->where('ct.referrer_type', '=', 'user')
                     ->where('u.is_influencer', '=', 1);
            })
            ->leftJoin('orders as o', 'ct.order_id', '=', 'o.id')
            ->leftJoin('order_items as oi', 'ct.order_item_id', '=', 'oi.id')
            ->leftJoin('products as p', 'oi.product_id', '=', 'p.id')
            ->where('ct.referrer_type', 'user')
            ->where('ct.status', 'credited')
            ->where('u.is_influencer', 1)
            ->select(
                'ct.id',
                'u.name as influencer_name',
                'u.email as influencer_email',
                'p.title as product_name',
                'o.order_number',
                'o.created_at as purchase_date',
                'ct.commission_amount',
                'ct.credited_at',
                'ct.status'
            );

        // Filters
        if($request->influencer_name) {
            $query->where('u.name', 'like', "%{$request->influencer_name}%");
        }

        if($request->product_name) {
            $query->where('p.title', 'like', "%{$request->product_name}%");
        }

        if($request->date_from) {
            $query->whereDate('o.created_at', '>=', $request->date_from);
        }

        if($request->date_to) {
            $query->whereDate('o.created_at', '<=', $request->date_to);
        }

        $query->orderBy('o.created_at', 'desc');
        $results = $query->get();

        $pdf = PDF::loadView('admin.earning-reports.influencer-pdf', [
            'results' => $results,
            'filters' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->download('influencer-report-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Get Astrologer Suggestions for Autocomplete
     */
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
