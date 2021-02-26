<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Company;

class CompanySeeder extends Seeder
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
            DB::table('companies')->delete();
            Company::create(['id'=>1,'name'=>'ผู้ดูแลระบบ',"is_active"=>'1',"is_register"=>'0',"path_image"=>"test.png"]);
            Company::create(['id'=>2,'name'=>'ร้านที่1',"is_active"=>'1',"is_register"=>'1',"path_image"=>"test.png"]);
            Company::create(['id'=>3,'name'=>'ร้านที่2',"is_active"=>'1',"is_register"=>'1',"path_image"=>"test.png"]);
            Company::create(['id'=>4,'name'=>'ร้านที่3',"is_active"=>'1',"is_register"=>'1',"path_image"=>"test.png"]);
            // test git 2
            DB::commit();
        }catch (Exception $e){
            echo "there's error";
            DB::rollBack();
            Log::info($e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
