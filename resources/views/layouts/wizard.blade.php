<html>
@include('partials.wizard.head')

<body class="wizard-page">

  <div class="container mt--5">
    @stack('body_start')

    <div id="app">
      {!! Form::open([
      'url' => url()->current(),
      'role' => 'form',
      'id' => 'form-wizard',
      '@submit.prevent' => 'onSubmit',
      '@keydown' => 'form.errors.clear($event.target.name)',
      ]) !!}
      <div class="card-body">
      <div class="document-loading" v-if="!page_loaded">
        <div><i class="fas fa-spinner fa-pulse fa-7x"></i></div>
    </div>
      @include('flash::message')

      @yield('content')
      </div>
    </div>

  </div>

  @include('partials.wizard.scripts')
</body>

</html>