@php
	$google_shopping_brand = '';
    $google_shopping_category = '';
    $google_shopping_in_stock = '';
    $google_shopping_item_condition = '';
	if (isset($type) && isset($type_id)) {
		$google_shoppings = \DB::table('google_shoppings')->where('type', $type)->where('type_id', $type_id)->first();
		$google_shopping_brand = $google_shoppings->brand ?? '';
	    $google_shopping_category = $google_shoppings->category ?? '';
	    $google_shopping_in_stock = $google_shoppings->in_stock ?? '';
	    $google_shopping_item_condition = $google_shoppings->item_condition ?? '';
	}
@endphp
<div class="col-lg-12">
	<div class="card">
		<div class="card-header" data-card-widget="collapse">
			<div class="card-title">@lang('Google Shopping')</div>
			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
			</div>
		</div>
		<div class="card-body">
			{{-- google_shopping_brand --}}
			<div class="form-group row">
				<label for="google_shopping_brand" class="col-sm-3 col-form-label">@lang('Thương hiệu')</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" data-check_length name="google_shopping_brand" id="google_shopping_brand" placeholder="@lang('VD: Apple')" value="{!! $google_shopping_brand ?? '' !!}">
				</div>
			</div>
			{{-- google_shopping_category --}}
			<div class="form-group row">
				<label for="google_shopping_category" class="col-sm-3 col-form-label">@lang('Danh mục google')</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" data-check_length name="google_shopping_category" id="google_shopping_category" placeholder="@lang('VD: Electronics > Communications > Telephony > Mobile Phone Accessories > Mobile Phone Replacement Parts')" value="{!! $google_shopping_category ?? '' !!}">
				</div>
			</div>
			{{-- google_shopping_in_stock --}}
			<div class="form-group row">
				<label for="google_shopping_in_stock" class="col-sm-3 col-form-label">@lang('Tình trạng kho hàng')</label>
				<div class="col-sm-3">
					@php
						$option_in_stock = [
							'' 				=> 'Mặc định',
							'còn hàng' 		=> 'Còn hàng',
							'hết hàng' 		=> 'Hết hàng',
						];
					@endphp
					<select id="google_shopping_in_stock" name="google_shopping_in_stock" class="form-control">
						@foreach ($option_in_stock as $key => $in_stock)
		                	<option value="{!! $key !!}"
								@if ($key == $google_shopping_in_stock) selected @endif
		                	>@lang($in_stock)</option>
						@endforeach
		            </select>
				</div>
			</div>
			{{-- google_shopping_item_condition --}}
			<div class="form-group row">
				<label for="google_shopping_item_condition" class="col-sm-3 col-form-label">@lang('Tình trạng sản phẩm')</label>
				<div class="col-sm-3">
					@php
						$option_item_condition = [
							'' 				=> 'Mặc định',
							'mới' 			=> 'Mới',
							'cũ' 			=> 'Cũ',
						];
					@endphp
					<select id="google_shopping_item_condition" name="google_shopping_item_condition" class="form-control">
						@foreach ($option_item_condition as $key => $item_condition)
		                	<option value="{!! $key !!}"
								@if ($key == $google_shopping_item_condition) selected @endif
		                	>@lang($item_condition)</option>
						@endforeach
		            </select>
				</div>
			</div>
		</div>
	</div>
</div>