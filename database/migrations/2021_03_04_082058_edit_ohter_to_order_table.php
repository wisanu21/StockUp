<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditOhterToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->float('sum_price', 8, 2)->comment('ราคาสินค้ารวม');
            $table->float('promotion_price', 8, 2)->nullable()->comment('ราคาโปรโมชั่นที่ใช้ลด');
            $table->float('final_price', 8, 2)->comment('เงินที่ต้องจ่าย');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
