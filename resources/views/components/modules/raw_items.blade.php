@foreach ($modules as $item)
    <x-modules.item :model="$item" />
@endforeach
