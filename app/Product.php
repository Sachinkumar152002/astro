<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\TempUser;
use Session;

class Product extends Model
{
    use HasFactory,SoftDeletes ;

    protected $guarded = [];

    protected $casts = [
        'valid_till_dates' => 'array', // Multiple valid dates for puja e.g. ["2026-02-16", "2026-02-18"]
    ];

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }
    public function specifications(){
    return $this->hasOne('App\ProductSpecification','product_id', 'id');
    }
    public function course(){
      return $this->hasMany('App\Course', 'id', 'course_id');
    }
    public function tags(){
      return $this->hasMany('App\Model\ProductTag', 'product_id', 'id');
    }

    public function updatedby()
    {
        return $this->hasOne('App\Admin','id', 'updated_by');
    }
    public function orderItems()
   {
       return $this->hasMany('App\OrderItem', 'product_id', 'id');
   }
   public function product(){
     return $this->hasMany('App\OrderItem', 'product_id', 'id');
   }
   public function productFormFields(){
     return $this->hasMany('App\ProductFormField', 'product_id', 'id');
   }
   public function reviews(){
     return $this->hasMany('App\ProductReview', 'product_id', 'id')->where('status', 1);
   }
   public function productSpec()
   {
     return $this->hasMany('App\Specification','item_id', 'id')->where('item_type', 'shop')->where('type', 'specification');
   }
   public function productGall(){
     return $this->hasMany('App\ProductGallery','product_id','id');
   }
   public function fields(){
     return $this->hasMany('App\ProductFormField','product_id','id')->orderBy('order', 'ASC');
   }
   public function category(){
     return $this->hasOne('App\Model\ProductCategory','id','category_id');
   }
   public function productFeatures()
   {
     return $this->hasMany('App\Specification','item_id', 'id')->where('item_type', 'shop')->where('type', 'feature');
   }
   public function wishlist(){
     $user = 0;
     $type = '';
     if(auth()->guard('web')->check()){
       $user = auth()->guard('web')->user()->id;
       $type = 'user';
     }elseif(auth()->guard('astrologer')->check()){
       $user = auth()->guard('astrologer')->user()->id;
       $type = 'astrologer';
     }
     return $this->hasOne('App\Model\Wishlist','item_id','id')->where('type', 'shop')->where('user_id', $user)->where('user_type', $type);
   }

   public function cartItem(){
     $user_id = 0;
     $user_type = 'guest';
     if(auth()->guard('web')->check()){
       $user_id = auth()->guard('web')->user()->id;
       $user_type = 'user';
     }elseif(Session::get("cart_user_id")){
       $user_id = Session::get("cart_user_id");
     }
     return $this->hasOne('App\CartItem','item_id','id')->where('type', 'shop')->where('user_id', $user_id)->where('user_type', $user_type);
   }

   public function getTempUserAttribute(){
     $user_id = 0;
     $user_type = 'guest';
     if(auth()->guard('web')->check()){
       $user_id = auth()->guard('web')->user()->id;
       $user_type = 'user';
     }elseif(Session::get("cart_user_id")){
       $user_id = Session::get("cart_user_id");
       $user_type = 'guest';
     }
     return TempUser::where('user_id', $user_id)->where('user_type', $user_type)->first();
   }

   public function orderItem() {
     $user = 0;
     $type = '';
     if(auth()->guard('web')->check()){
       $user = auth()->guard('web')->user()->id;
       $type = 'user';
     }elseif(auth()->guard('astrologer')->check()){
       $user = auth()->guard('astrologer')->user()->id;
       $type = 'astrologer';
     }
     return $this->hasMany('App\OrderItem','product_id','id')->where('user_id', $user)->where('user_type', $type)->where('type', 'shop');
   }
   public function parent(){
     return $this->hasOne('App\Model\ProductCategory','id','parent_id');
   }
   public function hnsCode(){
     return $this->hasOne('App\Model\HsnCode', 'id', 'hsn_id');
   }
   public function attributes(){
     return $this->hasMany('App\Model\ProductAttribute', 'product_id', 'id');
   }
   public function variations(){
     return $this->hasMany('App\Product', 'parent_id', 'id');
   }
   public function getPAttributesAttribute(){
     $id = $this->id;
     if($this->parent_id!=0){
       $id = $this->parent_id;
     }
     return \App\Model\PAttribute::where('product_id', $id)->get();
   }
   public function video(){
     return $this->hasOne('App\Model\ProductVideo', 'product_id', 'id');
   }
   public function allAttributes($parent_id){
     return \App\Model\ProductAttribute::where('parent_id', $parent_id)->get();
   }
   public function pandit(){
     return $this->hasOne("App\Model\Pandit", "id", "pandit_id");
   }
   public function deliveryDate(){
     return $this->hasOne("App\Model\DeliveryDate", "id", "delivery_date_id");
   }

   public function apiUrl(){
     return $this->hasOne("App\Model\ApiUrl", "id", "api_url_id");
   }
   public function productAndCategoryIds(){
     return $this->hasMany("App\Model\ProductAndCategoryId","product_id","id");
   }

   public function astrologers(){
     return $this->belongsToMany('App\Model\Astrologer', 'puja_astrologers', 'product_id', 'astrologer_id')
                 ->wherePivot('status', 1)
                 ->orderByPivot('priority', 'asc')
                 ->withPivot('priority', 'status')
                 ->withTimestamps();
   }

   public function pujaAstrologerAssignments(){
     return $this->hasMany('App\Model\PujaAstrologerAssignment', 'product_id', 'id');
   }

    /**
     * Get public detail URL for this product (by type). Optional ref param for astrologer referral.
     * @param string|null $referralCode Astrologer referral code to append as ?ref=
     * @return string
     */
    public function getPublicDetailUrl($referralCode = null)
    {
        $type = (int) $this->type;
        $isOneToOne = !empty($this->is_one_to_one_puja);
        if ($type === 1) {
            $url = route('product.detail', ['product_slug' => $this->slug]);
        } elseif ($type === 2 && $isOneToOne) {
            $url = route('product.detailonetoonepuja', ['product_slug' => $this->slug]);
        } elseif ($type === 2) {
            $url = route('product.detailpuja', ['product_slug' => $this->slug]);
        } elseif ($type === 3) {
            $url = route('product.detailservice', ['product_slug' => $this->slug]);
        } else {
            $url = route('product.detail', ['product_slug' => $this->slug]);
        }
        if ($referralCode) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'ref=' . urlencode($referralCode);
        }
        return $url;
    }
}
