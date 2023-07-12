<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'text_value',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo('App\Attribute', 'attribute_id', 'id');
    }
}
