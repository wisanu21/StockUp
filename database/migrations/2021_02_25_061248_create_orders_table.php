<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อ');
            $table->float('price', 8, 2);
            $table->float('get_money', 8, 2);
            $table->float('change_money', 8, 2);
            $table->unsignedBigInteger('company_id')->nullable()->comment('บริษัท');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('adder_id')->nullable()->comment('เจ้าของอุปกร');
            $table->foreign('adder_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('promotion_id')->nullable()->comment('โปรโมชั่นที่ใช้ใน order นี้');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
