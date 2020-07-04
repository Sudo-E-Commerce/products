<?php 
return [
	// Có sử dụng module Google Shopping không (true có | false không)
	'google_shoppings' => env('PRODUCT_GOOGLE_SHOPPING', true),
	
	// Models của giao diện bên ngoài và phải có hàn getUrl()
	'product_models' => '\Sudo\Product\Models\Product',

	// Có sử dụng module thuộc tính sản phẩm không
	'attributes' => env('PRODUCT_ATTRIBUTE', true),

	// Có sử dụng module thuộc tính sản phẩm không
	'filters' => env('PRODUCT_FILTER', true),
];