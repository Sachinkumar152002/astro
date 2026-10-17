<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    public function product(){
      return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function attribute(){
      return $this->hasOne('App\Model\Attribute', 'id', 'attribute_id');
    }

    public function values($parent_id,$attribute_value){
      $productIds = ProductAttribute::where('parent_id',$parent_id)->where('attribute_value',$attribute_value)->pluck('product_id')->toArray();
      $productAttributeValue = ProductAttribute::select('product_attributes.*')->join('products', 'product_attributes.product_id', '=', 'products.id')->where('products.status', 1)->where('product_attributes.parent_id',$parent_id)->where('product_attributes.attribute_value','!=',$attribute_value)->whereIn('product_attributes.product_id',$productIds)->pluck('product_attributes.attribute_value')->toArray();
      return array_map('strtolower', $productAttributeValue);
    }
}
