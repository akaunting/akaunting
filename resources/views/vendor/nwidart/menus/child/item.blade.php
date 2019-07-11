@if ($item->isDivider())
    <li class="divider"></li>
@elseif ($item->isHeader())
    <li class="dropdown-header">{{ $item->title }}</li>
@else
    <li class="{{ $item->isActive() ? 'active' : '' }}">
        <a tabindex="-1" href="{{ $item->getUrl() }}">
            {{ $item->title }}
        </a>
    </li>
@endif
