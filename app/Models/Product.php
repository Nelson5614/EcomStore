<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'quantity',
        'is_published',
        'instock',
        'create_by',
        'updated_by',
        'deleted_by',
        'brand_id',
        'category_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
