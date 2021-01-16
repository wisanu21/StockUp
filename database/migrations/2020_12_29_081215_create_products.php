<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('ชื่อ');
            $table->double('price')->comment('ราคา');
            $table->string('image_path')->comment('ที่อยู่รูป');
            $table->integer('num_product')->comment('จำนวนสินค้า');
            $table->text('detail')->comment('ข้อมูล');
            $table->unsignedBigInteger('company_id')->nullable()->comment('บริษัท');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('adder_id')->nullable()->comment('พนักงานที่เพิ่มสินค้า');
            $table->foreign('adder_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
