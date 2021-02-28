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
        // dd($request);
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

            \Log::info('register Employee ',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return response()->json(['error'=>'error']);
        }

        return redirect('/product') ;

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
        return view("product.edit");
    }

    public function detail($id){
        $product = Product::where("id",$id)->first();
        return  view('product.detail', compact('product'));
    }

    public function delect($id){

    }
}
