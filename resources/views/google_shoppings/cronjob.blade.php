<div class="form-group row">
	<label for="{{ $name??'' }}" class="col-lg-3 col-md-2 col-form-label text-right">@lang('Khởi chạy Datafeed')</label>
	<div class="col-lg-8 col-md-12">
		<div class="btn-group mb-2">
			<button type="button" class="btn btn-primary" data-run_datafeeds>@lang('Chạy Datafeed')</button>
			<button type="button" class="btn btn-success" onclick="window.open('/products.txt')">@lang('Trang kết quả')</button>
		</div>
		<span class="helper">- @lang('Cấu hình ở trang này là cấu hình cho <strong>toàn bộ sản phẩm</strong>')</span>
		<span class="helper">- @lang('Cấu hình ở trang này sẽ bị ghi đè nếu <strong>Danh mục</strong> có cấu hình')</span>
		<span class="helper">- @lang('Cấu hình ở trang <strong>Danh mục</strong> sẽ bị ghi đè nếu <strong>Sản phẩm</strong> có cấu hình')</span>
		<span class="helper">=> @lang('Mức độ ưu tiên (Cao > Thấp): <strong>Sản phẩm</strong> > <strong>Danh mục sản phẩm</strong> > <strong>Cấu hình Google Shopping</strong>')</span>
	</div>
	<script>
        $(document).ready(function() {
        	$('body').on('click', '*[data-run_datafeeds]', function() {
        		loadAjaxPost('{{ route('admin.settings.datafeeds') }}', {}, {
        			beforeSend: function(){},
			        success:function(result){
			        	if (result.status == 1) {
			        		alertText(result.message, 'success');
			        	} else {
			        		alertText(result.message, 'error');
			        	}
			        },
			        error: function (error) {}
        		})
        	})
        });
    </script>
</div>