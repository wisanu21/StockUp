<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Menu;

class MenuSeeder extends Seeder
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
            DB::table('menus')->delete();
            // DB::table('levels')->truncate();
            // DB::raw("INSERT INTO 'level' ('id', 'name') VALUES (0, 'ไม่ได้');");
            // DB::table("level")->create(['id'=>0, 'name'=>'ไม่ได้']);
            Menu::create(['id'=>1,'name'=>'จัดการพนักงาน','is_active'=>'1']);
            Menu::create(['id'=>2,'name'=>'จัดการร้านค้า','is_active'=>'1']);
            Menu::create(['id'=>3,'name'=>'จัดการสินค้า','is_active'=>'1']);
            Menu::create(['id'=>4,'name'=>'จัดการข้อมูลส่วนตัว','is_active'=>'1']);
            // test git
            DB::commit();
        }catch (Exception $e){
            echo "there's error";
            DB::rollBack();
            Log::info($e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
