<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RawMaterial ;

class Product extends Model
{
    use HasFactory , SoftDeletes;

    
    public function getStockAttribute(){
        return stock::where("product_id",$this->id)->first();
    }

}
