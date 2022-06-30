<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
 

    protected $guarded = [];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand($category_id = NULL)
    {
        $brand = DB::table('order_details a')
            ->select('b.name, c.name, a.qty')
            ->join('products b','b.id = a.product_id','left')
            ->join('categories c','c.id = b.category_id','left');

            
            if($category_id !== NULL){
            DB::where('c.id', $category_id)
            ->get();
            }
            
    }
}