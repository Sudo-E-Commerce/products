@include('Table::components.image',['image' => $value->getImage()])
@include('Table::components.link',['text' => $value->name, 'url' => route('admin.products.edit', $value->id)])
<td style="width: 200px;">
	<a href="{{ route('admin.product_categories.edit', $value->category_id) }}" target="_blank">{{ $product_categories[$value->category_id] ?? '' }}</a>
</td>