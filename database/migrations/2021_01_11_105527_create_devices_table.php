<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable()->comment('เจ้าของอุปกร');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('os')->comment('ยี่ห้อ');
            $table->string('identifier')->comment('รหัส');
            $table->string('token')->comment('รหัส');
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
        Schema::dropIfExists('devices');
    }
}
