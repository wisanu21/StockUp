<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Level;

class LevelSeeder extends Seeder
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
            DB::table('levels')->delete();
            // DB::table('levels')->truncate();
            // DB::raw("INSERT INTO 'level' ('id', 'name') VALUES (0, 'ไม่ได้');");
            // DB::table("level")->create(['id'=>0, 'name'=>'ไม่ได้']);
            Level::create(['id'=>1,'name'=>'ผู้ดูแลระบ','html_icon'=>'<i class="fas fa-user-astronaut"></i>']);
            Level::create(['id'=>2,'name'=>'เจ้าของร้าน','html_icon'=>'<i class="fas fa-user-tie"></i>']);
            Level::create(['id'=>3,'name'=>'หัวหน้าลูกจ้าง','html_icon'=>'<i class="fas fa-user-secret"></i>']);
            Level::create(['id'=>4,'name'=>'ลูกจ้าง','html_icon'=>'<i class="fas fa-user-ninja"></i>']);
            // test git 2
            DB::commit();
        }catch (Exception $e){
            echo "there's error";
            DB::rollBack();
            Log::info($e->getMessage() . "\n" . $e->getTraceAsString());
        }

    }
}
