<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function attributeOptions()
    {
        return $this->hasMany('App\AttributeOption', 'attribute_id', 'id');
    }

    public function productAttributeValue()
    {
        return $this->hasMany('App\ProductAttributeValue', 'attribute_id', 'id');
    }
}
