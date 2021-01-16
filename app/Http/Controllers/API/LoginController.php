<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Employee;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function loginByDeviceID(Request $request){
        $device = Device::where('os',$request->os)->where('identifier',$request->identifier)->first();
        if( $device ){
            try {
                $device->token = Str::random(25) ;
                $device->save();
                \Log::info('device Change token employee_id :'.$device->employee_id.' $device id :'.$device->identifier);
                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollBack();
                \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
                return response()->json(['state'=>'error','detail'=> $e->getMessage() ]);
            }
            return response()->json(['state'=>'successfully','employee_id'=>$device->employee_id , 'token' =>$device->token]); 
        }else{
            return response()->json(['state'=>'failed']); 
        }
        // return response()->json(['state '=>'successfully','detail' => 'loginByDeviceID']); 
    }

    public function login(Request $request){

        $employee = Employee::where('mobile',$request->text_phone)
                            ->where('password',$request->text_password)
                            ->where('is_active',1)
                            ->first();
        if($employee){
            try {
                // Device::where('employee_id',$employee->id)->delete();
                $device = new Device() ;
                $device->employee_id = $employee->id ;
                $device->os = $request->os ;
                $device->identifier = $request->identifier ;
                $device->token = Str::random(25) ;
                $device->save();
                \Log::info('login by Device employee id : '.$employee->id);
                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollBack();
                \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
                // return response()->json(['error'=>'error']);
                return response()->json(['state'=>'error','detail'=> $e->getMessage() ]);
            }

            return response()->json(['state'=>'successfully','detail' => 'login','employee_id' => $employee->id , 'token' =>$device->token]); 
        }else{
            return response()->json(['state'=>'failed','detail' => 'login']); 
        }

    }

    public function Logout(Request $request){
        $device = Device::where('os',$request->os)->where('identifier',$request->identifier)->first();
        try {
            if($device){
                \Log::info('delete Device employee_id :'.$device->employee_id.' $device id :'.$device->identifier);
                $device->delete();
                \DB::commit();
            }
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            return response()->json(['state'=>'error','detail'=> $e->getMessage() ]);
        }
        return response()->json(['state'=>'successfully']); 
    }

}
