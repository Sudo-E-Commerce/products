<?php

namespace Sudo\Product\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use ListData;
use ListCategory;
use Form;

class ProductController extends AdminController
{
	function __construct() {
        $this->models = new \Sudo\Product\Models\Product;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Sản phẩm';
        $this->has_seo = true;
        $this->has_locale = true;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests) {
    	$listdata = new ListData($requests, $this->models, 'Product::products.table', $this->has_locale);
        // Danh mục
        $product_categories = new ListCategory('product_categories');
        $product_categories = $product_categories->data();
        // Build Form tìm kiếm
        $listdata->search('name', 'Tên', 'string');
        $listdata->search('category_id', 'Danh mục', 'array', $product_categories);
        $listdata->search('created_at', 'Ngày tạo', 'range');
        $listdata->search('status', 'Trạng thái', 'array', config('app.status'));
        // Build các button hành động
        $listdata->btnAction('status', 1, __('Table::table.active'), 'success', 'fas fa-edit');
        $listdata->btnAction('status', 0, __('Table::table.no_active'), 'info', 'fas fa-window-close');
        $listdata->btnAction('delete', -1, __('Table::table.trash'), 'danger', 'fas fa-trash');
        // Build bảng
        $listdata->add('image', 'Ảnh', 0);
        $listdata->add('name', 'Tên', 1);
        $listdata->add('category_id', 'Danh mục', 0);
        $listdata->add('', 'Thời gian', 0, 'time');
        $listdata->add('status', 'Trạng thái', 1, 'status');
        $listdata->add('', 'Language', 0, 'lang');
        $listdata->add('', 'Sửa', 0, 'edit');
        $listdata->add('', 'Xóa', 0, 'delete');
        // Trả về views
        return $listdata->render(compact('product_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // Danh mục
        $categories = new ListCategory('product_categories', $this->has_locale, Request()->lang_locale ?? \App::getLocale());
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-9');
            $form->lang($this->table_name);
            $form->text('name', '', 1, 'Tiêu đề');
            $form->slug('slug', '', 1, 'Đường dẫn');
            $form->custom('Product::admin.form.price', [
                'name' => 'price',
                'value' => '',
                'required' => 1,
                'label' => 'Giá bán',
                'placeholder' => 'Giá bán',
            ]);
            $form->custom('Product::admin.form.price', [
                'name' => 'price_old',
                'value' => '',
                'required' => 0,
                'label' => 'Giá thị trường',
                'placeholder' => 'Giá thị trường',
            ]);
            $form->textarea('info', '', 0, 'Thông tin sản phẩm', 'Xuống dòng với mỗi thông tin');
            $form->textarea('promotion', '', 0, 'Thông tin khuyến mãi', 'Xuống dòng với mỗi thông tin');
            $form->editor('detail', '', 0, 'Nội dung');
        $form->endCard();
        $form->card('col-lg-3', '');
            $form->action('add');
            $form->radio('status', 1, 'Trạng thái', config('app.status'));
            $form->select('category_id', '', 1, 'Danh mục', $categories->data_select(), 0);
            $form->text('sku', '', 0, 'Mã định danh (SKU)');
            $form->suggest('brand_id', '', 0, 'Thương hiệu', null, 'brands');
            $form->multiSuggest('related_products', '', 0, 'Sản phẩm liên quan', null, 'products', 'id', 'name', 'true');
            $form->image('image', '', 0, 'Ảnh đại diện');
            $form->multiImage('slide', '', 0, 'Ảnh Slide');
        $form->endCard();
        // Thuộc tính
        if (config('SudoProduct.attributes') == true) {
            $form->custom('Product::products.form.product_attributes', [
                'product_id' => 0,
                'category_id' => 0
            ]);
        }
        // Bộ lọc
        if (config('SudoProduct.filters') == true) {
            $form->custom('Product::products.form.product_filters', [
                'product_id' => 0,
                'category_id' => 0,
            ]);
        }
        // Google Shopping
        if (config('SudoProduct.google_shoppings') == true) {
            $form->custom('Product::google_shoppings.form');
        }

        // Hiển thị form tại view
        $form->hasFullForm();
        return $form->render('create_multi_col');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $requests) {
        // Xử lý validate
        validateForm($requests, 'name', 'Tiêu đề không được để trống.');
        validateForm($requests, 'category_id', 'Danh mục không được để trống.');
        validateForm($requests, 'price', 'Giá bán không được để trống.');
        validateForm($requests, 'slug', 'Đường dẫn không được để trống.');
        validateForm($requests, 'slug', 'Đường dẫn đã bị trùng.', 'unique', 'unique:products');
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        if (isset($brand_id) && !empty($brand_id)) { $brand_id = $brand_id; } else { $brand_id = 0; }
        if (isset($slide) && !empty($slide)) { $slide = implode(',', $slide); } else { $slide = null; }
        if (isset($related_products) && !empty($related_products)) { $related_products = implode(',', $related_products); } else { $related_products = null; }
        // Thêm vào DB
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $compact = compact('category_id','brand_id','sku','name','slug','image','slide','price','price_old','info','detail','promotion','related_products','status','created_at','updated_at');
        $id = $this->models->createRecord($requests, $compact, $this->has_seo, $this->has_locale);
        // Lưu thuộc tính của sản phẩm
        if (isset($attributes) && !empty($attributes)) {
            $this->setAttribute($attributes, $id);
        }
        // Lưu bộ lọc của sản phẩm
        if (isset($filters) && !empty($filters)) {
            $this->setFilter($filters, $id);
        }
        // Lưu cấu hình google shopping
        if (config('SudoProduct.google_shoppings') == true) {
            googleShopping($requests, 'products', $id);
        }
        // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
            'type' => 'success',
            'message' => __('Core::admin.create_success')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
    	return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        // Dẽ liệu bản ghi hiện tại
        $data_edit = $this->models->where('id', $id)->first();
        // Ngôn ngữ bản ghi hiện tại
        $language_meta = \DB::table('language_metas')->where('lang_table', $this->table_name)->where('lang_table_id', $data_edit->id)->first();
        // danh mục ứng với ngôn ngữ
        $categories = new ListCategory('product_categories', $this->has_locale, $language_meta->lang_locale ?? null);
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-9');
            $form->lang($this->table_name);
            $form->text('name', $data_edit->name, 1, 'Tiêu đề');
            $form->slug('slug', $data_edit->slug, 1, 'Đường dẫn', '', 'false');
            $form->custom('Product::admin.form.price', [
                'name' => 'price',
                'value' => $data_edit->price,
                'required' => 1,
                'label' => 'Giá bán',
                'placeholder' => 'Giá bán',
            ]);
            $form->custom('Product::admin.form.price', [
                'name' => 'price_old',
                'value' => $data_edit->price_old,
                'required' => 0,
                'label' => 'Giá thị trường',
                'placeholder' => 'Giá thị trường',
            ]);
            $form->textarea('info', $data_edit->info, 0, 'Thông tin sản phẩm', 'Xuống dòng với mỗi thông tin');
            $form->textarea('promotion', $data_edit->promotion, 0, 'Thông tin khuyến mãi', 'Xuống dòng với mỗi thông tin');
            $form->editor('detail', $data_edit->detail, 0, 'Nội dung');
        $form->endCard();
        $form->card('col-lg-3', '');
            // lấy link xem
            $link = (config('SudoProduct.product_models')) ? config('SudoProduct.product_models')::where('id', $id)->first()->getUrl() : '';
            $form->action('edit', $link);
            $form->radio('status', $data_edit->status, 'Trạng thái', config('app.status'));
            $form->select('category_id', $data_edit->category_id, 1, 'Danh mục', $categories->data_select(), 0);
            $form->text('sku', $data_edit->sku, 0, 'Mã định danh (SKU)');
            $form->suggest('brand_id', $data_edit->brand_id, 0, 'Thương hiệu', null, 'brands');
            $form->multiSuggest('related_products', $data_edit->related_products, 0, 'Sản phẩm liên quan', null, 'products', 'id', 'name', 'true');
            $form->image('image', $data_edit->image, 0, 'Ảnh đại diện');
            $form->multiImage('slide', array_filter(explode(',', $data_edit->slide)), 0, 'Ảnh Slide');
        $form->endCard();
        // Thuộc tính
        if (config('SudoProduct.attributes') == true) {
            $form->custom('Product::products.form.product_attributes', [
                'product_id' => $data_edit->id,
                'category_id' => $data_edit->category_id,
            ]);
        }
        // Bộ lọc
        if (config('SudoProduct.filters') == true) {
            $form->custom('Product::products.form.product_filters', [
                'product_id' => $data_edit->id,
                'category_id' => $data_edit->category_id,
            ]);
        }
        // Google Shopping
        if (config('SudoProduct.google_shoppings') == true) {
            $form->custom('Product::google_shoppings.form', [
                'type' => 'products',
                'type_id' => $data_edit->id
            ]);
        }

        // Hiển thị form tại view
        $form->hasFullForm();
        return $form->render('edit_multi_col', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requests, $id) {
        // Xử lý validate
        validateForm($requests, 'name', 'Tiêu đề không được để trống.');
        validateForm($requests, 'category_id', 'Danh mục không được để trống.');
        validateForm($requests, 'price', 'Giá bán không được để trống.');
        validateForm($requests, 'slug', 'Đường dẫn không được để trống.');
        validateForm($requests, 'slug', 'Đường dẫn đã bị trùng.', 'unique', 'unique:products,slug,'.$id);
        // Lấy bản ghi
        $data_edit = $this->models->where('id', $id)->first();
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        if (isset($brand_id) && !empty($brand_id)) { $brand_id = $brand_id; } else { $brand_id = 0; }
        if (isset($slide) && !empty($slide)) { $slide = implode(',', $slide); } else { $slide = null; }
        if (isset($related_products) && !empty($related_products)) { $related_products = implode(',', $related_products); } else { $related_products = null; }
        // Các giá trị thay đổi
        $updated_at = date('Y-m-d H:i:s');
        $compact = compact('category_id','brand_id','sku','name','slug','image','slide','price','price_old','info','detail','promotion','related_products','status','updated_at');
        // Cập nhật tại database
        $this->models->updateRecord($requests, $id, $compact, $this->has_seo);
        // Lưu thuộc tính của sản phẩm
        if (isset($attributes) && !empty($attributes)) {
            $this->setAttribute($attributes, $id);
        }
        // Lưu bộ lọc của sản phẩm
        if (isset($filters) && !empty($filters)) {
            $this->setFilter($filters, $id);
        }
        // Lưu cấu hình google shopping
        if (config('SudoProduct.google_shoppings') == true) {
            googleShopping($requests, 'products', $id);
        }
        // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
            'type' => 'success',
            'message' => __('Core::admin.update_success')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
    	//
    }

    /**
     * Load thông số thuộc tính
     */
    public function getAttribute(Request $requests) {
        $category_id = $requests->category_id ?? 0;
        $product_id = $requests->product_id ?? 0;
        $render_html = view('Product::products.form.product_attribute_item', compact('category_id', 'product_id'))->render();
        return [
            'status' => 1,
            'html' => $render_html
        ];
    }

    /**
     * Lưu thông tin thuộc tính của sản phẩm
     */
    public function setAttribute($data, $id) {
        $store_data = [];
        \Sudo\Product\Models\ProductAttribute::where('product_id', $id)->delete();
        foreach ($data as $key => $value) {
            $store_data[] = [
                'product_id' => $id,
                'attribute_detail_id' => $key,
                'value' => $value,
            ];
        }
        \Sudo\Product\Models\ProductAttribute::insert($store_data);
    }

    /**
     * Load thông số bộ lọc
     */
    public function getFilter(Request $requests) {
        $category_id = $requests->category_id ?? 0;
        $product_id = $requests->product_id ?? 0;
        $render_html = view('Product::products.form.product_filter_item', compact('category_id', 'product_id'))->render();
        return [
            'status' => 1,
            'html' => $render_html
        ];
    }

    /**
     * Lưu thông tin bộ lọc của sản phẩm
     */
    public function setFilter($data, $id) {
        $store_data = [];
        \Sudo\Product\Models\ProductFilter::where('product_id', $id)->delete();
        foreach ($data as $value) {
            $store_data[] = [
                'product_id' => $id,
                'filter_detail_id' => $value
            ];
        }
        \Sudo\Product\Models\ProductFilter::insert($store_data);
    }
}