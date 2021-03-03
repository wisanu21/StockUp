<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Company ;
use Validator;
use Auth ;
use App\Models\Promotion ;

class OrderController extends Controller
{
    public function list(){
        $products = Product::where("company_id" , Auth::user()->company_id )->where("is_sell",1)->get();
        $menu = Menu::where("id",7)->first();
        $promotions = Promotion::where("company_id" , Auth::user()->company_id )->where("is_active_promotion",1)->get();
        return view('order.list', compact('products','menu','promotions')); 
    }

    public function createOrder($promotion_id){
        // dd($promotion_id);
        if($promotion_id != "null"){
            $promotion = Promotion::where("id" , $promotion_id )->where("is_active_promotion",1)->first();
        }else{
            $promotion = null ;
        }
        
        
        return view('order.create-order', compact('promotion')); 
    }
}
