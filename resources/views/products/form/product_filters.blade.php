<div class="col-lg-12">
	<div class="card" id="filters">
		<div class="card-header" style="padding: .75rem 1.25rem" data-card-widget="collapse">
			<div class="card-title">@lang('Bộ lọc')</div>
			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
			</div>
		</div>
		<div class="card-body pt-2 pr-2 pb-0 pl-2">
			{{-- @include('Product::products.form.product_filter_item') --}}
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('body').on('change', '#category_id', function() {
			category_id = $(this).val();
			product_id = '{{$product_id ?? ''}}';
			// Dữ liệu
			data = {
				category_id: category_id,
				product_id: product_id
			};
			url = '{{ route('admin.products.filters') }}';
			loadAjaxPost(url, data, {
		        beforeSend: function(){
		        	$('#filters').addClass('loading');
		        },
		        success:function(result){
		        	$('#filters').removeClass('loading');
		        	if (result.status == 1) {
		        		$('#filters').find('.card-body').html(result.html);
		        	} else {
		        		alertText(result.message, 'error');
		        	}
		        },
		        error: function (error) {
		        	$('#filters').removeClass('loading');
		        }
		    }, 'custom');
		});
		// Tự động load ajax để hiển thị chi tiết
		$('#category_id').change();
	});
</script>