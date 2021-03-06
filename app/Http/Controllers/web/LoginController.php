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
use App\Models\Menu;

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

    public function loginRestrictedArea(Request $request){
        \DB::beginTransaction();
        \Log::info('loginRestrictedArea user id is '.Auth::user()->id ,$request->input());
        try {
            if(isset($request->data["user_id"]) && isset($request->data["password"])){
                if(Employee::where("id",$request->data["user_id"])->where("password",$request->data["password"])->first() != null){
                    $menu = Menu::where("id",$request->data["menu_id"])->first();
                    if($menu !=null){
                        // return  [ "status" => "success" , "url" => url("/manage-users") ] ; 
                        return  [ "status" => "success" , "url" => url($menu->header_url) ] ; 
                    }
                }else{
                    return  [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => "ไม่ได้แดกกูหรอก" ] ; 
                }
                
            }else{
                return  [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => "ไม่ได้แดกกูหรอก" ] ; 
            }
            // \Log::info('loginRestrictedArea user id is '.Auth::user()->id ,$request->input());
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            // return response()->json(['error'=>'error']);
            return [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => "ไม่ได้แดกกูหรอก" ] ;
        }
    }
}


