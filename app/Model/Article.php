<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function user()
    {
    return $this->hasOne('App\User','id', 'user_id');
    }
    public function admin()
    {
    return $this->hasOne('App\Admin','id', 'admin_id');
    }

    public function category(){
      return $this->hasOne('App\Model\Category', 'id', 'category_id');
    }

    public function getYoutubeIdAttribute(){
        $url = $this->youtube;
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

    public function slides()
    {
        return $this->hasMany('App\Model\SlideShowArticle', 'article_id', 'id')->select('title', 'media_id', 'description','image');
    }

    public function likes(){
        return $this->hasMany('App\Model\LikeDislike', 'article_id')->where('type', 'like');
    }

    public function dislikes(){
        return $this->hasMany('App\Model\LikeDislike', 'article_id')->where('type', 'dislike');
    }

    public function getLikeCountAttribute(){
        return $this->likes()->count();
    }
    public function getDisLikeCountAttribute(){
        return $this->likes()->count();
    }
}
