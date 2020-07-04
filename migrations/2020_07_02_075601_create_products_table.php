<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            // ID Sản phẩm
            $table->id();
            // ID Danh mục
            $table->integer('category_id')->default(0);
            // ID Thương hiệu
            $table->integer('brand_id')->default(0);
            // Mã định danh sản phẩm duy nhất
            $table->string('sku')->nullable();
            // Tên sản phẩm
            $table->string('name');
            // Đường dẫn
            $table->string('slug')->unique();
            // Ảnh sản phẩm
            $table->text('image')->nullable();
            // Ảnh slide
            $table->text('slide')->nullable();
            // Giá bán
            $table->integer('price')->nullable();
            // Giá thị trường
            $table->integer('price_old')->nullable();
            // Thông tin thêm về SP (VD: phụ kiện đi kèm, các chính sách hỗ trợ, ...)
            $table->text('info')->nullable();
            // Nội dung giới thiệu sản phẩm (Editor hoặc cấu hình json)
            $table->longtext('detail')->nullable();
            // Khuyến mãi hiện hành
            $table->text('promotion')->nullable();
            // Sản phẩm liên quan
            $table->string('related_products')->nullable();
            // Trạng thái (-1 Xóa | 0 Không hoạt động | 1 Hoạt động)
            $table->tinyInteger('status')->default(1);
            // Ngày đăng/cập nhật
            $table->timestamps();

            // Đánh index (Quan trọng)
            $table->index(['category_id', 'status']);
            $table->index(['brand_id', 'status']);
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
