<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Cms;
use App\Model\ZodiacSign;
use App\Model\HoroscopeDaily;
use App\Model\HoroscopeMonthly;
use App\Model\HoroscopeWeekly;
use App\Model\HoroscopeYearly;

class HoroscopeController extends Controller
{
    public function index(Request $request) {
        $type = $request->type;
        if(is_numeric($type)) return $this->yearDetail($request);
        $signs = ZodiacSign::where('status',1)->get();
        if($type) {
            $breads = [
                ['name'=> 'Horoscope', 'url'=>route('horoscopes.index')],
                ['name'=> ucfirst($type), 'is_last'=>true]
            ];
        }else {
            $breads = [
                ['name'=> 'Horoscope', 'is_last'=>true]
            ];
        }
        $urlslug = 'horoscopes';
        $page = Cms::where('slug', $urlslug)->where('status',1)->first();
        if(!$page) abort(404);
        return view(themeView().'horoscope.index', compact('signs', 'type', 'breads', 'page'));
    }

    public function yearDetail(Request $request)
    {
        $year = $request->type;
        if($year>date('Y')) abort(404);
        $signs = ZodiacSign::where('status',1)->get();
        $pagename = 'Horoscope '.ucfirst($year);
        $breads = [
            ['name'=> 'Horoscope', 'url'=>route('horoscopes.index')],
            ['name'=> $pagename, 'is_last'=>true]
        ];

        $horoscrope = HoroscopeYearly::where('zodiac_sign_id', 0)->where('year', $year)->first();
        return view(themeView().'horoscope.yearpage', compact('signs',  'horoscrope', 'year', 'pagename'));
    }

    public function signDetail(Request $request)
    {
        $type = $request->type;
        $sign = $request->sign;

        $selectField = $field = $request->field;
        if($selectField=='free') $selectField = 'main';
        $page = ZodiacSign::where('slug', $sign)->where('status',1)->first();
        if(!$page) abort(404);
        $breads = [
            ['name'=> 'Horoscope', 'url'=>route('horoscopes.index')],
            ['name'=> ucfirst($type), 'url'=>route('horoscopes.index', $type)],
            ['name'=> $page->name.' '.$field.' horoscope', 'is_last'=>true],
        ];
        $signs = ZodiacSign::where('status',1)->get();
        $horoscrope = false;
        if($type=='daily'){
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $tommorow = date('Y-m-d', strtotime('+1 day'));
            $horoscrope = HoroscopeDaily::where('zodiac_sign_id', $page->id)->whereBetween('date', [$yesterday, $tommorow])->orderBy('date', 'asc')->get();
            $horoscrope = $horoscrope->groupBy('date');
        }elseif($type=='weekly'){
            $week = date('w');
            $horoscrope = HoroscopeWeekly::where('zodiac_sign_id', $page->id)->where('week_number', $week)->first();
        }elseif($type=='monthly'){
            $month = date('m');
            $horoscrope = HoroscopeMonthly::where('zodiac_sign_id', $page->id)->where('month_number', $month)->first();
        }elseif(is_numeric($type) && $type==$request->year){
            $year = $request->year;
            if($type>date('Y')) abort(404);
            $horoscrope = HoroscopeYearly::where('zodiac_sign_id', $page->id)->where('year', $type)->first();
            $breads[1]['name'] = 'Horoscope '.$type;
            $breads[2]['name'] = $page->name. ' horoscope '.$type;
            return view(themeView().'horoscope.yearly', compact('signs', 'type', 'sign', 'page', 'selectField', 'horoscrope', 'breads', 'field', 'year'));
        }else {
            abort(404);
        }
        return view(themeView().'horoscope.detail', compact('signs', 'type', 'sign', 'page', 'selectField', 'horoscrope', 'breads', 'field'));
    }
}
