@if (!isset($category_id) || $category_id == 0)
	<h5 class="p-2" style="font-weight: normal;">@lang('Vui lòng chọn <strong>Danh mục</strong> để hiển thị')</h5>
@else
	@php
		$attribute_product_category_maps = getAttributeCategoryMap($category_id);
		$attribute_array_id = $attribute_product_category_maps->pluck('attribute_id')->toArray();
		$attributes = \Sudo\Product\Models\Attribute::whereIn('id', $attribute_array_id)->orderBy('order', 'asc')->orderBy('id', 'desc')->get();
		$attribute_details = collect(\Sudo\Product\Models\AttributeDetail::whereIn('attribute_id', $attribute_array_id)->orderBy('order', 'asc')->get());
		$product_attributes = \Sudo\Product\Models\ProductAttribute::where('product_id', $product_id ?? 0)->pluck('value', 'attribute_detail_id')->toArray();
	@endphp
	@if (isset($attributes) && count($attributes) > 0)
		@foreach ($attributes as $attr)
			<div class="card card-pink collapsed-card mb-2">
				<div class="card-header text-sm" data-card-widget="collapse" style="padding: 5px 13px;">
					<div class="card-title">@lang($attr->name)</div>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
					</div>
				</div>
				<div class="card-body">
					@foreach ($attribute_details->where('attribute_id', $attr->id) as $details)
						<div class="form-group row">
						    <label for="html_head" class="col-lg-3 col-md-12 col-form-label text-right">{{ $details->name ?? '' }}</label>
					        <div class="col-lg-8 col-md-12">
					            <input type="text" class="form-control" autocomplete="off" name="attributes[{{$details->id}}]" placeholder="{{ $details->name ?? '' }}" value="{{ $product_attributes[$details->id] ?? '' }}">
					    	</div>
						</div>
					@endforeach
				</div>
			</div>
		@endforeach
	@else
		<h5 class="p-2" style="font-weight: normal;">@lang('Thuộc tính tạm thời không khả dụng!')</h5>
	@endif
@endif