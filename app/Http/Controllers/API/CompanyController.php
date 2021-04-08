<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;

class CompanyController extends Controller
{
    public function getAllCompanys(){
        \Log::info('getAllCompanys ');
        $companys = Company::get() ;
        return response()->json($companys);
    }

    public function getCompanysByIsRegister(){
        \Log::info('getCompanysByIsRegister ');
        $companys = Company::where('is_register',1)->get() ;
        return response()->json($companys);
    }

    public function saveRegister(Request $request){

        if(count(Employee::where('company_id',$request->select_company)->get()) == 0 ){
            $employee_level = 1 ;   //คนแรกเจ้าของร้าน
            $is_active = true ;    //เข้าได้เลย
        }else{
            $employee_level = null ;
            $is_active = false ;
        }

        \DB::beginTransaction();
        try {
            $employee = new Employee();
            $employee->first_name = $request->text_fname ;
            $employee->last_name = $request->text_lname ;
            $employee->mobile = $request->text_phone ;
            $employee->password = $request->text_password ;
            $employee->company_id = $request->select_company ;
            $employee->easy_name = $request->text_easy_name ;
            $employee->easy_name = $request->text_easy_name ;
            $employee->level_id = $employee_level;
            $employee->is_active = $is_active;

            $employee->save();
            \Log::info('saveRegister employee id :'.$employee->id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            // return response()->json(['error'=>'error']);
            return response()->json(['state'=>'error','detail'=> $e->getMessage() ]);
        }
        
        if($is_active == true){
            return response()->json(['state'=>'successfully','detail' => 'สามารถเข้าสู่ระบบได้']);
        }else{
            return response()->json(['state'=>'successfully','detail' => 'รอเจ้าของร้านอนุญาต จึงสามารถเข้าสู่ระบบ']);  
        }
    }

    // public function getCompanyByEmployeeId(Request $request){
    //     $employee = Employee::where('id',$request->employee_id)->first();
    //     $companys = Company::where('id',$employee->company_id)->first() ;
    //     return response()->json($companys); 
    // }
}
