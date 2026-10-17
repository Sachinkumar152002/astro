<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Video;

class VideoController extends Controller
{
     public function index(Request $request)
     {
        $pageName = 'Video';
        $breads = [
            ['name' => 'Video','is_last' => true],
        ];
        $dailyVideos = Video::where('category', 'daily')->take(4)->where('status',1)->orderBy('updated_at', 'desc')->get();
        $weeklyVideos = Video::where('category', 'weekly')->take(4)->where('status',1)->orderBy('updated_at', 'desc')->get();
        $yearlyVideos = Video::where('category', 'yearly')->take(4)->where('status',1)->orderBy('updated_at', 'desc')->get();
        $firstVideo = false;
        if($dailyVideos) $firstVideo = $dailyVideos->first();
        elseif($weeklyVideos) $firstVideo = $weeklyVideos->first();
        elseif($yearlyVideos) $firstVideo = $yearlyVideos->first();

        return view(themeView().'video.index', compact('dailyVideos', 'breads', 'weeklyVideos', 'pageName', 'yearlyVideos', 'firstVideo'));
     }

     public function categoryVideo(Request $request)
     {
        $pageName = ucfirst($request->category).' Video';
        $breads = [
            ['name' => 'Video','url' => route('video.index')],
            ['name' => $pageName,'is_last' => true],
        ];
        $videos = Video::where('category', $request->category)->where('status',1)->orderBy('updated_at', 'desc')->paginate(28);
        $firstVideo = false;
        if($videos) $firstVideo = $videos->first();

        return view(themeView().'video.category', compact('videos', 'breads',  'pageName', 'firstVideo'));
     }
}
