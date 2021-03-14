<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Company;
use Validator;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(){
        $company = Auth::user()->Company;
        $menu = Menu::where("id",3)->first();
        return view('company.index', compact('menu',"company")); 
    }

    public function save(Request $request){
        // dd($request->all());
        $validator = $this->validatorSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        \DB::beginTransaction();
        try {
            $company = Auth::user()->Company;
            $company->name = $request->name ;
            $company->is_register = $request->is_register ;
            $company->address = $request->address ;

            if($request->hasFile("image")){
                switch ($request->image->getMimeType()) {
                    case "image/jpeg":
                    $image_type = "jpg" ;
                    break;
                    case "image/png":
                    $image_type = "png" ;
                    break;
                }            
                $company->path_image = ".".$image_type ; 
                $company->save() ;
                $company->path_image = $company->id.".".$image_type ; 
                $company->save() ;
                saveImage($request,"/public/company/",$company->path_image);
            }else{
                $company->save() ;
            }

            \Log::info('Company Save :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/company')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/company')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "แก้ไขข้อมูลสำเร็จ !" ] ) ;
    }

    function validatorSave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'is_register' => 'required',
            'address' => 'required',
            'image' => 'mimes:jpg,png',
        ], [
            'name.required' => 'กรุณากรอกชื่อร้านค้า',
            'is_register.required' => 'กรุณาเลือกสถานะเปิดรับสมัครสมาชิก',
            'address.required' => 'กรุณากรอกที่อยู่ร้านค้า',
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
        ]);

        return $validator;
    }
}
