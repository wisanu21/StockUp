<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee ;
use App\Models\User ;
use App\Models\Menu;
use App\Models\Level;
use Validator;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function list(){
        $employees = Employee::where("company_id", Auth::user()->Company->id)->get();
        $menu = Menu::where("id",2)->first();
        return view("employee.list", compact('menu','employees')); 
    }

    public function edit($id){
        $employee = Employee::where("id",$id)->first();
        $levels = addNullInSelect(Level::where("id","!=",1)->pluck('name','id'),"-- กรุณาเลือกตำแหน่ง --");
        return view("employee.edit", compact('employee','levels')); 
    }

    public function editSave(Request $request){
        $validator = $this->validatorEditSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee = Employee::where("id","!=",$request->id)->where("mobile",$request->mobile)->first() ;
        if($employee){
            $validator->errors()->add('mobile', 'กรุณากรอกเบอร์โทรศัพท์ใหม่');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        \DB::beginTransaction();
        try {
            $employee = Employee::where("id",$request->id)->first();
            $employee->first_name =  $request->first_name ;
            $employee->last_name =  $request->last_name ;
            $employee->easy_name =  $request->easy_name ;
            $employee->mobile =  $request->mobile ;
            $employee->is_active =  $request->is_active ;
            $employee->level_id =  $request->level_id ;
            $employee->save();

            (new MenuEmployeeController())->addMenuToUser($employee->id);
            \Log::info('EmployeeController editSave id employee : '.$employee->id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/employee')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }
        return redirect('/employee')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "แก้ไขข้อมูลสำเร็จ !" ] ) ;
    }

    function validatorEditSave($request){
        if($request->is_active == '1'){
            $validator = Validator::make(
                $request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'easy_name' => 'required',
                'mobile' => 'required',
                'is_active' => 'required',
                'level_id' => 'required',
            ], [
                'first_name.required' => 'กรุณากรอกชื่อจริง',
                'last_name.required' => 'กรุณากรอกนามสกุล',
                'easy_name.required' => 'กรุณากรอกชื่อเล่น',
                'mobile.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'is_active.required' => 'กรุณาเลือกสถานะใช้งาน',
                'level_id.required' => 'กรุณาเลือกตำแหน่ง',
            ]);
        }else{
            $validator = Validator::make(
                $request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'easy_name' => 'required',
                'mobile' => 'required',
                'is_active' => 'required',
                // 'level_id' => 'required',
            ], [
                'first_name.required' => 'กรุณากรอกชื่อจริง',
                'last_name.required' => 'กรุณากรอกนามสกุล',
                'easy_name.required' => 'กรุณากรอกชื่อเล่น',
                'mobile.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'is_active.required' => 'กรุณาเลือกสถานะใช้งาน',
                // 'level_id.required' => 'กรุณาเลือกตำแหน่ง',
            ]);
        }
        return $validator;
    }

    public function delete($id){
        \DB::beginTransaction();
        try {
            User::where("id",$id)->delete();
            \Log::info('EmployeeController delete id User : '.$id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return redirect('/employee')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }

        return redirect('/employee')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "ลบ User สำเร็จ !" ] ) ;
    }

    public function changePassword(Request $request){
        $validator = $this->validatorChangePassword($request);
        if ($validator->errors()->any()) {
            return [ "status" => "warning" , "title" => "กรุณากรอกข้อมูล!" , "detail" => implode(" , ",$validator->errors()->all()) ] ;
        }
        if($request["items"]["new-password"] != $request["items"]["new-password2"]){
            return [ "status" => "warning" , "title" => "กรุณากรอกข้อมูล!" , "detail" => "รหัสผ่านไม่เหมือนกัน" ] ;
        }else{
            \DB::beginTransaction();
            try {
                $user = User::where("id",$request->id)->first();
                $user->password = $request["items"]["new-password"] ;
                $user->save();
                
                \Log::info('changePassword id User : '.$request->id);
                \DB::commit();
                return [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "เปลี่ยนรหัสผ่านสำเร็จ" ] ;
            } catch (\Throwable $e) {
                \DB::rollBack();
                \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
                return [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ;
            }
        }
        
    }

    public function validatorChangePassword($request){
        $validator = Validator::make(
            $request->all()["items"], [
            // 'old-password' => 'required',
            'new-password' => 'required',
            'new-password2' => 'required',
        ], [
            // 'old-password.required' => 'กรุณากรอกรหัสผ่านเดิม',
            'new-password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'new-password2.required' => 'กรุณากรอกยืนยันรหัสผ่านใหม่',
        ]);

        return $validator ;
    }

}
