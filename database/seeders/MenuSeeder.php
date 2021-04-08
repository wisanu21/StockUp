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
            Menu::create(['id'=>1,'name'=>'จัดการผู้ใช้งานระบบ','is_active'=>'1','html_icon'=>'<i class="fas fa-user-astronaut"></i>','header_url'=>"manage-users"]);
            Menu::create(['id'=>2,'name'=>'จัดการพนักงาน','is_active'=>'1','html_icon'=>'<i class="fas fa-user-ninja"></i>','header_url'=>"employee"]);
            Menu::create(['id'=>3,'name'=>'จัดการร้านค้า','is_active'=>'1','html_icon'=>'<i class="fas fa-store"></i>','header_url'=>"company"]);
            Menu::create(['id'=>4,'name'=>'จัดการสินค้า','is_active'=>'1','html_icon'=>'<i class="fas fa-box-open"></i>','header_url'=>"product"]);
            Menu::create(['id'=>5,'name'=>'จัดการข้อมูลส่วนตัว','is_active'=>'1','html_icon'=>'<i class="far fa-id-card"></i>','header_url'=>""]);
            Menu::create(['id'=>6,'name'=>'จัดการโปรโมชั่น','is_active'=>'1','html_icon'=>'<i class="fas fa-ticket-alt"></i>','header_url'=>"promotion"]);
            Menu::create(['id'=>7,'name'=>'รับ Order','is_active'=>'1','html_icon'=>'<i class="fas fa-cash-register"></i>','header_url'=>"order"]);
            Menu::create(['id'=>8,'name'=>'จัดการสต๊อก','is_active'=>'1','html_icon'=>'<i class="fas fa-warehouse"></i>','header_url'=>"manage-stock"]);
            Menu::create(["id"=>9,"name"=>"จัดการร้านค้าทั้งหมด",'is_active'=>'1','html_icon'=>'<i class="fas fa-city"></i>','header_url'=>"manage-company"]);
            Menu::create(["id"=>10,"name"=>"สรุปยอดขาย",'is_active'=>'1','html_icon'=>'<i class="fas fa-chart-line"></i>','header_url'=>"sales-summary"]);
            // test git 2
            DB::commit();
        }catch (Exception $e){
            echo "there's error";
            DB::rollBack();
            Log::info($e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
