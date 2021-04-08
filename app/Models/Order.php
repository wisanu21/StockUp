<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;

    public function getOrderProductAttribute()
    {   
        $order_products = OrderProduct::where("order_id", $this->id)->get();
        return $order_products ;
    }
}
