<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อ');
            $table->string('price_or_percentage')->comment('ราคา หรือ เปอร์เซ็น');
            $table->float('Resource', 8, 2)->comment('ค่า ของ ราคา หรือ เปอร์เซ็น');
            $table->unsignedBigInteger('company_id')->nullable()->comment('บริษัท');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('adder_id')->nullable()->comment('เจ้าของอุปกร');
            $table->foreign('adder_id')->references('id')->on('employees')->onDelete('cascade');
            $table->boolean("is_active_promotion")->comment('ใช้งานpromotionหรือไม่');
            $table->string('code')->comment('โค้ด');
            $table->boolean("is_active_code")->comment('ใช้งานcodeหรือไม่');
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
        Schema::dropIfExists('promotions');
    }
}
