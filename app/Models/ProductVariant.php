<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'quantity',
        'color',
        'size',
        'images',
        'status',
        'in_stock',
        'on_sale',
    ];

     // Relationship to Product: A product variant belongs to a product
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
