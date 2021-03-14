<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Company;

class RegisterCompanyController extends Controller
{
    public function index(){
        return view('register-company.index'); 
    }

    public function registerCompanySave(Request $request){
        // dd($request);
        $validator = $this->validatorRegisterCompanySave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        \DB::beginTransaction();
        try {
            $company = new Company() ;
            $company->name = $request->name ;
            $company->is_active = "0" ;
            $company->is_register = "1" ;
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
            
            \Log::info('registerCompanySave :',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/register-company')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/register')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลงทะเบียนร้านค้าสำเร็จ กรุณารอผู้ดูแลระบบตรวจสอบเพื่อยืนยัน !" ] ) ;
    }

    function validatorRegisterCompanySave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'address' => 'required',
            'image' => 'required|mimes:jpg,png',
        ], [
            'name.required' => 'กรุณากรอกชื่อร้านค้า',
            'address.required' => 'กรุณากรอกที่อยู่ร้านค้า',
            'image.required' => "กรุณาเลือกรูปภาพ",
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
        ]);

        return $validator;
    }
}
