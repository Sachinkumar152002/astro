<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vastu extends Model
{
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
    public function packageServices()
    {
    return $this->hasMany('App\Model\VastuServicePackage','service_id', 'id')->where('status', 1);
    }
    public function getBlogsAttribute()
    {
      $categories_ids = Category::where('id', $this->blog_category_id)->orWhere('parent_id', $this->blog_category_id)->groupBy('id')->pluck('id')->toArray();
      return Article::whereIn('category_id', $categories_ids)->orderBy('order', 'asc')->where('status',1)->orderBy('posted_on', 'desc')->limit(15)->get();
    }
    public function blogCategory()
    {
    return $this->hasOne('App\Model\Category','id', 'blog_category_id');
    }

}
