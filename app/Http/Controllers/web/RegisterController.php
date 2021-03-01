<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\MessageBag;
use App\Models\Employee;
use App\Models\Company;

class RegisterController extends Controller
{
    public function index(){
        $companies = Company::where("is_active",1)->where("is_register",1)->pluck('name','id');
        // dd($companies);
        $companies = addNullInSelect($companies,"--กรุณาเลือกร้านค้า --");
        return view('register.index', compact('companies')); 
    }

    public function register(Request $request){
        $validator = $this->validatorRegister($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        if($request->password != $request->repeat_password){
            $validator->errors()->add('field', 'ยืนยันรหัสผ่านไม่ตรงกัน');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $employee = Employee::where("mobile",$request->mobile)->first() ;
        if($employee){
            $validator->errors()->add('field', 'กรุณากรอกเบอร์โทรศัพท์ใหม่');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        \DB::beginTransaction();
        try {
            
            $employee = new Employee();
            $employee->first_name = $request->first_name ;
            $employee->last_name = $request->last_name ;
            $employee->easy_name = $request->easy_name ;
            $employee->mobile = $request->mobile ;
            $employee->password = $request->password ;
            $employee->company_id = $request->company ;
            $employee->is_active = false ;
            $employee->save();

            \Log::info('register Employee ',$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            // return response()->json(['error'=>'error']);
            return redirect('/')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }
        // dd("dfs");
        return redirect('/')->with('response', [ "status" => "success" , "title" => "ยินดีด้วย" , "detail" => "สมัครสมาชิกสำเร็จ กรุณารอการอนุมัติจากระบบ !" ] ) ;
    }

    public function validatorRegister($request){
        $validator = Validator::make(
            $request->all(), [
            'company' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'easy_name' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'repeat_password' => 'required',
        ], [
            'company.required' => 'กรุณาเลือกร้านค้า',
            'first_name.required' => 'กรุณากรอกชื่อจริง',
            'last_name.required' => 'กรุณากรอกนามสกุล',
            'easy_name.required' => 'กรุณากรอกชื่อเล่น',
            'mobile.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'repeat_password.required' => 'กรุณากรอกยืนยันรหัสผ่าน',
        ]);
        return $validator;
    }

}
