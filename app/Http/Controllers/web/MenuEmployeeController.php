<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Models\Menu;
use App\Models\MenuEmployee;
use App\Models\Level;

class MenuEmployeeController extends Controller
{
    public function addMenuToUser($user_id){
        \DB::beginTransaction();
        try {
            // dd("test");
            MenuEmployee::where("employee_id" , $user_id)->delete();
            $user = User::where("id",$user_id)->first();
            switch ($user->level_id) {
                case "1":
                    $this->addMenuAdmin($user_id);
                    break;
                case "2":
                    $this->addMenuShopkeeper($user_id);
                    break;
                case "3":
                    $this->addMenuHeadOfEmployee($user_id);
                    break;
                case "4":
                    $this->addMenuEmployee($user_id);
                    break;
                // default:
                //   echo "Your favorite color is neither red, blue, nor green!";
            }
            \Log::info('addMenuToUser id User : '.$user_id);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::info($e->getMessage() ."\n" . $e->getTraceAsString());
            // return redirect('/manage-users')->with('response', [ "status" => "error" , "title" => "เกิดข้อผิดพลาด" , "detail" => $e->getMessage() ."\n" . $e->getTraceAsString() ] ) ;
        }
    }

    function addMenuAdmin($user_id){
        foreach (Menu::all() as $key => $menu) {
            $menu_employee = new MenuEmployee() ;
            $menu_employee->employee_id = $user_id ;
            $menu_employee->menu_id = $menu->id ;
            $menu_employee->save();
        }
    }

    function addMenuShopkeeper($user_id){
        foreach (Menu::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 10])->get() as $key => $menu) {
            $menu_employee = new MenuEmployee() ;
            $menu_employee->employee_id = $user_id ;
            $menu_employee->menu_id = $menu->id ;
            $menu_employee->save();
        }
    }

    function addMenuHeadOfEmployee($user_id){
        foreach (Menu::whereIn('id', [2, 4, 5, 6, 7, 8, 10])->get() as $key => $menu) {
            $menu_employee = new MenuEmployee() ;
            $menu_employee->employee_id = $user_id ;
            $menu_employee->menu_id = $menu->id ;
            $menu_employee->save();
        }
    }

    function addMenuEmployee($user_id){
        foreach (Menu::whereIn('id', [ 4, 5, 7, 8])->get() as $key => $menu) {
            $menu_employee = new MenuEmployee() ;
            $menu_employee->employee_id = $user_id ;
            $menu_employee->menu_id = $menu->id ;
            $menu_employee->save();
        }
    }
}
