<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Company ;
use Validator;
use Auth ;
use App\Models\Stock ;

class ProductController extends Controller
{
    // const $num_menu = 4 ;
    public function list(){
        $products =  Product::where("company_id" , Auth::user()->company_id )->get();
        $menu = Menu::where("id",4)->first();
        return view('product.list', compact('products','menu'));
    }

    public function add(){
   
        return view("product.add");
    }

    public function addSave(Request $request){
        
        $validator = $this->validatorAddSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        \DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name ;
            $product->price = $request->price ;
            $product->is_sell = $request->is_sell ;
            $product->company_id = Auth::user()->company_id ;
            $product->adder_id = Auth::user()->id ;
            $product->is_stock = $request->is_stock ;

            switch ($request->image->getMimeType()) {
                case "image/jpeg":
                $image_type = "jpg" ;
                break;
                case "image/png":
                $image_type = "png" ;
                break;
            }

            $product->image_path = ".".$image_type ; 
            $product->save() ;
            $product->image_path = $product->id.".".$image_type ; 
            $product->save() ;
            
            saveImage($request,"/public/product/",$product->image_path);
            if($product->is_stock == "1"){
                // addSave
                (new StockController())->addSaveByProduct($request ,$product->id);
            }
            \Log::info('add Save product :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/product')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/product')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "เพิ่มสินค้าสำเร็จ !" ] ) ;

    }

    public function validatorAddSave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'price' => 'required',
            'is_sell' => 'required',
            'image' => 'required|mimes:jpg,png',
            'is_stock' => 'required',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'is_sell.required' => 'กรุณาเลือกสถานะสินค้า',
            'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
            "is_stock.required" => 'กรุณาเลือกสถานะการตัดสต๊อก',
            // 'image.size' => "กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 512 kilobytes"
        ]);

        if ($validator->errors()->first('is_stock') == "" ) {
            if($request->is_stock == "1"){
                if($request->number == null){
                    // dd("sdsd");
                    $validator->errors()->add('number', 'กรุณากรอกจำนวนสินค้าเริ่มต้นในสต๊อก!');
                }   
            }
        }

        return $validator;
    }

    public function edit($id){
        
        $product = Product::where("id",$id)->first();
        return view("product.edit", compact('product'));
    }

    public function editSave(Request $request){
        $validator = $this->validatorEditSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        \DB::beginTransaction();
        try {
            $product = Product::where("id",$request->id)->first(); 
            $product->name = $request->name ;
            $product->price = $request->price ;
            $product->is_sell = $request->is_sell ;
            $product->company_id = Auth::user()->company_id ;
            $product->adder_id = Auth::user()->id ;
            $product->is_stock = $request->is_stock ;
            if($request->hasFile("image")){
                switch ($request->image->getMimeType()) {
                    case "image/jpeg":
                    $image_type = "jpg" ;
                    break;
                    case "image/png":
                    $image_type = "png" ;
                    break;
                }            
                $product->image_path = ".".$image_type ; 
                $product->save() ;
                $product->image_path = $product->id.".".$image_type ; 
                $product->save() ;
                saveImage($request,"/public/product/",$product->image_path);
            }else{
                $product->save() ;
            }
            (new StockController())->editSaveByProduct($request ,$product->id);

            \Log::info('edit Save product :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/product')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/product')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "แก้ไขสินค้าสำเร็จ !" ] ) ;
    }

    public function validatorEditSave($request){
        $validator = Validator::make(
            $request->all(), [
            'id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'is_sell' => 'required',
            'image' => 'mimes:jpg,png',
            'is_stock' => 'required',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'is_sell.required' => 'กรุณาเลือกสถานะสินค้า',
            // 'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
            "is_stock.required" => 'กรุณาเลือกสถานะการตัดสต๊อก',
            // 'image.size' => "กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 512 kilobytes"
        ]);

        if ($validator->errors()->first('is_stock') == "" ) {
            if($request->is_stock == "1"){
                if($request->number == null){
                    // dd("sdsd");
                    $validator->errors()->add('number', 'กรุณากรอกจำนวนสินค้าเริ่มต้นในสต๊อก!');
                }   
            }
        }

        return $validator;
    }

    public function detail($id){
        $product = Product::where("id",$id)->first();
        return  view('product.detail', compact('product'));
    }

    public function delete($id){
        \DB::beginTransaction();
        try {
            $product = Product::where("id",$id)->first();
            $product->is_stock = "0" ;
            $product->save();
            Product::where("id",$id)->delete();
            \Log::info('delete id product : '.$id);
            Stock::where("product_id",$id)->delete();
            \Log::info('delete Stock by id product : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/product')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/product')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบสินค้าสำเร็จ !" ] ) ;
    
    }
}
