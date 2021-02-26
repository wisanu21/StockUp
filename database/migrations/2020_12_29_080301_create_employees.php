<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            // $table->id();
            $table->bigIncrements('id');
            $table->string('first_name')->comment('ชื่อจริง');
            $table->string('last_name')->comment('นามสกุล');
            $table->string('easy_name')->comment('ชื่อเล่น');
            $table->string('mobile',10)->unique()->comment('เบอร์โทร');
            $table->string('password')->comment('รหัสผ่าน');
            $table->unsignedBigInteger('company_id')->nullable()->comment('บริษัท');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->boolean("is_active")->comment('สถานะใช้งาน');
            // $table->unsignedBigInteger('level_id')->nullable()->comment('บริษัท');
            // $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
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
        Schema::dropIfExists('employees');
    }
}
