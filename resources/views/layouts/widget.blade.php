<div id="@yield('widget-id', 'widget-' . Str::random())" class="@yield('widget-class', 'col-md-12')">

    <div class="card @yield('widget-card-class', 'col-md-12')">

        @include('partials.widget.head')

        <div class="card-body">

            @include('partials.widget.content')

        </div>

    </div>

</div>
