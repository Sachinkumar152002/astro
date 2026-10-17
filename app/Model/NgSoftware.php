<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NgSoftware extends Model
{
    use HasFactory;
    protected $table='ng_softwares';
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
    public function packages()
    {
    return $this->hasMany('App\Model\ModulePackage','data_id', 'id');
    }
    public function features()
    {
    return $this->hasMany('App\Model\ModuleFeature','data_id', 'id');
    }
    public function procedures()
    {
    return $this->hasMany('App\Model\ModuleProcedure','data_id', 'id');
    }
    public function videos()
    {
    return $this->hasMany('App\Model\DemoVideo','page_id', 'id');
    }
    public function getBlogsAttribute()
    {
        $categories_ids = Category::where('id', $this->blog_category_id)->orWhere('parent_id', $this->blog_category_id)->groupBy('id')->pluck('id')->toArray();
        return Article::whereIn('category_id', $categories_ids)->orderBy('order', 'asc')->where('status', 1)->orderBy('posted_on', 'desc')->limit(15)->get();
    }
    public function blogCategory()
    {
    return $this->hasOne('App\Model\Category','id', 'blog_category_id');
    }
}
