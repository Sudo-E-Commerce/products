## Hướng dẫn sử dụng Sudo Post ##

**Giới thiệu:** Đây là package dùng để quản lý sản phẩm của SudoCms.

Mặc định package sẽ tạo ra giao diện quản lý cho toàn bộ sản phẩm và danh mục bài viết được đặt tại `/{admin_dir}/products` và `/{admin_dir}/product_categories`, trong đó admin_dir là đường dẫn admin được đặt tại `config('app.admin_dir')`

Package đã tích hợp sẵn các module khác: Google Shopping, Bộ lọc, Thuộc tính sản phẩm

### Cài đặt để sử dụng ###

- Package cần phải có base `sudo/core` để có thể hoạt động không gây ra lỗi
- Để có thể sử dụng Package cần tải Zip và nhúng vào Laravel như các Package thông thường (Vì sẽ phải sửa thường xuyên nên không dùng packagist để require)
- Chạy `php artisan migrate` để tạo các bảng phục vụ cho package

### Cấu hình tại Menu ###

	[
    	'type' 				=> 'multiple',
    	'name' 				=> 'Sản phẩm',
		'icon' 				=> 'fab fa-product-hunt',
		'childs' => [
			[
				'name' 		=> 'Thêm mới',
				'route' 	=> 'admin.products.create',
				'role' 		=> 'products_create'
			],
			[
				'name' 		=> 'Danh sách',
				'route' 	=> 'admin.products.index',
				'role' 		=> 'products_index',
				'active' 	=> [ 'admin.products.show', 'admin.products.edit' ]
			],
			[
				'name' 		=> 'Danh mục',
				'route' 	=> 'admin.product_categories.index',
				'role' 		=> 'product_categories_index',
				'active' 	=> [ 'admin.product_categories.create', 'admin.product_categories.show', 'admin.product_categories.edit' ]
			],
			[
				'name' 		=> 'Thuộc tính',
				'route' 	=> 'admin.attributes.index',
				'role' 		=> 'attributes_index',
				'active' 	=> [ 'admin.attributes.create', 'admin.attributes.show', 'admin.attributes.edit' ]
			],
			[
				'name' 		=> 'Bộ lọc',
				'route' 	=> 'admin.filters.index',
				'role' 		=> 'filters_index',
				'active' 	=> [ 'admin.filters.create', 'admin.filters.show', 'admin.filters.edit' ]
			],
			[
				'name' 		=> 'Google Shopping',
				'route' 	=> 'admin.settings.google_shoppings',
				'role' 		=> 'settings_google_shoppings',
				'active' 	=> [ 'admin.settings.google_shoppings' ]
			],
		]
    ],
    [
		'type' 		=> 'single',
        'name' 		=> 'Thương hiệu',
        'icon' 		=> 'fab fa-battle-net',
        'route' 	=> 'admin.brands.index',
        'role'		=> 'brands_index'
	],
 
- Vị trí cấu hình được đặt tại `config/SudoMenu.php`
- Để có thể hiển thị tại menu, chúng ta có thể đặt đoạn cấu hình trên tại `config('SudoMenu.menu')`

### Cấu hình tại Module ###
	
	'products' => [
		'name' 			=> 'Sản phẩm',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],
	'product_categories' => [
		'name' 			=> 'Danh mục sản phẩm',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],
	'attributes' => [
		'name' 			=> 'Thuộc tính',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],
	'filters' => [
		'name' 			=> 'Bộ lọc',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],
	'brands' => [
		'name' 			=> 'Thương hiệu',
		'permision' 	=> [
			[ 'type' => 'index', 'name' => 'Truy cập' ],
			[ 'type' => 'create', 'name' => 'Thêm' ],
			[ 'type' => 'edit', 'name' => 'Sửa' ],
			[ 'type' => 'restore', 'name' => 'Lấy lại' ],
			[ 'type' => 'delete', 'name' => 'Xóa' ],
		],
	],
	'settings' => [
		'name' 			=> 'Cấu hình',
		'permision' 	=> [
			...
			[ 'type' => 'google_shoppings', 'name' => 'Cấu hình Google Shopping' ],
			...
		],
	],

- Vị trí cấu hình được đặt tại `config/SudoModule.php`
- Để có thể phân quyền, chúng ta có thể đặt đoạn cấu hình trên tại `config('SudoModule.modules')`

### Publish ###

Mặc định khi chạy lệnh `php artisan sudo/core` đã sinh luôn cho package này, nhưng có một vài trường hợp chỉ muốn tạo lại riêng cho package này thì sẽ chạy các hàm dưới đây:

* Khởi tạo chung theo core
	- Tạo configs `php artisan vendor:publish --tag=sudo/core`
	- Chỉ tạo configs `php artisan vendor:publish --tag=sudo/core/config`
* Khởi tạo riêng theo package
	- Tạo configs `php artisan vendor:publish --tag=sudo/product`
	- Chỉ tạo configs `php artisan vendor:publish --tag=sudo/product/config`

###Cách dùng###

Có thể bật, tắt không sử dụng các modules: Google shopping, Bộ lọc, Thuộc tính sản phẩm tại `config/SudoProduct.php`