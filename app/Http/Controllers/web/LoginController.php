<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Device;
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('login.index');
    }

    public function login(Request $request){
        $validator = $this->validatorLogin($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // $this->loginByDevice();
        $employee = Employee::where("mobile",$request->phone_number)->where("password",$request->password)->first();
        if($employee){
            if($employee->is_active == 1 && $employee->level_id != null){
                Auth::login($employee);
                if(Auth::check()){
                    return  redirect('/home');
                }else{
                    return back() ;
                }
            }else{
                $validator->errors()->add('field', 'กรุณารอการอนุมัติจากระบบ');
                if ($validator->errors()->any()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }

        }else{
            $validator->errors()->add('field', 'เบอร์โทรศัพท์ หรือ รหัสผ่าน ผิด');
            if ($validator->errors()->any()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
    }

    public function validatorLogin($request){
        $validator = Validator::make(
            $request->all(), [
            'phone_number' => 'required',
            'password' => 'required',
        ], [
            'phone_number.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);
        return $validator;
    }

    public function logout(){
        Auth::logout();
        return  redirect('/');
    }

}


