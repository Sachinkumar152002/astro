<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Astrologer;
use App\User;
use App\Product;
use DB;

class SuggestedRemediesController extends Controller
{
    private $module = 'suggested_remedies';
    private $moduleName = 'Suggested Remedies by Astrologer';
    private $moduleNameSingle = 'Suggested Remedy';

    public function index(Request $request)
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');
        $nav = $this->module;
        $breads = [
            ['name'=>$this->moduleName]
        ];
        $moduleName = $this->moduleName;
        return view('admin.suggested_remedies.list', compact('nav','breads', 'moduleName'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');

        $results = DB::table('astrologer_remedy_suggestions')
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($astrologer_name = $request->astrologer_name) {
            $astrologerIds = Astrologer::where('display_name', 'like', "%$astrologer_name%")
                ->orWhere('firstname', 'like', "%$astrologer_name%")
                ->pluck('id')
                ->toArray();
            if(!empty($astrologerIds)){
                $results->whereIn('astrologer_id', $astrologerIds);
            } else {
                $results->where('astrologer_id', 0); // No results
            }
        }

        if($user_name = $request->user_name) {
            $userIds = User::where('name', 'like', "%$user_name%")
                ->orWhere('firstname', 'like', "%$user_name%")
                ->pluck('id')
                ->toArray();
            if(!empty($userIds)){
                $results->whereIn('user_id', $userIds);
            } else {
                $results->where('user_id', 0); // No results
            }
        }

        if($date = $request->date) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ?", [$date]);
        }

        $results = $results->paginate(10);

        $data = [
            'rows' => view('admin.suggested_remedies.listrows', ['results'=>$results])->render(),
            'pagination' => view('admin._partials.pagination', ['results'=>$results])->render(),
        ];
        return response()->json($data, 200);
    }

    public function show($id)
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');

        $suggestion = DB::table('astrologer_remedy_suggestions')->find($id);

        if(!$suggestion){
            return redirect()->route('admin.suggested_remedies.index')->with('error', 'Suggested remedy not found');
        }

        $astrologer = Astrologer::find($suggestion->astrologer_id);
        $user = User::find($suggestion->user_id);
        $appointment = \App\Model\AstrologerAppointment::find($suggestion->appointment_id);

        $products = json_decode($suggestion->products, true) ?: [];
        $reports = json_decode($suggestion->reports, true) ?: [];
        $pujas = json_decode($suggestion->pujas ?? '[]', true) ?: [];

        $productDetails = [];
        $reportDetails = [];
        $pujaDetails = [];

        if(!empty($products)){
            $productDetails = Product::whereIn('id', $products)
                ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
                ->get()
                ->map(function($product){
                    $currentDate = date('Y-m-d');
                    $price = $product->base_price ?? 0;

                    // Check if sale price is valid and within date range
                    if($product->sale_price && $product->sale_price > 0){
                        if($product->sale_price_start_date && $product->sale_price_end_date){
                            if($currentDate >= $product->sale_price_start_date && $currentDate <= $product->sale_price_end_date){
                                $price = $product->sale_price;
                            }
                        } elseif(!$product->sale_price_start_date && !$product->sale_price_end_date){
                            $price = $product->sale_price;
                        }
                    }

                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'price' => $price
                    ];
                });
        }

        if(!empty($reports)){
            $reportDetails = Product::whereIn('id', $reports)
                ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
                ->get()
                ->map(function($report){
                    $currentDate = date('Y-m-d');
                    $price = $report->base_price ?? 0;

                    // Check if sale price is valid and within date range
                    if($report->sale_price && $report->sale_price > 0){
                        if($report->sale_price_start_date && $report->sale_price_end_date){
                            if($currentDate >= $report->sale_price_start_date && $currentDate <= $report->sale_price_end_date){
                                $price = $report->sale_price;
                            }
                        } elseif(!$report->sale_price_start_date && !$report->sale_price_end_date){
                            $price = $report->sale_price;
                        }
                    }

                    return [
                        'id' => $report->id,
                        'title' => $report->title,
                        'price' => $price
                    ];
                });
        }

        if(!empty($pujas)){
            $pujaDetails = Product::whereIn('id', $pujas)
                ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
                ->get()
                ->map(function($puja){
                    $currentDate = date('Y-m-d');
                    $price = $puja->base_price ?? 0;

                    // Check if sale price is valid and within date range
                    if($puja->sale_price && $puja->sale_price > 0){
                        if($puja->sale_price_start_date && $puja->sale_price_end_date){
                            if($currentDate >= $puja->sale_price_start_date && $currentDate <= $puja->sale_price_end_date){
                                $price = $puja->sale_price;
                            }
                        } elseif(!$puja->sale_price_start_date && !$puja->sale_price_end_date){
                            $price = $puja->sale_price;
                        }
                    }

                    return [
                        'id' => $puja->id,
                        'title' => $puja->title,
                        'price' => $price
                    ];
                });
        }

        $nav = $this->module;
        $breads = [
            ['name'=>$this->moduleName, 'url'=> route('admin.suggested_remedies.index')],
            ['name'=>'Detail']
        ];

        return view('admin.suggested_remedies.detail', compact(
            'nav',
            'breads',
            'suggestion',
            'astrologer',
            'user',
            'appointment',
            'productDetails',
            'reportDetails',
            'pujaDetails'
        ));
    }
}
