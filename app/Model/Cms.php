<?php

namespace App\Model;
use App\Model\TemplateContent;
use App\Model\TemplateMeta;
use App\Model\TemplateFaq;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    public function questions(){
        return $this->hasMany('App\Model\QuestionAnswer', 'page_id', 'id');
    }
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function getPageContentAttribute(){
        $content = '';
        $tContent = TemplateContent::where('status',1)->where('overwrite', 'like', '%"'.$this->template.'"%')->orderBy('updated_at', 'desc')->first();
        if($tContent){
            $content = $tContent->content;
        }
        return $content;
    }
    public function getMetaAttribute(){
        $title = $this->meta_title?$this->meta_title:$this->name;
        $keywords = $this->meta_keywords;
        $description = $this->meta_description;
        $tMeta = TemplateMeta::where('status',1)->where('overwrite', 'like', '%"'.$this->template.'"%')->orderBy('updated_at', 'desc')->first();
        if($tMeta){
            $title = $tMeta->meta_title;
            $keywords = $tMeta->meta_keywords;
            $description = $tMeta->meta_description;
        }
        return ['title'=>$title, 'keywords'=>$keywords, 'description'=>$description];
    }

    public function getVariableAttribute() {
        $variables = [
            'NAME'=> '',
            'CURRENT_URL' => '',
            'ADDRESS'=> '',
            'CITY'=> '',
            'STATE'=> '',
            'COUNTRY'=> '',
            'ZIPCODE'=> '',
            'PARENT_URL'=> '',
            'PARENT_NAME'=> '',
            'PHONE'=> '',
            'WEBSITE'=> '',
            'COUNT_DESTINATIONS'=> '',
        ] ;
            $variables['NAME']= $this->name;
            $variables['CURRENT_URL']= route('pages', $this->slug);

            return $variables;
    }

    public function getFaqsAttribute(){
        $faqs = TemplateFaq::where('status',1)->where('overwrite', 'like', '%"'.$this->template.'"%')->orderBy('order', 'asc')->get();
        return $faqs;
    }

    public function city(){
        return $this->hasOne('App\Model\City', 'page_id', 'id');
    }
    public function hotel(){
        return $this->hasOne('App\Model\Hotel', 'page_id', 'id');
    }

    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
}
