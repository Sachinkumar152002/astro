<?php
/*
 * Developer : Dixinfotech Team
 */
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
function productype($key=0) {
  $array = [
      '1' => 'Product',
      '2' => 'Pooja',
      '3' => 'Service',
  ];
  return $array[$key]??$array;
}
function footerPuja(){
  $categriesId = App\Product::where('type', 'Pooja')
  ->where('status', 1)
  ->where('parent_id', 0)
  ->distinct("category_id")
  ->pluck("category_id")
  ->toArray();
  $categories = App\Model\ProductCategory::where("type", 'Pooja')
  ->where("status", 1)
  ->whereIn("id", $categriesId)
  ->get();
  return $categories;
}
function productTypeName($t){
  $type = $t;
  return $type;
}

function producSubType(){
  return [
    '0' => 'Other',
    '1' => 'Online Pooja',
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

/**
 * Status badge HTML: green for Active, red for Inactive (for admin table listings).
 *
 * @param int|string|null $status 0 = Inactive, 1 = Active
 * @return string HTML span with badge class
 */
function statusBadge($status)
{
  $isActive = (string) $status === '1' || $status === 1 || $status === true;
  $label = $isActive ? 'Active' : 'Inactive';
  $class = $isActive ? 'badge bg-success' : 'badge bg-danger';
  return '<span class="' . e($class) . '">' . e($label) . '</span>';
}

function adminCan($module, $can){
    return auth()->guard('admin')->user()->can($module, $can);
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
        '0' => 'Select Gender',
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
    return str_replace('&nbsp;', ' ', $str);

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
    'about' => 'About',
    // 'fixed' => 'Fixed',
  ];
  return is_null($n) && !isset($templates[$n]) ? $templates : $templates[$n];
}

function themeView(){
  return 'themes.'.config('vrdcms.theme').'.';
}

function logo($w=null, $h=null){
  $logo = \App\Model\Setting::where('key', 'logo')->first();
  return $logo && $logo->logoFn ? $logo->logoFn->image($w, $h) : asset('assets/img/logo.svg');
}

/**
 * Logo as base64 data URI for DomPDF (remote URLs are disabled by default in config/dompdf.php).
 */
function invoice_logo_data_uri_for_pdf($w = 195, $h = 100)
{
    try {
        $logo = \App\Model\Setting::where('key', 'logo')->first();
        if ($logo && $logo->logoFn) {
            $logo->logoFn->image($w, $h);
            $media = $logo->logoFn;
            $sizeDir = $w.'-'.$h;
            $sized = public_path('media/'.$media->id.'/'.$sizeDir.'/'.$media->file);
            if (\Illuminate\Support\Facades\File::exists($sized)) {
                return invoice_logo_file_to_data_uri($sized);
            }
            $original = public_path('media/'.$media->id.'/'.$media->file);
            if (\Illuminate\Support\Facades\File::exists($original)) {
                return invoice_logo_file_to_data_uri($original);
            }
        }
        $fallbackSvg = public_path('assets/img/logo.svg');
        if (\Illuminate\Support\Facades\File::exists($fallbackSvg)) {
            return invoice_logo_file_to_data_uri($fallbackSvg);
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('invoice_logo_data_uri_for_pdf: '.$e->getMessage());
    }
    $no = public_path('images/noimage.png');
    if (\Illuminate\Support\Facades\File::exists($no)) {
        return invoice_logo_file_to_data_uri($no);
    }

    return '';
}

function invoice_logo_file_to_data_uri($path)
{
    if (! $path || ! is_readable($path)) {
        return '';
    }
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mimeMap = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
    ];
    $mime = $mimeMap[$ext] ?? (function_exists('mime_content_type') ? @mime_content_type($path) : null) ?: 'application/octet-stream';
    $binary = @file_get_contents($path);
    if ($binary === false) {
        return '';
    }

    return 'data:'.$mime.';base64,'.base64_encode($binary);
}

function directorSign($w=100, $h=100){
  $setting = \App\Model\Setting::where('key', 'director_sign')->first();
  return $setting && $setting->director_signFn ? $setting->director_signFn->image($w, $h) : no_image();
}

function director(){
  $setting = \App\Model\Setting::where('key', 'director_name')->first();
  return $setting ? $setting->value : '';
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
    return cache()->remember('footer-cms-pages', 60*60*24*7, function () {
      return \App\Model\Cms::where('show_in_navigation', 1)->where('status', 1)->get();
    });
}

function youtubeId($url = ''){
  $vId = false;
  if(stristr($url,'youtu.be/')){
    preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID);
    if(isset($final_ID[4])){
      $vId = $final_ID[4];
    }
  }else{
    @preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD);
    if(isset($IDD[5])){
      $vId  = $IDD[5];
    }
  }
  if($vId){
    return $vId;
  }
  return $url ? $url : false;
}

function vimeoId($url = '') {
    $vId = '';
  preg_match('/(vimeo(pro)?\.com)\/(?:[^\d]+)?(\d+)\??(.*)?$/', $url, $final_ID);
  if(isset($final_ID[3])){
    $vId = $final_ID[3];
  }
  return   $vId;
}

function onlineStatus($n=null)
{
  $status = [
    1=> 'Online',
    2=> 'Offline',
    3=> 'Busy',
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
function dateFmt($date){
  return date('F d, Y', strtotime($date));
}
function ISTDatetimeFormat($time){
  return date('d/m/Y h:i A', strtotime($time));
}
function setActiveBySegment($segment, array $values)
{
  if($segment == 0){
    return 'active';
  }
    return in_array(Request::segment($segment), $values) ? 'active' : '';
}
function navigation(){
  return App\Model\Cms::where('status', 1)->where('show_in_navigation',1)->get();
}

function cources() {
  return cache()->remember('footer-courses', 60*60*24*7, function () {
      return App\Model\Course::where('status', 1)->take(7)->get();
  });
}

function courses($type=0){
  $courses = App\Model\Course::where('courses.status', 1)->join('course_categories', 'course_categories.id', '=', 'courses.course_category_id');
  if($type){
    $courses->where('courses.type', $type);
  }
  $coursess = $courses->orderBy('course_categories.order', 'ASC')->orderBy('courses.order', 'ASC')->select('courses.*')->get()->map(function($item, $index){
    return $item->name;
  });
  $coursess = $coursess->toArray();
  array_unshift($coursess, 'Select Course');
  return $coursess;
}

function product() {
  return cache()->remember('footer-products-menu', 60*60*24*7, function () {
    return App\Product::where('status', 1)->where('is_featured',1)->take(7)->orderBy('title', 'asc')->get();
  });
}

function courseTypes($v = '')
{
  $type = ['0'=>'None', '1' => 'Live', '2' => 'Recorded', '3' => 'Online'];
  return isset($type[$v]) ? $type[$v] : $type;
}

function purchaseTypes($v = '')
{
  // $type = ['1'=>'Premium', '2' => 'Gold', '3' => 'Silver', '4' => 'Free'];
  $type = ['1'=>'Premium', '2' => 'Intermediate', '3' => 'Foundation', '4' => 'Basic'];
  return isset($type[$v]) ? $type[$v] : $type;
}

function noImage() {
  return "/assets/images/afaf4cb2f868466c5d60220c4469d588.png";
}
function panchang_img() {
  return "/assets/images/p-2.webp";
}

function pageTypes () {
  return [
    "" => "Select",
    "home" => "Home",
    // "product_categories" => "Product Categories",
    "product" => "Product",
    // "pooja_categories" => "Puja Categories",
    "puja" => "Puja",
    // "service_categories" => "Service Categories",
    "service" => "Service",
    // "astrologer" => "Astrologer",
    // "ai_astrologer" => "AI Astrologer",
    "kundali" => "Kundali",
    "kundali_matching" => "Kundali Matching",
    "panchang" => "Panchang",
    "horoscope" => "Horoscope",
  ];
}

function prfileImg(){
  return "/assets/images/coustomer.webp";
}

function days() {
  $days = [
    "Monday" => 'Monday',
    "Tuesday" => 'Tuesday',
    "Wednesday" => 'Wednesday',
    "Thursday" => 'Thursday',
    "Friday" => 'Friday',
    "Saturday" => 'Saturday',
    "Sunday" => 'Sunday',
  ];
  return $days;
}

function videoDetails($url)
{
   $videoId = youtubeId($url);
   $image = $title = $description = '';
   if($videoId){
       $apikey = 'AIzaSyCay4G-bn_qb2V3bhWiYPvw6oSfWG3PL6I';
       $googleApiUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apikey . '&part=snippet';

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       curl_setopt($ch, CURLOPT_VERBOSE, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $response = curl_exec($ch);
       curl_close($ch);
       $data = json_decode($response);
       $value = json_decode(json_encode($data), true);
       if(count($value['items'])>0){
         // return $value;
         $title = $value['items'][0]['snippet']['title'];
         $description = $value['items'][0]['snippet']['description'];
         if(isset($value['items'][0]['snippet']['thumbnails']['maxres'])){
           $image = $value['items'][0]['snippet']['thumbnails']['maxres']['url'];
         }else if(isset($value['items'][0]['snippet']['thumbnails']['standard'])){
           $image = $value['items'][0]['snippet']['thumbnails']['standard']['url'];
         }else if(isset($value['items'][0]['snippet']['thumbnails']['high'])){
           $image = $value['items'][0]['snippet']['thumbnails']['high']['url'];
         }else if(isset($value['items'][0]['snippet']['thumbnails']['medium'])){
           $image = $value['items'][0]['snippet']['thumbnails']['medium']['url'];
         }else if(isset($value['items'][0]['snippet']['thumbnails']['default'])){
           $image = $value['items'][0]['snippet']['thumbnails']['default']['url'];
         }else {
           $image = sprintf("https://img.youtube.com/vi/%s/0.jpg", $videoId);
         }
      }
   }
   return ['title'=>$title, 'image'=>$image, 'description'=>$description, 'video_id'=>$videoId, 'url'=>$url];
}

  function category() {
    return App\CourseCategory::where('status',1)->orderBy('order', 'asc')->limit(7)->get();
  }

  function formFields() {
    $fields = [
      '' => 'Select Field',
      'text' => 'Text',
      'email' => 'Email',
      // 'file' => 'File',
      'date' => 'Date',
      'time' => 'Time',
      'number' => 'Number',
      // 'textarea' => 'Textarea',
    ];
    return $fields;
  }

  function filterSortBy() {
    $fields = [
      'a_to_z'=>'A to Z',
      // 'price_low_to_high' => 'Price Low To High',
      // 'price_high_to_low' => 'Price High To Low',
      'whats_new' => "What's New",
    ];
    return $fields;
  }
  function inrConvert($amount) {
    return number_format($amount,0);
  }

function faqTypes($index=''){
  $type = [
    // '' => 'Select',
    '0' => 'All',
    '1' => 'Product',
    '2' => 'Puja',
    '3' => 'Service',
    '4' => 'Astrologer',
    '5' => 'AI Astrologer'
  ];
  return is_numeric($index) && isset($type[$index]) ? $type[$index] : $type;
}

function faqTypesValue($index){
  $type = [
    // '' => 'Select',
    '0' => 'All',
    '1' => 'Product',
    '2' => 'Puja',
    '3' => 'Service',
    '4' => 'Astrologer',
    '5' => 'AI Astrologer'
  ];
  return isset($type[$index])?$type[$index]:'';
}

function cartitemcount($item_type=0){
  $user_id = 0;
  $user_type = '';
  if(Session::get("cart_user_id") == null && auth()->guard('web')->check()){
      $user_id =  auth()->guard('web')->user()->id;
      $user_type = 'user';
  }elseif(Session::get("cart_user_id") == null && auth()->guard('astrologer')->check()){
    $user_id =  auth()->guard('astrologer')->user()->id;
    $user_type = 'astrologer';
  }elseif(Session::get("cart_user_id")){
    $user_id = Session::get("cart_user_id");
    $user_type = Session::get("cart_user_type");
  }
  Session::put("cart_user_id", $user_id);
  Session::put("cart_user_type", $user_type);
  $cartItems = App\CartItem::where('user_id', $user_id)->where('user_type', $user_type)->where('item_type', $item_type)->get();
  $total = 0;
  foreach ($cartItems as $key => $item) {
    $total += $item->quantity;
  }
  return $total;
}

function astrologerCategories($limit){
  return cache()->remember('astrologer-categories', 60*60*24*7, function () use($limit) {
    $astrologersId = App\Model\Astrologer::where("type", "Astrologer")
    ->where("chat", ">", 0)
    ->pluck("id")
    ->toArray();
    $servicesId = App\Model\AstrologerService::distinct("service_id")
    ->whereIn("astrologer_id", $astrologersId)
    ->pluck("service_id")
    ->toArray();
    return App\Model\Service::whereIn("id", $servicesId)
    ->orderBy('name','asc')
    ->limit($limit)
    ->get();
  });
}

function productCategory($limit){
  return cache()->remember('product-categories', 60*60*24*7, function () use($limit) {
    $categriesId = App\Product::where('type', 1)
    ->where('status', 1)
    ->where('parent_id', 0)
    ->distinct("category_id")
    ->pluck("category_id")
    ->toArray();
    return App\Model\ProductCategory::where("type", 1)
    ->where('status', 1)
    ->whereIn("id", $categriesId)
    ->orderBy('order','asc')
    ->limit($limit)
    ->get();
  });
}

function poojas($limit){
  return cache()->remember('pooja-products', 60*60*24*7, function () use($limit) {
    return App\Product::where('type', 2)
    ->where('status', 1)
    ->where('parent_id', 0)
    ->orderBy('order', 'asc')
    ->limit($limit)
    ->get();
  });
}

function serviceProducts($limit){
  return cache()->remember('serive-products', 60*60*24*7, function () use($limit) {
    $categriesId = App\Product::where('type', 3)
    ->where('status', 1)
    ->where('parent_id', 0)
    ->distinct("category_id")
    ->pluck("category_id")
    ->toArray();
    $categories = App\Model\ProductCategory::where("type", 3)
    ->where("status", 1)
    // ->whereIn("id", $categriesId)
    ->get();
    return $categories;
  });
}

function zodiacSignsModel(){
  return cache()->remember('zodiac-signs', 60*60*24*7, function () {
    return App\Model\ZodiacSign::get();
  });
}

function textTstimonialPage(){
  $type = [
    // '' => 'Select',
    // '7' => 'Astrology',
    '2' => 'Astrologer',
    // '1' => 'Course',
    // '8' => 'Instructor',
    '3' => 'Product',
    '4' => 'Puja',
    '5' => 'Service',
    // '5' => 'Software',
    // '4' => 'Vastu Shastra',
  ];
  return $type;
}
function videoTstimonialPage($key = ''){
  $type = [
    // '0' => 'Select',
    // '8' => 'About',
    // '7' => 'Astrology',
    '2' => 'Astrologer',
    // '1' => 'Course',
    // '6' => 'Instructor',
    '3' => 'Product',
    '4' => 'Puja',
    '5' => 'Service',
    // '5' => 'Software',
    // '4' => 'Vastu Shastra',
  ];
  return is_numeric($key) ? ( isset($type[$key]) ? $type[$key] : '' ) : $type;
}
function countries($id=0){
    return cache()->remember('countries', 60*60*24*7, function () use($id) {
      if($id){
        return \DB::table('master_countries')->where("id", $id)->orderBy('name', 'ASC')->get();
      }
      return \DB::table('master_countries')->orderBy('name', 'ASC')->get();
    });
}
function vastuServies(){
  return \App\Model\Vastu::where('type','sub-page')->where('status',1)->orderBy('name', 'ASC')->select('name', 'id')->get();
}
function serviceType(){
  $type = [
    '' => 'Service Type',
    'online' => 'Online',
    'ofline' => 'Ofline',
  ];
  return $type;
}

function daysIndex($day){
  $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  // return $days[$day];
  return array_search($day, $days);
}

function courseSpecifications(){
  $type = [
    'assets/course/specification/first.png' => 'Total Class',
    'assets/course/specification/second.png' => 'Recorded Videos',
    'assets/course/specification/third.png' => 'Duration',
    'assets/course/specification/fourth.png' => 'Timing',
    'assets/course/specification/fifth.png' => 'Language',
  ];
  return $type;
}

function ads($page = '', $display_on = 'page'){
  $today = date('Y-m-d');
  $ads = App\Model\Advertisement::where('status', 1)->where('display_on', $display_on);
  if($page){
    $ads->where('page', $page);
  }
  return $ads->orderBy('id', 'desc')
    ->orderBy('order', 'desc')
    ->where(function($q)use($today){
      $q->where('start_date','<=', $today)->orWhereNull('start_date');
    })
    ->where(function($q)use($today){
      $q->where('end_date','>=', $today)->orWhereNull('end_date');
    })
    ->take(5)->get();
}

function cms2(){
  $type = [
    '' => 'Select',
    'astrology' => 'Astrology',
    'software' => 'Software',
  ];
  return $type;
}

function languages(){
  $type = [
    '' => 'Select Language',
    'english' => 'English',
    'hindi' => 'Hindi',
  ];
  return $type;
}


function astrologyServices(){
  $type = [
    '' => 'Select Service',
    'gemstone_recommendation' => 'Gemstone Recommendation',
    'horoscope_analysis' => 'Horoscope Analysis',
    'janam_patrika' => 'Janam Patrika',
    'kundli_making' => 'Kundli Making',
    'kp_astrology' => 'Kp Astrology',
    'match_making' => 'Match Making',
    'nadi_astrology' => 'Nadi Astrology',
    'rudraksh_recommendation' => 'Rudraksh Recommendation',
    'vedic_astrology' => 'Vedic Astrology',
    'other' => 'Other',
  ];
  return $type;
}

function pages(){
  $type = [
    '' => 'Select Page',
    'astrologer' => 'Astrologer',
    'blog' => 'Blog',
    'home' => 'Home',
    'product' => 'Product',
    // 'course' => 'Course',
    // 'vastu' => 'Vastu Shastra',
    // 'software' => 'Software',
    // 'astrology' => 'Astrology',
    // 'today' => 'Thought of the day',
  ];
  return $type;
}

function userQualification(){
  $type = [
    '' => 'Select Qualification',
    'Middle School' => 'Middle School',
    'High School' => 'High School',
    'Intermediate' => 'Intermediate',
    'Graduation' => 'Graduation',
    'Post Graduation' => 'Post Graduation',
    'Others' => 'Others',
  ];
  return $type;
}

// function vimeoVideo($videoId){
//   $videoId = trim($videoId);
//   $client = new Vimeo\Vimeo("dd9d6bcfa13627d2e6c073350b68de95afe4c747", "6aNgathwF0iGT3EeNMCPYIZIU8CK8cuaSGMAEEJXA8f/mrzR5T8TqNSQqe+ceiGo6uCdZ2AKaJKXJ5lpLjf65Ft8K0qdEbgxv48o1Czz9Ea9EVPavogRx6yrqiTIH1eB", "58abee3b6e3a601ea10185e5d8aaca08");
//   $response = $client->request('/me/videos/'.$videoId, array(), 'GET');
//   if($response['status'] == 200){
//     return $response['body']['player_embed_url'];
//   }else{
//     return null;
//   }
// }

function vimeoVideo($videoId){
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.vimeo.com/videos/".$videoId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
      "Accept: application/json",
      "Authorization: Bearer ".config('vrdcms.vimeo_access_token'),
      "Content-Type: application/json"
    ],
  ]);
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  $title = '';
  $video_id = $videoId;
  $video_hash = 0;
  $description = '';
  $player_embed_url = '';

  if($err){
    // echo "cURL Error #:" . $err;
    return ['title'=>$title, 'video_id'=>$video_id, 'video_hash'=>$video_hash, 'description'=>$description, 'player_embed_url'=>$player_embed_url];
  }else{
    $decodedResponse = json_decode($response, true);
    if(isset($decodedResponse['link'])){
      $array = explode('/', $decodedResponse['link']);
      $video_hash = $array[count($array)-1];
      $title = isset($decodedResponse['name'])?$decodedResponse['name']:'';
      $description = isset($decodedResponse['description'])?$decodedResponse['description']:'';
      $player_embed_url = isset($decodedResponse['player_embed_url'])?$decodedResponse['player_embed_url']:'';
      return ['title'=>$title, 'video_id'=>$video_id, 'video_hash'=>$video_hash, 'description'=>$description, 'player_embed_url'=>$player_embed_url];
    }
    return ['title'=>$title, 'video_id'=>$video_id, 'video_hash'=>$video_hash, 'description'=>$description, 'player_embed_url'=>$player_embed_url];
  }
}

function courseQuestionShowIn(){
  $type = [
    '' => 'Select',
    'assignment' => 'Assignment',
    'quizz' => 'Quizz',
  ];
  return $type;
}

function correctOption($answer){
  $type = ['Skip', 'A', 'B', 'C', 'D'];
  return $type[$answer];
}

function supportType($key=''){
  $type = [
    '' => 'Select',
    'product' => 'Product',
    'astrologer' => 'Astrologer',
    'platform' => 'Platform',
    'other' => 'Other',
  ];
  return $key ? ( isset($type[$key]) ? $type[$key] : "" ) : $type;
}

function supportTypeAstrologer(){
  $type = [
    '' => 'Select',
    'platform' => 'Platform',
    'other' => 'Other',
  ];
  return $type;
}

function supportTypePrefix($key){
  $type = [
    '' => 'Select',
    'astrologer' => 'AST',
    'astrology' => 'ASTRO',
    // 'course' => 'COU',
    'product' => 'PRO',
    'platform' => 'PLA',
    // 'software' => 'SFT',
    // 'vastu_shastra' => 'VAS',
    'other' => 'OTH',
  ];
  return isset($type[$key]) ? $type[$key] : null;
}

function getSupportTypeName($key){
  $type = [
    '' => 'Select',
    'product' => 'Product',
    'astrologer' => 'Astrologer',
    'platform' => 'Platform',
    'other' => 'Other',
  ];
  return $type[$key]?:'';
}
function couponApplyOn($key = null){
  $type = [
    '' => 'Select',
    'any' => 'Any',
    'product' => 'Product',
    // 'course' => 'Course',
    'recharge' => 'Recharge',
  ];
  return $key && isset($type[$key]) ? $type[$key] : $type;
}
function payoutPakages(){
  return \DB::table('payout_packages')->get();
}

function fromSetting($key = ''){
  $setting = \DB::table('settings')->where('key', $key)->first();
  return $setting ? $setting->value : '' ;
}
function vastuIndex(){
  $page = \App\Model\Vastu::where('type', 'page')->first();
  if($page){
    return $page->slug;
  }
  return 'vastu';
}
function ngSoftwarePage($type){
  $page = \App\Model\NgSoftware::where('page', $type)->first();
  if($page){
    return $page->slug;
  }
  return '/';
}

function downloadCertificate($user, $user_type, $course){

  $path = public_path('certificate/user/'.$user->id.'/');
  $fileName = $course->slug.'.pdf';
  $pdfUrl = $path.$fileName;
  if(!\File::exists($pdfUrl)) {
    $path = public_path('certificate');
    if(!\File::exists($path)){
        \File::makeDirectory($path, 0755, true);
    }
    $path = public_path('certificate/user');
    if(!\File::exists($path)){
        \File::makeDirectory($path, 0755, true);
    }
    $path = public_path('certificate/user/'.$user->id);
    if(!\File::exists($path)){
        \File::makeDirectory($path, 0755, true);
    }
    $pdf = \PDF::loadView(themeView().'account.explore.certificate', compact('user', 'course'))->setPaper('a4', 'landscape');
    $path = public_path('certificate/user/'.$user->id.'/');
    $pdf->save($path.$fileName);
    $pdf = null;
  }
  $path = asset('certificate/user/'.$user->id);
  $pdfUrl = $path.'/'.$fileName;
  return $pdfUrl;
}

function orderStatus($key = null){
  $type = [
    '' => 'Select',
    'ordered' => 'Ordered',
    'shipped' => 'Shipped',
    'in transit' => 'In-Transit',
    'dispatched' => 'Dispatched',
    'delivered' => 'Delivered',
  ];
  return $key && isset($type[$key]) ? $type[$key] : $type;
}

function consultationRequests($appointment_id='', $request_type='', $user_type=''){
  $data = \App\Model\ConsultationRequest::where('appointment_id', $appointment_id);
  if($request_type){
    $data->where('request_type', $request_type);
  }
  if($user_type){
    $data->where('user_type', $user_type);
  }
  return $data->get();
}
function calculateShippingAmount($product, $pincode){
  $shipping = null;
  $amount = fromSetting('shipping_charges')?:0;
  if($product->shipping_based_on==1){
    //volume
    $productVolume = $product->length*$product->breadth*$product->height;
    $shipping = \App\Model\ShippingVolumePrice::where('volume_from', '<=', $productVolume)->where('volume_to', '>=', $productVolume)->first();
  }else{
    //weight
    $productWeight = $product->weight;
    $shipping = \App\Model\ShippingWeightPrice::where('weight_from', '<=', $productWeight)->where('weight_to', '>=', $productWeight)->first();
  }
  if($shipping){
    $pincodee = $pincode;
    $domestic = \App\Model\DomesticPincode::where('pincode', $pincode)->first();
    if($domestic){
      $amount = $shipping->domestic_price;
    }elseif(\App\Model\LocalPincode::where('pincode', $pincode)->first()){
      $amount = $shipping->local_price;
    }else{
      $amount = $shipping->national_price;
    }
  }
  return $amount;
}

function getOrderStatus($order_id, $status){
  $status = \DB::table('order_status')->where('order_id', $order_id)->where('status', $status)->first();
  return $status?date('d-m-Y', strtotime($status->created_at)):'';
}

function assignmentAnswer($user_id, $question_id){
  return  \App\Model\AssignmentAnswer::where('user_id', $user_id)->where('question_id', $question_id)->first();
}

function orderRequestReason($key = null){
  $types = [
    '' => 'Select',
    '1' => 'Order created by mistake.',
    '2' => 'Item(s) would not arrive on time.',
    '3' => 'Shipping cost too high.',
    '4' => 'Item price too high.',
    '5' => 'Need to change shipping address',
    '6' => 'Need to change billing address',
    '7' => 'Need to change payment method.',
    '8' => 'Other',
  ];
  return $key && isset($types[$key]) ? $types[$key] : $types;
}

function orderRequestReturnReason($key = null){
  $types = [
    '' => 'Select',
    '1' => 'Product is physically damaged.',
    '2' => 'Product has missing parts or accessories.',
    '3' => 'Defective product.',
    '4' => 'Different form their description or specification.',
    '5' => 'Other'
  ];
  return $key && isset($types[$key]) ? $types[$key] : $types;
}

function orderRequest($user_id, $order_id, $request_type, $status_array){
  return \DB::table('order_requests')
  ->where('user_id', $user_id)
  ->where('order_id', $order_id)
  ->where('request_type', $request_type)
  ->whereIn('status', $status_array)
  ->first();
}
function orderRequests($user_id, $order_id, $request_type){
  return \DB::table('order_requests')
  ->where('user_id', $user_id)
  ->where('order_id', $order_id)
  ->where('request_type', $request_type)
  ->get();
}

function courseGst($key = null){
  $type = [
    '' => 'Select',
    '18' => '18%',
    '9' => '9%',
    '6' => '6%',
    '3' => '3%',
  ];
  return $key && isset($type[$key]) ? $type[$key] : $type;
}

function landingPages($status = 1) {
    return \App\Model\Landing::where('status', $status)->get();
}

function landingPagesold($status = 1) {
    return \App\Model\LandingPage::where('status', $status)->get();
}

function sendDataToCrm($body){
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.telecrm.in/enterprise/6744661ed39cc8bbd9b1480f/autoupdatelead",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($body),
    CURLOPT_HTTPHEADER => [
      "Accept: application/json",
      "Authorization: Bearer 7902688b-bd45-4434-a851-e1017ce6a5bd1741333304388:8a18a8c3-b616-4490-84f3-c07885e825fe",
      "Content-Type: application/json"
    ],
  ]);
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
}

function notifications($for, $user, $user_type, $limit = 8){
  $userIdIn = [$user->id, 0];
  if($user_type=='user'){
    return \App\Model\Notification::whereIn('user_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->orderBy('created_at', 'desc')->limit($limit)->get();
  }else{
    return \App\Model\Notification::whereIn('astrologer_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->orderBy('created_at', 'desc')->limit($limit)->get();
  }
}

function countryByID($id = 0) {
  if($id) {
    return \DB::table('master_countries')->where('id', $id)->first()->name;
  }
  return 'na';
}

function stateByID($id = 0) {
  if($id) {
    return \DB::table('master_states')->where('id', $id)->first()->name;
  }
  return 'na';
}
function cityByID($id = 0) {
  if($id) {
    return \DB::table('master_cities')->where('id', $id)->first()->name;
  }
  return 'na';
}

function notificationOn($key=-1){
  $type=[
    '0'=>'Notification',
    '1'=>'Top'
  ];
  return $key>-1?$type[$key]:$type;
}

function astrologerCommisionSettlementInDays($key=0){
  $type=[
    'sevendays'=>'7 Days',
    'fifteendays'=>'15 Days',
    'onemonth'=>'1 Month'
  ];
  return $key?$type[$key]:$type;
}

function invoice($relation_type, $relation_id){
  return \App\Model\Invoice::where('relation_type', $relation_type)->where('relation_id', $relation_id)->first();
}

function sha256($value){
  $clean = trim(strtolower($value));
  return hash('sha256', $clean);
}

function loginOtpMessage($otp){
  return urlencode("Hi, Your AdvoLive verification code is $otp It is valid for 10 minutes. NEVER share this code with anyone. –Team AdvoLive");
}
