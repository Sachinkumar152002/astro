<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryCommission extends Model
{
    protected $table = 'category_commissions';

    protected $fillable = [
        'category_id',
        'commission_value',
        'max_cap',
        'status'
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'max_cap' => 'decimal:2',
        'status' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo('App\Model\Category', 'category_id');
    }

    public function productCategory()
    {
        return $this->belongsTo('App\Model\ProductCategory', 'category_id');
    }
}
