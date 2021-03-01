<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Company ;
use Validator;
use Auth ;

class ProductController extends Controller
{
    // const $num_menu = 4 ;
    public function list(){
        $products =  Product::all();
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
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'is_sell.required' => 'กรุณาเลือกสถานะสินค้า',
            'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
            // 'image.size' => "กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 512 kilobytes"
        ]);
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
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'is_sell.required' => 'กรุณาเลือกสถานะสินค้า',
            // 'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
            // 'image.size' => "กรุณาเลือกรูปภาพที่มีขนาดไม่เกิน 512 kilobytes"
        ]);
        return $validator;
    }

    public function detail($id){
        $product = Product::where("id",$id)->first();
        return  view('product.detail', compact('product'));
    }

    public function delete($id){
        \DB::beginTransaction();
        try {
            Product::where("id",$id)->delete();
            \Log::info('delete id product : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/product')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/product')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบสินค้าสำเร็จ !" ] ) ;
    
    }
}
