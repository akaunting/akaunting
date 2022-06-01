@stack('content_start')
<div id="app">
    @stack('content_content_start')

    {!! $slot !!}

    @stack('content_content_end')

    <notifications></notifications>

    <form id="form-dynamic-component" method="POST" action="#"></form>

    <component v-bind:is="component"></component>
</div>
@stack('content_end')
