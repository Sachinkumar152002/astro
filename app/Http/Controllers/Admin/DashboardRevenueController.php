<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OrderItem;
use App\Model\KundaliEnquiry;
use App\Model\KundaliMatchingEnquiry;
use Illuminate\Http\Request;

class DashboardRevenueController extends Controller
{
    protected function segmentToProductType(string $segment): int
    {
        return match ($segment) {
            'products' => 1,
            'puja' => 2,
            'reports' => 3,
            default => abort(404),
        };
    }

    protected function segmentTitle(string $segment): string
    {
        return match ($segment) {
            'products' => 'Products Revenue',
            'puja' => 'Puja Revenue',
            'reports' => 'Reports Revenue',
            default => '',
        };
    }

    public function index(Request $request, string $segment)
    {
        if (! adminCan('order', 'view')) {
            return dd('No Permission');
        }
        $this->segmentToProductType($segment);

        $nav = 'order';
        $breads = [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => $this->segmentTitle($segment)],
        ];

        return view('admin.dashboard_revenue.list', compact('nav', 'breads', 'segment'));
    }

    public function list(Request $request, string $segment)
    {
        if (! adminCan('order', 'view')) {
            return response()->json(['message' => 'No Permission'], 403);
        }

        $productType = $this->segmentToProductType($segment);

        $query = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereNull('products.deleted_at')
            ->where('order_items.type', 'shop')
            ->where('products.type', $productType)
            ->where('orders.status', 'success')
            ->select('order_items.*');

        if ($request->filled('product_name')) {
            $term = $request->product_name;
            $query->where('products.title', 'like', '%'.$term.'%');
        }

        if ($request->filled('has_image')) {
            if ($request->has_image === '1') {
                $query->whereNotNull('products.image_id');
            } elseif ($request->has_image === '0') {
                $query->whereNull('products.image_id');
            }
        }

        if ($request->filled('price_min')) {
            $query->where('order_items.total_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('order_items.total_price', '<=', $request->price_max);
        }

        if ($request->filled('date_from')) {
            $query->whereRaw('DATE(orders.created_at) >= ?', [$request->date_from]);
        }
        if ($request->filled('date_to')) {
            $query->whereRaw('DATE(orders.created_at) <= ?', [$request->date_to]);
        }

        $sortable = [
            'orders.created_at' => 'orders.created_at',
            'order_items.total_price' => 'order_items.total_price',
            'products.title' => 'products.title',
        ];
        $sortBy = $request->sort_by ?? 'orders.created_at';
        if (! array_key_exists($sortBy, $sortable)) {
            $sortBy = 'orders.created_at';
        }
        $sortDir = strtolower((string) $request->sort_dir) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortable[$sortBy], $sortDir)->orderBy('order_items.id', 'desc');

        $items = $query->with(['product.image', 'order'])->paginate(10);

        $data = [
            'rows' => view('admin.dashboard_revenue.listrows', ['items' => $items])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $items])->render(),
        ];

        return response()->json($data, 200);
    }

    public function freeKundaliIndex()
    {
        if (! adminCan('order', 'view')) {
            return dd('No Permission');
        }

        $nav = 'order';
        $breads = [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => 'Free Kundali'],
        ];

        return view('admin.dashboard_revenue.kundali_list', compact('nav', 'breads'));
    }

    public function freeKundaliList(Request $request)
    {
        if (! adminCan('order', 'view')) {
            return response()->json(['message' => 'No Permission'], 403);
        }

        $query = KundaliEnquiry::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('gender')) {
            $query->whereRaw('LOWER(gender) = ?', [strtolower((string) $request->gender)]);
        }
        if ($request->filled('birth_date')) {
            $query->whereDate('date', $request->birth_date);
        }
        if ($request->filled('birth_time')) {
            $query->where('time', 'like', $request->birth_time.'%');
        }

        $sortable = [
            'name' => 'name',
            'gender' => 'gender',
            'date' => 'date',
            'time' => 'time',
            'city' => 'city',
            'lang' => 'lang',
        ];
        $sortBy = $request->sort_by ?? 'date';
        if (! array_key_exists($sortBy, $sortable)) {
            $sortBy = 'date';
        }
        $sortDir = strtolower((string) $request->sort_dir) === 'asc' ? 'asc' : 'desc';

        $rows = $query->orderBy($sortable[$sortBy], $sortDir)->paginate(10);

        return response()->json([
            'rows' => view('admin.dashboard_revenue.kundali_listrows', ['rows' => $rows])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $rows])->render(),
        ], 200);
    }

    public function freeKundaliMatchingIndex()
    {
        if (! adminCan('order', 'view')) {
            return dd('No Permission');
        }

        $nav = 'order';
        $breads = [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => 'Free Kundali Matching'],
        ];

        return view('admin.dashboard_revenue.kundali_matching_list', compact('nav', 'breads'));
    }

    public function freeKundaliMatchingList(Request $request)
    {
        if (! adminCan('order', 'view')) {
            return response()->json(['message' => 'No Permission'], 403);
        }

        $query = KundaliMatchingEnquiry::query();

        if ($request->filled('boy_name')) {
            $query->where('boy_name', 'like', '%'.$request->boy_name.'%');
        }
        if ($request->filled('girl_name')) {
            $query->where('girl_name', 'like', '%'.$request->girl_name.'%');
        }
        if ($request->filled('boy_birth_date')) {
            $query->whereDate('boy_birth_date', $request->boy_birth_date);
        }
        if ($request->filled('girl_birth_date')) {
            $query->whereDate('girl_birth_date', $request->girl_birth_date);
        }
        if ($request->filled('lang')) {
            $query->whereRaw('LOWER(lang) = ?', [strtolower((string) $request->lang)]);
        }

        $sortable = [
            'boy_name' => 'boy_name',
            'girl_name' => 'girl_name',
            'boy_birth_date' => 'boy_birth_date',
            'girl_birth_date' => 'girl_birth_date',
            'lang' => 'lang',
        ];
        $sortBy = $request->sort_by ?? 'boy_birth_date';
        if (! array_key_exists($sortBy, $sortable)) {
            $sortBy = 'boy_birth_date';
        }
        $sortDir = strtolower((string) $request->sort_dir) === 'asc' ? 'asc' : 'desc';

        $rows = $query->orderBy($sortable[$sortBy], $sortDir)->paginate(10);

        return response()->json([
            'rows' => view('admin.dashboard_revenue.kundali_matching_listrows', ['rows' => $rows])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $rows])->render(),
        ], 200);
    }
}
