<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            // ID sản phẩm
            $table->integer('product_id');
            // ID chi tiết lọc
            $table->integer('attribute_detail_id');
            // Giá trị
            $table->string('value')->nullable();
            // Đánh index (Quan trọng)
            $table->index(['product_id']);
            $table->index(['attribute_detail_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
