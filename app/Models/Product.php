<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Category; 
use App\Models\OrderItems;
use App\Models\ProductVariant;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'brand_id','name','slug','short_description',
    'default_images','description','status','featured'];
    protected $cast = [
        'default_images' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItems::class);
    }

    public function productVariant(){
        return $this->hasMany(ProductVariant::class);
    }
}
