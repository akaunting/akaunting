<canvas style="display: none;" id="{{ $chart->id }}" {!! $chart->formatContainerOptions('html') !!}></canvas>
@include('charts::loader')