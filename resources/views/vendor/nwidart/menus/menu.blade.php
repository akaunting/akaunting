@foreach ($items as $item)
    @if ($item->hasChilds())
        @include('menus::item.dropdown', compact('item'))
    @else
        @include('menus::item.item', compact('item'))
    @endif
@endforeach
