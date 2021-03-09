<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth ;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Menu;

class StockController extends Controller
{
    public function addSaveByProduct($request , $product_id){

        $stock = new Stock();
        $stock->name = $request->name ;
        $stock->number = $request->number ;
        $stock->product_id = $product_id ;
        $stock->company_id = Auth::user()->company_id ;
        $stock->adder_id = Auth::user()->id ;

        switch ($request->image->getMimeType()) {
            case "image/jpeg":
            $image_type = "jpg" ;
            break;
            case "image/png":
            $image_type = "png" ;
            break;
        }

        $stock->image_path = ".".$image_type ; 
        $stock->save() ;
        $stock->image_path = $stock->id.".".$image_type ; 
        $stock->save() ;
        saveImage($request,"/public/stock/",$stock->image_path);

        \Log::info('add Save stock id :'.$stock->id);
    }

    public function editSaveByProduct($request , $product_id){
        // $raw_material = RawMaterial::where("product_id",$product_id)->first();
        if($request->is_stock == "0"){  //ไม่ต้องการผูก
            Stock::where("product_id",$product_id)->delete();
            \Log::info('delete stock product_id is :'.$product_id);
        }else{
            $stock = Stock::where("product_id",$product_id)->first();
            if($stock != null){  //มีอยู่แล้ว
                $stock->number = $request->number ; 
                $stock->save() ;
            }else{  //สร้างใหม่
                $product = Product::where("id",$product_id)->first(); 
                $stock = new Stock();
                $stock->name = $product->name ;
                $stock->number = $request->number ;
                $stock->product_id = $product_id ;
                $stock->company_id = Auth::user()->company_id ;
                $stock->adder_id = Auth::user()->id ;
                $type = (explode(".",$product->image_path))[1];
                $stock->image_path = ".".$type ; 
                $stock->save() ;
                $stock->image_path = $stock->id.".".$type ; 
                $stock->save() ;
                Storage::copy('/public/product/'.$product->image_path,'public/stock/'.$stock->image_path);
            }
            \Log::info('edit Save stock id :'.$stock->id);

        }
    }

    public function list(){
        $stocks =  Stock::where("company_id" , Auth::user()->company_id )->get();
        $menu = Menu::where("id",8)->first();
        return view("stock.list", compact('stocks','menu'));
    }

    public function add(){
   
        return view("stock.add");
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
            $stock = new Stock();
            $stock->name = $request->name ;
            $stock->number = $request->number ;
            $stock->company_id = Auth::user()->company_id ;
            $stock->adder_id = Auth::user()->id ;
            $stock->product_id = null ;

            switch ($request->image->getMimeType()) {
                case "image/jpeg":
                $image_type = "jpg" ;
                break;
                case "image/png":
                $image_type = "png" ;
                break;
            }

            $stock->image_path = ".".$image_type ; 
            $stock->save() ;
            $stock->image_path = $stock->id.".".$image_type ; 
            $stock->save() ;
            
            saveImage($request,"/public/stock/",$stock->image_path);

            \Log::info('add Save stock :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/manage-stock')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/manage-stock')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "เพิ่มของในสต๊อกสำเร็จ !" ] ) ;


    }

    public function validatorAddSave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'number' => 'required',
            'image' => 'required|mimes:jpg,png',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'number.required' => 'กรุณากรอกราคาสินค้า',
            'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
        ]);
        return $validator;
    }

    public function detail($id){
        $stock = Stock::where("id",$id)->first();
        return  view('stock.detail', compact('stock'));
    }

    public function edit($id){
        $stock = Stock::where("id",$id)->first();
        return view("stock.edit", compact('stock'));
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
            $stock = Stock::where("id",$request->id)->first(); 
            $stock->name = $request->name ;
            $stock->number = $request->number ;
            $stock->adder_id = Auth::user()->id ;
            if($request->hasFile("image")){
                switch ($request->image->getMimeType()) {
                    case "image/jpeg":
                    $image_type = "jpg" ;
                    break;
                    case "image/png":
                    $image_type = "png" ;
                    break;
                }            
                $stock->image_path = ".".$image_type ; 
                $stock->save() ;
                $stock->image_path = $stock->id.".".$image_type ; 
                $stock->save() ;
                saveImage($request,"/public/stock/",$stock->image_path);
            }else{
                $stock->save() ;
            }

            \Log::info('edit Save stock :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/manage-stock')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/manage-stock')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "แก้ไขของในสต๊อกสำเร็จ !" ] ) ;
    
    }

    public function validatorEditSave($request){
        $validator = Validator::make(
            $request->all(), [
            'id' => 'required',
            'name' => 'required',
            'number' => 'required',
            'image' => 'mimes:jpg,png',

        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'number.required' => 'กรุณากรอกราคาสินค้า',
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
        ]);

        return $validator;
    }

    public function delete($id){
        \DB::beginTransaction();
        try {
            $stock = Stock::where("id",$id)->first();
            if($stock->product_id != null){
                $product = Product::where("id",$stock->product_id)->first();
                if($product != null){
                    $product->is_stock = "0" ;
                    $product->save();
                }
            }
            Stock::where("id",$id)->delete();
            \Log::info('delete id stock : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/manage-stock')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/manage-stock')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบของในสต๊อกสำเร็จ !" ] ) ;
    
    }

}
