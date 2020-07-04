<?php
App::booted(function() {
	$namespace = 'Sudo\Product\Http\Controllers';
	
	Route::namespace($namespace)->name('admin.')->prefix(config('app.admin_dir'))->middleware(['web', 'auth-admin'])->group(function() {
		// products
		Route::resource('products', 'ProductController');
		// product_categories
		Route::resource('product_categories', 'ProductCategoryController');
		// brands
		Route::resource('brands', 'BrandController');
		// Thuộc tính
		if (config('SudoProduct.attributes') == true) {
			Route::resource('attributes', 'AttributeController');
			Route::post('/ajax/get_product_attributes', 'ProductController@getAttribute')->name('products.attributes');
		}
		// Bộ lọc
		if (config('SudoProduct.filters') == true) {
			Route::resource('filters', 'FilterController');
			Route::post('/ajax/get_product_filters', 'ProductController@getFilter')->name('products.filters');
		}
		// Cấu hình Google Shopping
		if (config('SudoProduct.google_shoppings') == true) {
			Route::match(['GET', 'POST'], 'settings/google_shoppings', 'GoogleShoppingController@googleShopping')->name('settings.google_shoppings');
			Route::post('settings/google_shoppings/datafeeds', 'GoogleShoppingController@datafeeds')->name('settings.datafeeds');
		}
	});
});