<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_details', function (Blueprint $table) {
            // ID
            $table->id();
            // ID danh mục cha
            $table->integer('filter_id')->default(0);
            // Tên
            $table->string('name');
            // Đường dẫn (Trường hợp lọc kết hợp với link)
            $table->string('slug');
            // Sắp xếp
            $table->integer('order')->default(99999);
            // Trạng thái (-1 Xóa | 0 Không hoạt động | 1 Hoạt động)
            $table->tinyInteger('status')->default(1);
            // Ngày đăng/cập nhật
            $table->timestamps();
            // Đánh index (Quan trọng)
            $table->index(['filter_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_details');
    }
}
