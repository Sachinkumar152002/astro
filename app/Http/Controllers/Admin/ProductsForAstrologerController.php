<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ProductsForAstrologerController extends Controller
{
    private $module = 'products_for_astrologer';
    private $moduleName = 'Products for Astrologer';

    public function index(Request $request)
    {
        if (!adminCan('astrologer', 'view')) {
            return redirect()->route('admin.dashboard')->with('error', 'No permission');
        }
        $nav = $this->module;
        $breads = [
            ['name' => 'Astrologers Management', 'url' => route('admin.Astrologer.index')],
            ['name' => $this->moduleName],
        ];
        $moduleName = $this->moduleName;
        return view('admin.products_for_astrologer.list', compact('nav', 'breads', 'moduleName'));
    }

    public function all(Request $request)
    {
        if (!adminCan('astrologer', 'view')) {
            return response()->json(['rows' => '', 'pagination' => ''], 403);
        }
        // type can be stored as string in DB (VARCHAR)
        $query = Product::whereIn('type', [1, 2, 3, '1', '2', '3'])
            ->where(function ($q) {
                $q->where('parent_id', 0)->orWhereNull('parent_id');
            });

        if ($request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if (isset($request->show_in_astrologer_profile) && $request->show_in_astrologer_profile !== '') {
            $query->where('show_in_astrologer_profile', (int) $request->show_in_astrologer_profile);
        }

        // Show assigned products first (show_in_astrologer_profile = 1), then by sort order and title
        $query->orderByRaw('COALESCE(show_in_astrologer_profile, 0) DESC')
            ->orderByRaw('COALESCE(astrologer_profile_sort_order, 9999) ASC')
            ->orderBy('title', 'ASC');
        $products = $query->paginate(15);

        $data = [
            'rows' => view('admin.products_for_astrologer.listrows', [
                'products' => $products,
            ])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $products])->render(),
        ];
        return response()->json($data, 200);
    }

    public function submit(Request $request)
    {
        if (!adminCan('astrologer', 'edit')) {
            return response()->json(['success' => false, 'message' => 'No permission'], 403);
        }
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.show_in_astrologer_profile' => 'nullable|boolean',
            'products.*.astrologer_profile_sort_order' => 'nullable|integer|min:0',
        ]);

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $product->show_in_astrologer_profile = !empty($item['show_in_astrologer_profile']);
                $product->astrologer_profile_sort_order = isset($item['astrologer_profile_sort_order'])
                    ? (int) $item['astrologer_profile_sort_order'] : null;
                $product->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
    }
}
