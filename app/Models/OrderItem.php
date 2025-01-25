<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id','product_variant_id',
    'quantity','price','total'];
   
    public function order(){
        return $this->belongsTo(Order::class);
    } 

    public function product(){
        return $this->belongsTo(Product::class);
    } 

     // Change productVariant to variant to match your Filament form
     public function variant()
     {
         return $this->belongsTo(ProductVariant::class, 'product_variant_id');
     }
}
