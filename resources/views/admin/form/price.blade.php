{{-- 
	@include('Product::admin.form.price', [
    	'name'				=> $item['name'],
		'value' 			=> $item['value'],
		'required' 			=> $item['required'],
		'label' 			=> $item['label'],
		'placeholder' 		=> $item['placeholder'],
    ])
--}}

<div class="form-group row">
    @if ($has_full == true)
        <label for="{{ $name??'' }}" class="col-lg-12 col-form-label">@if($required==1) * @endif @lang($label??'')</label>
    @else
        <label for="{{ $name??'' }}" class="col-lg-3 col-md-2 col-form-label text-right">@if($required==1) * @endif @lang($label??'')</label>
    @endif
    <div class="col-lg-4 col-md-5">
      	<input type="number" class="form-control" autocomplete="off" name="{{ $name??'' }}" id="{{ $name??'' }}" placeholder="@lang($placeholder??$label??$name??'')" value="{{ old($name)??$value??'' }}">
    </div>
    <div class="col-lg-4 col-md-5">
        <span id="{{$name}}_price" style="line-height: 38px;"></span>
    </div>
    <script>
        $(document).ready(function() {
            $('body').on('keyup', '#{{$name}}', function() {
                price = $(this).val();
                $('#{{$name}}_price').html(formatPrice(price));
            });
            $('#{{$name}}').keyup();
            @if ($required==1)
                validateInput('#{{$name}}', '@lang($label??$placeholder??$name??'') @lang('Form::form.valid.no_empty')');
            @endif
        });
        // Định dạng giá
        function formatPrice(number) {
            if (number == '') {
                return '';
            } else if (number == 0) {
                return '0đ';
            } else {
                number += '';
                x = number.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + '.' + '$2');
                }
                number = x1 + x2 +"đ";
                return number;
            }
        }
    </script>
</div>