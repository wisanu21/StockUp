<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Employee;
use App\Models\MenuEmployee ;
use App\Models\Menu;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try{
            DB::table('employees')->delete();
            // ::create(['id'=>1,'name'=>'ผู้ดูแลระบ']);
            $employee = new Employee();
            $employee->id = 1 ;
            $employee->first_name = "wisanu" ;
            $employee->last_name = "futemwong" ;
            $employee->easy_name = "pech" ;
            $employee->mobile = "admin" ;
            $employee->password = "30874" ;
            $employee->company_id = 1 ;
            $employee->is_active = 1 ;
            $employee->level_id = 1 ;
            $employee->save(); 

            DB::table('menu_employees')->delete();
            foreach (Menu::all() as $key => $menu) {
                $menu_employee = new MenuEmployee();
                $menu_employee->employee_id = 1 ;
                $menu_employee->menu_id = $menu->id ;
                $menu_employee->save();
            }
            // $menu_employee = new MenuEmployee();
            // $menu_employee->employee_id = 1 ;
            // $menu_employee->menu_id = 1 ;
            // $menu_employee->save();

            // test git 2
            DB::commit();
        }catch (Exception $e){
            echo "there's error";
            DB::rollBack();
            Log::info($e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
