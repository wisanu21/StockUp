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

function saveImage($request , $folder , $file_name){
    //เซฟรูป request ที่มีไฟส์ชื่อ 'file' , ชื่อตัวแปลที่เก็บ file , ชื่อ folder ที่ต้องการเซฟ
    if ($request->hasFile("image")) { //save image
        $request->file("image")->storeAs($folder, $file_name); //save image
    }
}

?>