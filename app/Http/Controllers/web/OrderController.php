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
use App\Models\Order ;
use App\Models\OrderProduct ;
use App\Models\Stock ;

class OrderController extends Controller
{
    public function list(){
        $products = Product::where("company_id" , Auth::user()->company_id )->where("is_sell",1)->get();
        $menu = Menu::where("id",7)->first();
        $promotions = Promotion::where("company_id" , Auth::user()->company_id )->where("is_active_promotion",1)->get();
        return view('order.list', compact('products','menu','promotions')); 
    }

    public function createOrder($promotion_id){
        // dd();
        // dd($promotion_id);
        if($promotion_id != "null"){
            $promotion = Promotion::where("id" , $promotion_id )->where("is_active_promotion",1)->first();
        }else{
            $promotion = null ;
        }
        return view('order.create-order', compact('promotion')); 
    }

    public function submitOrder(Request $request){
        if($request->items){
            $sum_price = 0 ; 
            foreach ($request->items["products"] as $key => $product) {
                $sum_price = $sum_price + ( $product["price"] * $product["number"] ) ;
            }
            $promotion = Promotion::where("id" , $request->items["promotion_id"] )->where("is_active_promotion",1)->first();
            $num_promotion = 0; 
            if($promotion != null){
                if($promotion->price_or_percentage == "price"){
                    $num_promotion = $promotion->resource ;
                }
                if($promotion->price_or_percentage == "percentage"){
                    $num_promotion = ( $sum_price / 100 ) * $promotion->resource ;
                }
            }

            $final_price = $sum_price - $num_promotion ;
            $final_price = number_format($final_price, 2, '.', '');
            // dd(( $final_price + 1 > $request->items["final_price"] && $final_price - 1 < $request->items["final_price"] ));
            if($final_price == $request->items["final_price"] || ( $final_price + 1 > $request->items["final_price"] && $final_price - 1 < $request->items["final_price"] ) ){
                $save_order = ($this->saveOrder($request ,$sum_price ,$num_promotion)) ; 
                if($save_order["status"] == "success"){
                    return [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "บันทึก Order สำเร็จ !" , "data_detail" => $save_order["detail"] ] ;
                }else{
                    return  [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => "ในการบันทึก Order !" ] ;
                }
            }else{
                return  [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => "มีการคำนวนผิดพลาด !" ] ;
            }
        }
    }
    
    public function saveOrder($request ,$sum_price ,$num_promotion){
        $is_cut_stock_by_product_error = "success" ;
        \DB::beginTransaction();
        try {
            $order = new Order();
            $order->name = Auth::user()->company_id."-".Auth::user()->id."-".date("Y-m-d-H-i-s") ;
            $order->sum_price = $sum_price ;
            $order->promotion_price = $num_promotion ;
            $order->final_price = $request->items["final_price"] ;
            $order->get_money = $request->items["get_money"] ;
            $order->change_money = $request->items["change_money"] ;
            $order->company_id = Auth::user()->company_id ;
            $order->adder_id = Auth::user()->id ;
            $order->promotion_id = $request->items["promotion_id"] ;
            $order->save();
            \Log::info('saveOrder '.$order->id);
            foreach ($request->items["products"] as $key => $product) {
                $order_product = new OrderProduct();
                $order_product->order_id = $order->id ;
                $order_product->product_id = $product["id"]; 
                $order_product->num_product = $product["number"]; 
                $order_product->price = $product["price"]; 
                $order_product->save();
                if($this->cutStockByProduct($product["id"],$product["number"]) == "error"){
                    $is_cut_stock_by_product_error = "error" ;
                }
                \Log::info('save order_product '.$order_product->id);
            }
            $order["products"] = $request->items["products"] ;
            if($is_cut_stock_by_product_error == "success"){
                \DB::commit();
                return [ "status" => "success" , "detail" => $order ]  ;
            }else{
                \Log::info('$is_cut_stock_by_product_error == "error" ');
                \DB::rollBack();
                return [ "status" => "error"]  ;
            }
            
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            // return "error" ;
            return [ "status" => "error"]  ;

        }
    }

    public function cutStockByProduct($product_id , $product_number){
        // dd(Product::where("id",$product_id)->first());
        $product = Product::where("id",$product_id)->first();
        if($product != null){
            if($product->is_stock == "1" ){
                $stock = Stock::where("product_id",$product->id)->first();
                if($stock != null){
                    $number = $stock->number - $product_number ;
                    if($number >= 0){
                        $stock->number = $number ;
                        $stock->save();
                        return "success" ;
                    }else{
                        return "error" ;
                    }
                    
                }
            }
        }
    }
}
