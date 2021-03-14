<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Validator;
use App\Models\Menu;

class ManageCompanyController extends Controller
{
    public function list(){
        $companies = Company::all();
        $menu = Menu::where("id",9)->first();
        return view('manage-company.list', compact('menu',"companies")); 
    }

    public function editCompany($id){
        $company = Company::where("id",$id)->first();
        return view('manage-company.edit-company', compact("company")); 
    }

    public function editCompanySave(Request $request){
        // dd($request->all());
        $validator = $this->validatorEditCompanySave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        \DB::beginTransaction();
        try {
            $company = Company::where("id",$request->id)->first();
            $company->name = $request->name ;
            $company->is_register = $request->is_register ;
            $company->is_active = $request->is_active ;
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
            return redirect('/manage-company')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/manage-company')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "แก้ไขข้อมูลสำเร็จ !" ] ) ;
    }

    function validatorEditCompanySave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'is_register' => 'required',
            'is_active' => 'required',
            'address' => 'required',
            'image' => 'mimes:jpg,png',
        ], [
            'name.required' => 'กรุณากรอกชื่อร้านค้า',
            'is_register.required' => 'กรุณาเลือกสถานะเปิดรับสมัครสมาชิก',
            'is_active.required' => 'กรุณาเลือกสถานะใช้งาน',
            'address.required' => 'กรุณากรอกที่อยู่ร้านค้า',
            'image.mimes' => "กรุณาเลือกรูปภาพที่มีสกุลดังนี้ jpg,bmp,png",
        ]);

        return $validator;
    }

    function delete( $id ){
        \DB::beginTransaction();
        try {
            Company::where("id",$id)->delete();
            \Log::info('delete id company : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/manage-company')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/manage-company')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบร้านค้าสำเร็จ !" ] ) ;
    
    }

}
