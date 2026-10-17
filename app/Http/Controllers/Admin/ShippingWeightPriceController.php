<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ShippingWeightPrice;

class ShippingWeightPriceController extends Controller
{
    private $module = 'shippingweightprice';
    private $moduleName = 'Shipping Weight Price';
    private $moduleNameSingle = 'shippingweightprice';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan($this->module,'view')) return dd('No Permission');
      $nav = $this->module;
      $modulename = $this->moduleName;
      $modulenamesingle = $this->moduleName;
      $breads = [
          ['name'=>$this->moduleName]
      ];
      return view('admin.'.$this->module.'.list', compact('nav','breads', 'modulename', 'modulenamesingle'));
    }

    /**/
    public function submit(Request $request)
    {
      $id = $request->item_id;
      if(!$id && !adminCan($this->module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($this->module, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }

      $this->validate($request, [
        'weight_from' => 'required|numeric',
        'weight_to' => 'required|numeric',
        'local_price' => 'required|numeric',
        'domestic_price' => 'required|numeric',
        'national_price' => 'required|numeric',
      ]);

      $item = ShippingWeightPrice::findOrNew($id);
      $item->weight_from = $request->weight_from?:0;
      $item->weight_to = $request->weight_to?:0;
      $item->local_price = $request->local_price?:0;
      $item->domestic_price = $request->domestic_price?:0;
      $item->national_price = $request->national_price?:0;

      if($item->save()){
          return response()->json(['message'=>'Succcessfully saved!'], 200);
      }
      return response()->json(['message'=>'Error in saving'], 401);
    }

    /**/
    public function all(Request $request)
    {
       $items = ShippingWeightPrice::orderBy($request->sort_by,$request->sort_dir);
       if($weight_from = $request->weight_from){
         $items->where('weight_from','>=', $weight_from);
       }
       if($weight_to = $request->weight_to){
         $items->where('weight_to', '<=', $weight_to);
       }
       if($local_price = $request->local_price){
         $items->where('local_price', $local_price);
       }
       if($domestic_price = $request->domestic_price){
         $items->where('domestic_price', $domestic_price);
       }
       if($national_price = $request->national_price){
         $items->where('national_price', $national_price);
       }

      $results = $items->paginate(100);
      $nav = $this->module;
      $data = [
          'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

      ];
      return response()->json($data, 200);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan($this->module,'delete')) return dd('No Permission');
      if($item_id = $request->item_id) {
        $item = ShippingWeightPrice::find($item_id);
        $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }
    /*
     public function form(Request $request)
     {
       if(!adminCan('shippingweightprice','view')) return dd('No Permission');
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName],
        ];
       $shipping_weight_prices = ShippingWeightPrice::get();
       return view('admin.'.$nav.'.form', compact('nav', 'breads', 'shipping_weight_prices', 'moduleName'));
     }

     public function submit(Request $request) {
         \DB::select('truncate table shipping_weight_prices');
         if(is_array($request->weight_from) && count($request->weight_from)){
           foreach($request->weight_from as $index => $value){
             if(isset($request->weight_from[$index]) && isset($request->weight_to[$index]) && isset($request->local_price[$index]) && isset($request->domestic_price[$index]) && isset($request->national_price[$index])){
              $shipping_price = new ShippingWeightPrice();
              $shipping_price->weight_from 		= $request->weight_from[$index];
              $shipping_price->weight_to 		= $request->weight_to[$index];
              $shipping_price->local_price 		= $request->local_price[$index];
              $shipping_price->domestic_price 		= $request->domestic_price[$index];
              $shipping_price->national_price 		= $request->national_price[$index];
              $shipping_price->save();
             }
           }
         }
        $redirectTo =route('admin.'.$this->module.'.add');
        return response()->json(['message'=>'Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
     */
}
