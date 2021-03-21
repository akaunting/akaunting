@foreach ($items as $item)
	@if ($item->hasChilds())
		@include('menu::bootstrap3.item.dropdown', compact('item'))
	@else
		@include('menu::bootstrap3.item.item', compact('item'))
	@endif
@endforeach
