<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleShoppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_shoppings', function (Blueprint $table) {
            // Tên bảng
            $table->string('type');
            // ID của bảng
            $table->integer('type_id');
            // Thương hiệu
            $table->string('brand')->nullable();
            // Danh mục
            $table->string('category')->nullable();
            // Tình trạng kho (Còn hàng | Hết hàng)
            $table->string('in_stock')->nullable();
            // Tình trạng sản phẩm (Cũ | Mới)
            $table->string('item_condition')->nullable();
            // Đánh index (Quan trọng)
            $table->index(['type', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_shoppings');
    }
}
