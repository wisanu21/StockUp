<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Device;
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

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
            //dd($employee);
            $device_token = createDevice($employee->id);
            return  redirect('/home')->with('token', $device_token);
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

}


