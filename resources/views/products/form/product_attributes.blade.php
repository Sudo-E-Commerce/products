<div class="col-lg-12">
	<div class="card" id="attributes">
		<div class="card-header" data-card-widget="collapse">
			<div class="card-title">@lang('Thuộc tính')</div>
			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
			</div>
		</div>
		<div class="card-body pt-2 pr-2 pb-0 pl-2">
			{{-- @include('Product::products.form.product_attribute_item') --}}
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
			url = '{{ route('admin.products.attributes') }}';
			loadAjaxPost(url, data, {
		        beforeSend: function(){
		        	$('#attributes').addClass('loading');
		        },
		        success:function(result){
		        	$('#attributes').removeClass('loading');
		        	if (result.status == 1) {
		        		$('#attributes').find('.card-body').html(result.html);
		        	} else {
		        		alertText(result.message, 'error');
		        	}
		        },
		        error: function (error) {
		        	$('#attributes').removeClass('loading');
		        }
		    }, 'custom');
		});
		// Tự động load ajax để hiển thị chi tiết
		$('#category_id').change();
	});
</script>