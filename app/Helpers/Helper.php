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

?>