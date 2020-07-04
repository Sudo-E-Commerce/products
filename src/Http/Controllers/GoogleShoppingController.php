<?php

namespace Sudo\Product\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use DB;
use Form;

class GoogleShoppingController extends AdminController
{

    public function googleShopping(Request $requests) {
        $title = "Cấu hình Google Shopping";
        $note = "Cấu hình tại đây sẽ áp dụng cho toàn bộ sản phẩm.";
        // Thêm hoặc cập nhật dữ liệu
        if (isset($requests->redirect)) {
            $check_exits = DB::table('google_shoppings')->where('type', 'settings')->first();
            if ($check_exits == null) {
                DB::table('google_shoppings')->insert([
                    'type' => 'settings',
                    'type_id' => 0,
                    'brand' => $requests->brand,
                    'category' => $requests->category,
                    'in_stock' => $requests->in_stock,
                    'item_condition' => $requests->item_condition,
                ]);
            } else {
                DB::table('google_shoppings')->where('type', 'settings')->update([
                    'brand' => $requests->brand,
                    'category' => $requests->category,
                    'in_stock' => $requests->in_stock,
                    'item_condition' => $requests->item_condition,
                ]);
            }
        }
        // Lấy dữ liệu ra
        $data = DB::table('google_shoppings')->where('type', 'settings')->first();
        // Khởi tạo form
        $form = new Form;

        $option_in_stock = [
            ''              => 'Mặc định',
            'còn hàng'      => 'Còn hàng',
            'hết hàng'      => 'Hết hàng',
        ];
        $option_item_condition = [
            ''              => 'Mặc định',
            'mới'           => 'Mới',
            'cũ'            => 'Cũ',
        ];

        $form->text('brand', $data->brand ?? '', 0, 'Thương hiệu', 'VD: Apple');
        $form->text('category', $data->category ?? '', 0, 'Danh mục google', 'VD: Electronics > Communications > Telephony > Mobile Phone Accessories > Mobile Phone Replacement Parts');
        $form->select('in_stock', $data->in_stock ?? '', 0, 'Tình trạng kho hàng', $option_in_stock, 0);
        $form->select('item_condition', $data->item_condition ?? '', 0, 'Tình trạng sản phẩm', $option_item_condition, 0);
        $form->custom('Product::google_shoppings.cronjob');
        $form->action('editconfig');
        // Hiển thị form tại view
        return $form->render('custom', compact(
            'title', 'note'
        ), 'Product::google_shoppings.settings');
    }

    public function datafeeds() {
        $status = 0;
        $message = '';
        try {
            if (config('SudoProduct.product_models')) {
                // Khởi tạo tiêu đề file
                $data = "id\ttiêu đề\tmô tả\tliên kết\ttình trạng\tgiá\tcòn hàng\tliên kết hình ảnh\tnhãn hiệu\tdanh mục sản phẩm của Google";
                // Bắt đầu lấy ra các phần tử từ sản phẩm
                // Dữ liệu mặc định lấy từ cấu hình google shopping
                $setting_google_shopping = DB::table('google_shoppings')->where('type','settings')->where('type_id', 0)->first();
                // Dữ liệu sản phẩm ( Giá > 0 | Không rỗng ảnh | Không rỗng nội dung )
                $products = config('SudoProduct.product_models')::where('status',1)->where('price','>',0)->whereNotNull('image')->whereNotNull('detail')->get();
                foreach ($products as $product) {
                    $google_shopping_cate = DB::table('google_shoppings')->where('type','product_categories')->where('type_id',$product->category_id??'')->first();
                    $google_shopping_product = DB::table('google_shoppings')->where('type','products')->where('type_id',$product->id)->first();
                    // set giá trị google_shopping mặc định
                    $google_shopping_brand = $setting_google_shopping->brand;
                    $google_shopping_category = $setting_google_shopping->category;
                    $google_shopping_in_stock = $setting_google_shopping->in_stock;
                    $google_shopping_item_condition = $setting_google_shopping->item_condition;
                    // set giá trị google_shopping theo danh mục
                    if(!empty($google_shopping_cate)){
                        if($google_shopping_cate->brand != null){
                            $google_shopping_brand = $google_shopping_cate->brand;
                        }
                        if($google_shopping_cate->category != null){
                            $google_shopping_category = $google_shopping_cate->category;
                        }   
                        if($google_shopping_cate->in_stock != null){
                            $google_shopping_in_stock = $google_shopping_cate->in_stock;
                        }
                        if($google_shopping_cate->item_condition != null){
                            $google_shopping_item_condition = $google_shopping_cate->item_condition;
                        }
                    }
                    if(!empty($google_shopping_product)){
                        if($google_shopping_product->brand != null){
                            $google_shopping_brand = $google_shopping_product->brand;
                        }
                        if($google_shopping_product->category != null){
                            $google_shopping_category = $google_shopping_product->category;
                        }
                        if($google_shopping_product->in_stock != null){
                            $google_shopping_in_stock = $google_shopping_product->in_stock;
                        }
                        if($google_shopping_product->item_condition != null){
                            $google_shopping_item_condition = $google_shopping_product->item_condition;
                        }
                    }
                    if(!empty($google_shopping_brand) && !empty($google_shopping_category) && !empty($google_shopping_in_stock) && !empty($google_shopping_item_condition)){
                        $data .= "\n"."p".$product->id."\t".$product->name."\t".$product->getDesc()."\t".$product->getUrl()."\t".$google_shopping_item_condition."\t".$product->price." VND"."\t".$google_shopping_in_stock."\t".$product->getImage()."\t".$google_shopping_brand."\t".$google_shopping_category;
                    }

                }
                // Tạo file product.txt với dữ liệu đã có
                \Storage::disk('local')->put('products.txt', "\xEF\xBB\xBF" . $data);
                // Trả về thành công
                $status = 1;
                $message = 'Chạy Datafeeds thành công';
            } else {
                $status = 2;
                $message = 'Chạy Datafeeds thất bại';
            }
        } catch (\Exception $e) {
            \Log::error($e);
            $status = 2;
            $message = 'Chạy Datafeeds thất bại';
        }
        return [
            'status' => $status,
            'message' => __($message),
        ];
    }

}