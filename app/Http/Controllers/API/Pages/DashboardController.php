<?php

namespace App\Http\Controllers\API\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Device;
use App\Models\Level ;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
Use DB;

class DashboardController extends Controller
{
    public function getDataDashboard(Request $request){
        if(Device::where('token',$request->token)->where('employee_id',$request->employee_id)->first()){
            $employee = Employee::where('id',$request->employee_id)->first();
            if($employee){
                $company = Company::where('id',$employee->company_id)->first();
                $level = Level::where('id',$employee->level_id)->first();
                if($company && $level){
                    return response()->json(['state'=>'successfully' , 'employee'=>$employee , 'company' => $company , 'level' => $level ]); 
                }
            }
        }

        return response()->json(['state'=>'failed']);  
    } 

    public function image(){

        $path = public_path().'/storage/company/1.png';
        // dd($path);
        return Response::download($path);        
    }
}
