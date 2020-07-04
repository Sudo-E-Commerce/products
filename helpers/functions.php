<?php 

/*
 * Cập nhật Google Shopping
 * @param requests      $requests: Request của form truyên vào
 * @param string        $table: Tên bảng
 * @param number        $id: id thuộc bảng
 */
function googleShopping($requests, $type, $type_id) {
    $seo = DB::table('google_shoppings')->where('type', $type)->where('type_id',$type_id)->first();
    $data = [
        'brand' => $requests->google_shopping_brand,
        'category' => $requests->google_shopping_category,
        'in_stock' => $requests->google_shopping_in_stock,
        'item_condition' => $requests->google_shopping_item_condition,
    ];
    if (!empty($seo)) {
        \DB::table('google_shoppings')->where('type',$type)->where('type_id',$type_id)->update($data);
    } else {
        $data['type'] = $type;
        $data['type_id'] = $type_id;
        \DB::table('google_shoppings')->insert($data);
    }
}

/**
 * Lấy thuộc tính ứng với danh mục, nếu danh mục hiện tại không có thì sẽ lấy theo danh mục cha
 * @param number        $id: ID danh mục sản phẩm cần lấy
 */
function getAttributeCategoryMap($category_id) {
    $attribute_product_category_maps = \Sudo\Product\Models\AttributeProductCategoryMap::where('category_id', $category_id)->get();
    $coutinue = (count($attribute_product_category_maps) == 0) ? true : false;
    while ($coutinue == true) {
        $category = \Sudo\Product\Models\ProductCategory::where('id', $category_id)->first();
        $attribute_product_category_maps = \Sudo\Product\Models\AttributeProductCategoryMap::where('category_id', $category->parent_id)->get();
        if (count($attribute_product_category_maps) > 0 || $category->parent_id == 0) {
            $coutinue = false;
        }
    }
    return $attribute_product_category_maps;
}

/**
 * Lấy bộ lọc ứng với danh mục, nếu danh mục hiện tại không có thì sẽ lấy theo danh mục cha
 * @param number        $id: ID danh mục sản phẩm cần lấy
 */
function getFilterCategoryMap($category_id) {
    $filter_product_category_maps = \Sudo\Product\Models\FilterProductCategoryMap::where('category_id', $category_id)->get();
    $coutinue = (count($filter_product_category_maps) == 0) ? true : false;
    while ($coutinue == true) {
        $category = \Sudo\Product\Models\ProductCategory::where('id', $category_id)->first();
        $filter_product_category_maps = \Sudo\Product\Models\FilterProductCategoryMap::where('category_id', $category->parent_id)->get();
        if (count($filter_product_category_maps) > 0 || $category->parent_id == 0) {
            $coutinue = false;
        }
    }
    return $filter_product_category_maps;
}