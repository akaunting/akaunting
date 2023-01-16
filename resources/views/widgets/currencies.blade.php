<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }} my-8">
    @include($class->views['header'], ['header_class' => ''])

    <ul class="text-sm space-y-3 my-3">
        @foreach($currencies as $item)
            <li class="flex justify-between">
                {{ $item->name }}

                <span class="font-medium">
                    {{ $item->rate }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
