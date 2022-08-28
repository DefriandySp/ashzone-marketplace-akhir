<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = ['id','name', 'parent_id', 'slug'];

    public function parent()
    {
        return $this->belongsTo(Brand::class);
    }

    public function child()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }

    public function scopeGetParent($query)
    {
        //parent berarti whereNull('parent_id')
        return $query->whereNull('parent_id');
    }

    //MUTATOR
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    //ACCESSOR
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
