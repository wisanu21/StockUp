<?php 

function showError($name,$errors)
{
    if($errors->has($name)){
        return '<span class="text-danger">'.$errors->first($name).'</span>' ;
    }
}

function addNullInSelect($arr_selects , $text){
    $value_selects[null] = $text ;
    foreach ($arr_selects as $key => $arr_select) {
        $value_selects[$key] = $arr_select ;
    }
    return $value_selects ;
}

function findEmployeeIdByToken($token){
    $device = App\Models\Device::where("token",$token)->first();
    if($device){
        return $device->employee_id ;
    }else{
        return response()->json(['error'=>'error']);
    }
    
}

function createDevice($employee_id){

    \DB::beginTransaction();
    try {
        App\Models\Device::where("employee_id",$employee_id)->delete();
        $device = new App\Models\Device();
        $device->employee_id = $employee_id ;
        $device->os = "-" ;
        $device->identifier = "-" ;
        $device->token = Illuminate\Support\Str::random(60);
        $device->save();

        \Log::info('createDevice '.$employee_id);
        \DB::commit();
    } catch (\Throwable $e) {
        \DB::rollBack();
        \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
        return response()->json(['error'=>'error']);
    }

    return $device->token ;
}

?>