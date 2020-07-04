<?php

namespace Sudo\Product\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use ListData;
use Form;

class AttributeController extends AdminController
{
	function __construct() {
        $this->models = new \Sudo\Product\Models\Attribute;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Thuộc tính';
        $this->has_seo = false;
        $this->has_locale = true;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests) {
    	$listdata = new ListData($requests, $this->models, 'Product::attributes.table', $this->has_locale, 30, [ 'order' => 'asc', 'id' => 'desc' ]);
        // Build Form tìm kiếm
        $listdata->search('name', 'Tên', 'string');
        $listdata->search('created_at', 'Ngày tạo', 'range');
        $listdata->search('status', 'Trạng thái', 'array', config('app.status'));
        // Build các button hành động
        $listdata->btnAction('status', 1, __('Table::table.active'), 'success', 'fas fa-edit');
        $listdata->btnAction('status', 0, __('Table::table.no_active'), 'info', 'fas fa-window-close');
        $listdata->btnAction('delete', -1, __('Table::table.trash'), 'danger', 'fas fa-trash');
        // Build bảng
        $listdata->add('name', 'Tên', 1);
        $listdata->add('order', 'Sắp xếp', 1, 'order');
        $listdata->add('', 'Thời gian', 0, 'time');
        $listdata->add('status', 'Trạng thái', 1, 'status');
        $listdata->add('', 'Language', 0, 'lang');
        $listdata->add('', 'Sửa', 0, 'edit');
        $listdata->add('', 'Xóa', 0, 'delete');
        // Trả về views
        return $listdata->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $form = new Form;
        $form->text('name', '', 1, 'Tiêu đề');
        $form->text('display_name', '', 0, 'Tên hiển thị');
        $form->radio('status', 1, 'Trạng thái', config('app.status'));
        $form->custom('Form::custom.form_custom', [
            'has_full' => false,
            'name' => 'attribute_details',
            'value' => $data['attribute_details'] ?? [],
            'label' => 'Thêm thuộc tính',
            'generate' => [
                [ 'type' => 'textarea', 'name' => 'name', 'placeholder' => 'Tên thuộc tính', ],
            ],
        ]);
        $form->action('add');
        // Hiển thị form tại view
        return $form->render('create');
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
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        // Thêm vào DB
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $compact = compact('name','display_name','status','created_at','updated_at');
        $id = $this->models->createRecord($requests, $compact, $this->has_seo, $this->has_locale);
        // Thêm chi tiết thuộc tính
        if (isset($attribute_details['name']) && !empty($attribute_details['name'])) {
            $this->storeAttributeDetail($attribute_details['name'] ?? [], $id);
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

        $form = new Form;
        $form->card('col-lg-3', 'Thông tin thuộc tính');
            $form->text('attribute_name', $data_edit->name, 1, 'Tiêu đề');
            $form->text('display_name', $data_edit->display_name, 0, 'Tên hiển thị');
            $form->radio('attribute_status', $data_edit->status, 'Trạng thái', config('app.status'));
            $form->custom('Form::custom.form_custom', [
                'has_full' => true,
                'name' => 'attribute_details',
                'value' => $data['attribute_details'] ?? [],
                'label' => 'Thêm thuộc tính',
                'generate' => [
                    [ 'type' => 'textarea', 'name' => 'name', 'placeholder' => 'Tên thuộc tính', ],
                ],
            ]);
        $form->endCard();
        
        $form->custom('Product::attributes.attribute_details', [
            'attribute_id' => $id,
        ]);

        $form->action('edit');
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
        validateForm($requests, 'attribute_name', 'Tiêu đề không được để trống.');
        // Lấy bản ghi
        $data_edit = $this->models->where('id', $id)->first();
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        $name = $attribute_name ?? null;
        $status = $attribute_status ?? $status;
        // Các giá trị thay đổi
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $compact = compact('name','display_name','status','updated_at');
        // Cập nhật tại database
        $this->models->updateRecord($requests, $id, $compact, $this->has_seo);
        // Thêm chi tiết thuộc tính
        if (isset($attribute_details['name']) && !empty($attribute_details['name'])) {
            $this->storeAttributeDetail($attribute_details['name'] ?? [], $data_edit->id);
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
     * Thêm chi tiết thuộc tính theo mảng truyền vào (Auto thêm)
     */
    public function storeAttributeDetail($data = [], $attribute_id) {
        $store_data = [];
        foreach ($data as $value) {
            $store_data[] = [
                'attribute_id' => $attribute_id,
                'name' => $value,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        \Sudo\Product\Models\AttributeDetail::insert($store_data);
    }
}