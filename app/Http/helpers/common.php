<?php
/**
 * status Array
 *
 * @return void
 */
function statusArray($v = '')
{
  $type =  [
    '0' => 'Inactive',
    '1' => 'Active'
  ];
  return isset($type[$v]) ? $type[$v] : $type;
}
function testimonialType($v = '')
{
  $type =  [
    '0' => 'Text Testimonial',
    '1' => 'Video Testimonial'
  ];
  return isset($type[$v]) ? $type[$v] : $type;
}
function productype() {
  return [
      '1' => 'Real',
      '2' => 'Virtual',
      '3' => 'Services',
  ];
}

/**
 * YesNO Array
 *
 * @return void
 */
function yesNoArray($v='')
{
  $type= [
    '0' => 'No',
    '1' => 'Yes'
  ];
  return isset($type[$v]) ? $type[$v] : $type;
}

/**
 * Status Label
 *
 * @param [type] $op
 * @return void
 */
function statusLabel($op)
{
  $status = statusArray();
  return $status[$op];
}

function adminCan($module, $can){
    return auth()->guard('admin')->user()->can($module,$can);
}

function getMyCookie(){
    $key = config('labx.cookie');
    $cookieValue = Cookie::get($key);
    if($cookieValue=='') {
        $cookieValue = 'visitor'.time().'labx';
        $_cookie = Cookie::queue(Cookie::make($key, $cookieValue, 60*24*365));
    }
    return $cookieValue;
}


function getCookieValue($key){
    $myCookie = getMyCookie();
    $cookie = \App\Model\VisitorCookie::where('cookie', $myCookie)->first();
    return $cookie ? $cookie->getByKey($key) : '';
}

function genders($op = ''){

    $gender = [
        '0' => 'select gender',
        '1' => 'Male',
        '2' => 'Female',
        '3' => 'other',
    ];

    return $op ? $gender[$op] : $gender;
}
function mySubStr($string, $l = 25)
{
    $str = substr($string, 0, $l);
    if(strlen($string)>$l) {
      $str .= '...';
    }
    return $str;

}
function ratings(){
    return [1=>1,2=>2,3=>3,4=>4,5=>5];
}

function articleStatus($n = null) {
  $types = [
    '0' => 'Inactive',
    '1' => 'Publish',
    '2' => 'Draft',
  ];

  return !is_numeric($n) ? $types : $types[$n];
}

function videoStatus($n = null) {
  $types = [
    '0' => 'Inactive',
    '1' => 'Publish',
    '2' => 'Draft',
  ];

  return !is_numeric($n) ? $types : $types[$n];
}

function pageTemplates($n = null) {
  $templates = [
    'default' => 'Default',
    'content' => 'Content',
    'fixed' => 'Fixed',
  ];
  return is_null($n) && !isset($templates[$n]) ? $templates : $templates[$n];
}

function themeView(){
  return 'themes.'.config('vrdcms.theme').'.';
}

function logo(){
  $logo = \App\Model\Setting::where('key', 'logo')->first();
  return $logo ? asset($logo->value) : asset('images/logo.png');
}

function blogCategories($parent = 0, $lang = 'en') {
  $categories = \App\Model\Category::where('parent_id',$parent)
      ->where('lang', $lang)
      ->where('status', 1)
      ->get();
  return $categories;
}

function recentposts($limit = 3){
  return \App\Model\Article::where('status', 1)->orderBy('created_at', 'desc')->take($limit)->get();
}

function optionByKey($key = ''){
  $option = \App\Model\Setting::where('key', $key)->first();
  return $option ? $option->value : '';
}

function no_image(){
  return App\Model\Media::no_image();
}

function changeVariables($string ='', $variables = []) {
    foreach($variables as $key=>$value) {
        $string = str_replace("%%$key%%", $value, $string);
    }
    return $string;
}

function paymentTypes($n = null) {
  $type = [
    'Credit Card',
    'Master Card',
    'Offline',
    'Bank Transfer',
    'Paypal'
  ];
  return is_null($n) && !isset($type[$n]) ? $type : $type[$n];
}

function cmsMenu(){
  return \App\Model\Cms::where('show_in_navigation', 1)->where('status', 1)->get();
}

function youtubeId($url = ''){
  $vId = false;
  if (stristr($url,'youtu.be/'))
  {
      preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID);
      if(isset($final_ID[4])) { $vId = $final_ID[4]; }
  }
  else {
      @preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD);
      if(isset($IDD[5])) {$vId  = $IDD[5]; }
  }
  if($vId) {
      return $vId;
  }
  return $url ? $url : false;
}
function onlineStatus($n=null)
{
  $status = [
    1=> 'online',
    2=> 'offline',
    3=> 'busy',
  ];
  return isset($status[$n]) ? $status[$n] : $status;
}

function topAstrologers($limit = 8, $services = [])
{
  $topAstrologers =  \App\Model\Astrologer::where('status', 1)->where('is_verified', 1);
  if(count($services)>0){
    $topAstrologers->whereHas('services', function($query)use($services){
      $query->whereIn('service_id', $services);
    });
  }
  $topAstrologers = $topAstrologers->take(8)->orderBy('updated_at', 'desc')->get();
  return $topAstrologers;
}

function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

function ISTtimeFormat($time){
  return date('h:i A', strtotime($time));
}

function ISTDatetimeFormat($time){
  return date('d/m/Y h:i A', strtotime($time));
}
function setActiveBySegment($segment, array $values)
{
    return in_array(Request::segment($segment), $values) ? 'active' : '';
}
function navigation(){
  return App\Model\Cms::where('status', 1)->where('show_in_navigation',1)->get();
}

function cources() {
  return App\Model\Course::where('status', 1)->take(6)->get();
}
function product() {
  return App\Product::where('status', 1)->where('type',3)->get();
}

function courseTypes($v = '')
{
  $type = ['0'=>'None', '1' => 'Live', '2' => 'Recorded', '3' => 'Online'];
  return isset($type[$v]) ? $type[$v] : $type;
}

function noImage() {
  return "/assets/images/afaf4cb2f868466c5d60220c4469d588.png";
}
function panchang_img() {
  return "/assets/images/p-2.webp";
}
