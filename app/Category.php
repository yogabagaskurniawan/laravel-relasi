<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','parent_id']; 

    // relasi dari tabel categories ke tabel products
    public function productCategory()
    {
        return $this->hasMany('App\ProductCategory', 'category_id', 'id');
    }
}
