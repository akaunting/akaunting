<li class="dropdown {{ $item->hasActiveOnChild() ? 'active' : '' }}">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
    {{ $item->title }}
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" role="menu">
    @foreach ($item->childs as $child)
    	@if ($child->hasChilds())
  			@include('menus::child.dropdown', ['item' => $child])
    	@else
  			@include('menus::item.item', ['item' => $child])
    	@endif
    @endforeach
  </ul>
</li>
