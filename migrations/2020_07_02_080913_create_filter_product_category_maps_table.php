<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterProductCategoryMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_product_category_maps', function (Blueprint $table) {
            // ID sản phẩm
            $table->integer('category_id');
            // ID chi tiết lọc
            $table->integer('filter_id');
            // Đánh index (Quan trọng)
            $table->index(['category_id']);
            $table->index(['filter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_product_category_maps');
    }
}
