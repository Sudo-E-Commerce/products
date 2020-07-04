<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeProductCategoryMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_product_category_maps', function (Blueprint $table) {
            // ID sản phẩm
            $table->integer('category_id');
            // ID chi tiết lọc
            $table->integer('attribute_id');
            // Đánh index (Quan trọng)
            $table->index(['category_id']);
            $table->index(['attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_product_category_maps');
    }
}
