<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            // $table->id();
            $table->bigIncrements('id');
            $table->string('name')->comment('ชื่อ companies');
            $table->boolean('is_active')->comment('เปิดใช้โรงงาน');
            $table->boolean('is_register')->comment('แสดงใน select Register หรือไม่');
            $table->string('path_image')->comment('ลิ้งรูป');
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
        Schema::dropIfExists('companies');
    }
}
