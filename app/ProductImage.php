<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
}
