<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class OrderProduct extends Model
{
    use HasFactory;

    public function getProductAttribute()
    {   
        $Product = Product::withTrashed()->where("id", $this->product_id)->first();
        return $Product ;
    }
}
