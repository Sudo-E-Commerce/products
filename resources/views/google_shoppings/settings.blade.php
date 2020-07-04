@extends('Core::layouts.app')

@section('title') @lang($title ?? 'Setting::general.title')  @endsection
@section('content')

<form action="" class="form-horizontal" enctype="multipart/form-data" method="post">
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title">@lang($note ?? 'Form::form.require_text')</div>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body">
				@include('Form::generate')
			</div>
		</div>
	</div>
</div>
</form>

@endsection