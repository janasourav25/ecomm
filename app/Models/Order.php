<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Order extends Model{

    use HasFactory;

    protected $fillable = ['user_id', 'order_number','total_amount',
    'payment_method','payment_status','order_status','currency','shipping_amount','shipping_method','notes'];
   
    public function user(){
        return $this->belongsTo(User::class);
    } 

    public function items(){
        return $this->hasMany(OrderItem::class);
    } 

    public function address(){
        return $this->hasOne(Address::class);
    } 

// Order model
public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

public function productVariant()
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
}

    
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($order) {
        // Generate order number
        $order->order_number = 'ORD-' . time() . '-' . rand(1000,9999);
        
        // Calculate total
        $orderItems = Session::get('cart_items');
        $total = collect($orderItems)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        
        $order->total_amount = $total + ($order->shipping_amount ?? 0);
    });
}

}