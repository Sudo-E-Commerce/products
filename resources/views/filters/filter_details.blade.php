@php
	if (isset($filter_id) && !empty($filter_id)) {
		$filter_details = \Sudo\Product\Models\FilterDetail::where('filter_id', $filter_id)->where('status', '<>', -1)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
	}
@endphp
<div class="col-lg-9">
	<div class="card listdata" id="listdata">
		<div class="card-header" style="padding: .75rem 1.25rem" data-card-widget="collapse">
			<div class="card-title">@lang('Bộ lọc')</div>
			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
			</div>
		</div>
		<div class="card-body table-responsive p-0">
			<table class="table table-striped table-bordered table-head-fixed">
				<thead>
					<tr>
						<th class="text-center pl-3" style="width: 50px;">@lang('STT')</th>
						<th class="text-center pl-3">@lang('Tên')</th>
						<th class="text-center pl-3">@lang('Slug')</th>
						<th class="text-center pl-3">@lang('Sắp xếp')</th>
						<th class="text-center pl-3">@lang('Thời gian')</th>
						<th class="text-center pl-3">@lang('Trạng thái')</th>
						<th class="text-center pl-3">@lang('Xóa')</th>
					</tr>
				</thead>
				<tbody>
					@if (isset($filter_details) && count($filter_details) > 0)
						@foreach ($filter_details as $key => $value)
							<tr data-table="filter_details" data-id="{!! $value->id !!}">
								<td class="text-center">{{$key+1}}</td>
								@include('Table::components.edit_text', [ 'width' => 'auto', 'name' => 'name' ])
								@include('Table::components.edit_text', [ 'width' => 'auto', 'name' => 'slug' ])
								@include('Table::components.edit_text', [ 'width' => '100px', 'name' => 'order' ])
								@include('Table::components.time')
								@include('Table::components.edit_array',[
									'name' => 'status',
									'value' => $value->status, 
									'options' => config('app.status')
								])
								<td class="text-center table-action">
						            <a class="delete-record" href="javascript:;" data-delete_filter data-message="@lang('Table::table.delete_question')"><i class="fas fa-trash text-red"></i></a>
						        </td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="7" class="text-center">@lang('Table::table.no_record')</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			// Xóa nhanh chi tiết thuộc tính
			$('body').on('click', '*[data-delete_filter]', function(e) {
				e.preventDefault();
				e = $(this);
				// Bảng
				table = $(this).closest('*[data-table]').data('table');
				// id
				id = $(this).closest('*[data-id]').data('id');
				// mảng id_array
				id_array = [];
				id_array.push(id);
				// Chuẩn hóa data
				data = {
					table 		: table,
					id_array 	: id_array,
				};
				if (confirm( $(this).data('message') )) {
					loadAjaxPost('/{{config('app.admin_dir')}}/ajax/quick_delete', data, {
						beforeSend: function(){},
				        success:function(result){
				        	if (result.status == 1) {
				        		alertText(result.message);
				        		e.closest('tr').fadeOut(function() {
				        			$(this).remove();
				        			if (e.closest('tbody').find('tr').length == 0) {
				        				$('.listdata').find('tbody').append(`
											<tr>
												<td colspan="7" class="text-center">@lang('Table::table.no_record')</td>
											</tr>
				        				`);
				        			}
				        		});
				        	} else {
				        		alertText(result.message, 'warning');
				        	}
				        },
				        error: function (error) {}
					});
				}
			});
		})
	</script>
</div>