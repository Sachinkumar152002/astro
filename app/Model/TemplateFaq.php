<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TemplateFaq extends Model
{
    public function pages(){
      return $this->hasMany('App\Model\OnPage', 'table_id', 'id')->where('table', 'template_faqs');
    }
}
