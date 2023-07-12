<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $fillable = [
        'product_id',
        'stok'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
