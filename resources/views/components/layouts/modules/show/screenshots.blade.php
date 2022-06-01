@props(['module'])

<div class="relative">
    <akaunting-slider :screenshots="{{ json_encode($module->screenshots) }}" :arrow="true" :slider-view="5"></akaunting-slider>
</div>
