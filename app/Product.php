<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'parent_id',
        'user_id',
        'sku',
        'name',
        'slug',
        'price',
        'weight',
        'description',
    ];

    // relasi dengan user 
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    // relasi dengan tabel itu sendiri 
    public function variants()
    {
        return $this->hasMany('App\Product', 'parent_id');
    }
    public function parent()
    {
        return $this->BelongsTo('App\Product', 'parent_id');
    }

    // relasi ke tabel product_images
    public function productImages()
    {
        return $this->hasMany('App\productImage', 'product_id', 'id');
    }

    // relasi dari product ke productInventory 
    public function productInventory()
    {
        return $this->hasOne('App\ProductInventory', 'product_id', 'id');
    }

    // relasi dari product ke productAttributeValue
    public function productAttributeValues()
    {
        return $this->hasMany('App\ProductAttributeValue', 'product_id', 'id');
    }
}
