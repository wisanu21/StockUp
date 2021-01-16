<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Str;

class ValidationPassportController extends Controller
{
    function ValidationPassport(Request $request){
        // return response()->json(['state'=>'successfully','token' => 'dfgdfg' ]);
        $device = Device::where('identifier',$request->identifier)
                ->where('os',$request->os)
                ->where('token',$request->token)
                ->where('employee_id',$request->employee_id)
                ->first();
        $old_d = $device ;        
        if($device){
            // $device->token = Str::random(25) ;
            // $device->save();
            return response()->json(['state'=>'successfully','token' => $device->token ]);
        }else{
            return response()->json(['state'=>'failed']); 
        }
    }
}
