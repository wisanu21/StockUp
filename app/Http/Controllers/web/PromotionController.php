<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion ;
use App\Models\Menu ;
use Validator;
use Auth ;

class PromotionController extends Controller
{
    public function list(){
        $promotions =  Promotion::all();
        $menu = Menu::where("id",6)->first();
        return view('promotion.list', compact('promotions','menu'));
    }

    public function add(){
        return view('promotion.add');
    }

    public function addSave(Request $request){
        $validator = $this->validatorAddSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Promotion::where("is_active_code",1)->where("is_active_promotion",1)->where("code",$request->code)->first()){
            $validator->errors()->add('field', 'กรุณากรอกโค้ดส่วนลดอีกครั้ง เพราะมีโค้ดซ้ำ!');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        \DB::beginTransaction();
        try {
            $promotion = new Promotion();
            $promotion->name = $request->name ;
            $promotion->price_or_percentage = $request->price_or_percentage ;
            $promotion->resource = $request->resource ;
            $promotion->is_active_promotion = $request->is_active_promotion ;
            $promotion->code = $request->code ;
            $promotion->is_active_code = $request->is_active_code ;
            $promotion->company_id = Auth::user()->company_id ;
            $promotion->adder_id = Auth::user()->id ;
            $promotion->save() ;

            \Log::info('add Save Promotion :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/promotion')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/promotion')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "เพิ่มโปรโมชั่นสำเร็จ !" ] ) ;

    }

    public function validatorAddSave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'resource' => 'required',
            'price_or_percentage' => 'required',
            'is_active_promotion' => 'required',
            'code' => 'required',
            'is_active_code' => 'required',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'resource.required' => 'กรุณากรอกจำนวนส่วนลด',
            'price_or_percentage.required' => 'กรุณาเลือกหน่วยส่วนลด',
            'is_active_promotion.required' => 'กรุณาเลือกสถานะการใช้งานโปรโมชั่น',
            'code.required' => 'กรุณากรอกโค้ดส่วนลด',
            'is_active_code.required' => 'กรุณาเลือกสถานะการใช้งานโค้ดส่วนลด',
        ]);
        return $validator;
    }

    public function detail($id){
        $promotion = Promotion::where("id",$id)->first();
        return  view('promotion.detail', compact('promotion'));
    }

    public function edit($id){
        $promotion = Promotion::where("id",$id)->first();
        return  view('promotion.edit', compact('promotion'));
    }

    public function editSave(Request $request){
        $validator = $this->validatorAddSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Promotion::where("id","!=",$request->id)->where("is_active_code",1)->where("is_active_promotion",1)->where("code",$request->code)->first()){
            $validator->errors()->add('field', 'กรุณากรอกโค้ดส่วนลดอีกครั้ง เพราะมีโค้ดซ้ำ!');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        \DB::beginTransaction();
        try {
            $promotion = Promotion::where("id",$request->id)->first();
            $promotion->name = $request->name ;
            $promotion->price_or_percentage = $request->price_or_percentage ;
            $promotion->resource = $request->resource ;
            $promotion->is_active_promotion = $request->is_active_promotion ;
            $promotion->code = $request->code ;
            $promotion->is_active_code = $request->is_active_code ;
            $promotion->company_id = Auth::user()->company_id ;
            $promotion->adder_id = Auth::user()->id ;
            $promotion->save() ;

            \Log::info('add Save Promotion :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/promotion')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/promotion')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "เพิ่มโปรโมชั่นสำเร็จ !" ] ) ;

    }

    public function delete($id){
        \DB::beginTransaction();
        try {
            Promotion::where("id",$id)->delete();
            \Log::info('delete id Promotion : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/promotion')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/promotion')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบสินค้าสำเร็จ !" ] ) ;
    
    }

}
