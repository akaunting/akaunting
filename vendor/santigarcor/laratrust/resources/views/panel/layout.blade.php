<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" href="/vendor/laratrust/img/logo.png">
  <title>Laratrust - @yield('title')</title>
  <link href="{{ mix('laratrust.css', 'vendor/laratrust') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body>
<div>
  <nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-center h-16">
        <div class="flex items-center">
          <div class="hidden md:block">
            <div class="flex items-baseline">
              <a href="{{config('laratrust.panel.go_back_route')}}" class="nav-button">← Go Back</a>
              <a
                href="{{ route('laratrust.roles-assignment.index') }}"
                class="ml-4 {{ request()->is('*roles-assigment*') ? 'nav-button-active' : 'nav-button' }}"
              >
                Roles & Permissions Assignment
              </a>
              <a
                href="{{route('laratrust.roles.index')}}"
                class="ml-4 {{ request()->is('*roles') ? 'nav-button-active' : 'nav-button' }}"
              >
                Roles
              </a>
              <a
                href="{{ route('laratrust.permissions.index') }}"
                class="ml-4 {{ request()->is('*permissions*') ? 'nav-button-active' : 'nav-button' }}"
              >
                Permissions
              </a>
            </div>
          </div>
          <div class="ml-10 flex-shrink-0">
            <svg class="h-10 w-auto" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="80" width="272" version="1.1" viewBox="0 0 272 80"><defs><linearGradient id="a" x1="156" gradientUnits="userSpaceOnUse" y1="325" gradientTransform="matrix(.403 0 0 .403 -21.8 -51)" x2="151" y2="120"><stop style="stop-color:#36d1dc" offset="0"/><stop style="stop-color:#5b86e5" offset="1"/></linearGradient></defs><path style="fill:url(#a)" d="m80.3 40a40 40 0 0 1 -40.2 40 40 40 0 0 1 -40.1 -40 40 40 0 0 1 39.8 -40 40 40 0 0 1 40.5 39"/><g style="fill:#fcfcfc"><path style="fill:#fcfcfc" d="m250 36.4h-2.88v-3.54l3.13-0.206 0.577-5.52h3.96v5.52h5.15v3.75h-5.15v9.64q0 3.54 2.84 3.54 0.536 0 1.07-0.124 0.577-0.124 1.03-0.33l0.824 3.5q-0.824 0.288-1.9 0.494-1.03 0.247-2.22 0.247-1.77 0-3.01-0.536-1.19-0.536-1.98-1.48-0.742-0.989-1.11-2.31-0.33-1.36-0.33-3.01v-9.64zm-18.3 11.1q1.36 1.07 2.68 1.69 1.36 0.577 2.93 0.577 1.65 0 2.43-0.659 0.783-0.7 0.783-1.77 0-0.618-0.371-1.07-0.371-0.494-0.989-0.865t-1.4-0.659q-0.783-0.33-1.57-0.659-0.989-0.371-2.02-0.865t-1.85-1.19q-0.783-0.7-1.32-1.61-0.494-0.948-0.494-2.22 0-2.68 1.98-4.37t5.4-1.69q2.1 0 3.79 0.742t2.93 1.69l-2.18 2.88q-1.07-0.783-2.18-1.24-1.07-0.494-2.27-0.494-1.52 0-2.27 0.659-0.7 0.618-0.7 1.57 0 0.618 0.33 1.07 0.371 0.412 0.948 0.742t1.32 0.618q0.783 0.288 1.61 0.577 1.03 0.371 2.06 0.865 1.03 0.453 1.85 1.15 0.865 0.7 1.36 1.73 0.536 0.989 0.536 2.39 0 1.32-0.536 2.47-0.494 1.11-1.48 1.98-0.989 0.824-2.47 1.32-1.48 0.494-3.38 0.494-2.1 0-4.16-0.783-2.02-0.824-3.5-2.02l2.22-3.05zm-23.3-14.9h4.74v12.1q0 2.51 0.742 3.54t2.39 1.03q1.32 0 2.31-0.659 1.03-0.659 2.18-2.14v-13.8h4.74v20.2h-3.87l-0.371-2.97h-0.124q-1.32 1.57-2.88 2.51-1.52 0.948-3.63 0.948-3.25 0-4.74-2.06-1.48-2.1-1.48-5.97v-12.7zm-15.2 0h3.91l0.33 3.58h0.165q1.07-1.98 2.6-3.01 1.52-1.07 3.13-1.07 1.44 0 2.31 0.412l-0.824 4.12q-0.536-0.165-0.989-0.247-0.453-0.0824-1.11-0.0824-1.19 0-2.51 0.948-1.32 0.906-2.27 3.21v12.4h-4.74v-20.2zm-14.2 3.75h-2.88v-3.54l3.13-0.206 0.577-5.52h3.96v5.52h5.15v3.75h-5.15v9.64q0 3.54 2.84 3.54 0.536 0 1.07-0.124 0.577-0.124 1.03-0.33l0.824 3.5q-0.824 0.288-1.9 0.494-1.03 0.247-2.22 0.247-1.77 0-3.01-0.536-1.19-0.536-1.98-1.48-0.742-0.989-1.11-2.31-0.33-1.36-0.33-3.01v-9.64zm-23.1 11q0-3.25 2.84-5.03 2.84-1.77 9.06-2.47 0-0.783-0.206-1.48-0.206-0.742-0.659-1.28-0.412-0.577-1.11-0.865-0.659-0.33-1.69-0.33-1.52 0-2.97 0.577-1.4 0.577-2.72 1.4l-1.73-3.17q1.69-1.07 3.75-1.85 2.1-0.783 4.53-0.783 3.83 0 5.69 2.27 1.85 2.22 1.85 6.47v12h-3.87l-0.371-2.22h-0.124q-1.36 1.15-2.93 1.94-1.52 0.783-3.34 0.783-2.68 0-4.37-1.61-1.65-1.65-1.65-4.33zm4.61-0.371q0 1.36 0.783 1.98 0.824 0.618 2.1 0.618 1.24 0 2.27-0.577t2.14-1.65v-4.53q-2.06 0.247-3.46 0.659t-2.27 0.948q-0.824 0.494-1.19 1.15-0.371 0.659-0.371 1.4zm-18-14.4h3.91l0.33 3.58h0.165q1.07-1.98 2.6-3.01 1.52-1.07 3.13-1.07 1.44 0 2.31 0.412l-0.824 4.12q-0.536-0.165-0.989-0.247-0.453-0.0824-1.11-0.0824-1.19 0-2.51 0.948-1.32 0.906-2.27 3.21v12.4h-4.74v-20.2zm-22.4 14.8q0-3.25 2.84-5.03 2.84-1.77 9.06-2.47 0-0.783-0.206-1.48-0.206-0.742-0.659-1.28-0.412-0.577-1.11-0.865-0.659-0.33-1.69-0.33-1.52 0-2.97 0.577-1.4 0.577-2.72 1.4l-1.73-3.17q1.69-1.07 3.75-1.85 2.1-0.783 4.53-0.783 3.83 0 5.69 2.27 1.85 2.22 1.85 6.47v12h-3.87l-0.371-2.22h-0.124q-1.36 1.15-2.93 1.94-1.52 0.783-3.34 0.783-2.68 0-4.37-1.61-1.65-1.65-1.65-4.33zm4.61-0.371q0 1.36 0.783 1.98 0.824 0.618 2.1 0.618 1.24 0 2.27-0.577t2.14-1.65v-4.53q-2.06 0.247-3.46 0.659t-2.27 0.948q-0.824 0.494-1.19 1.15-0.371 0.659-0.371 1.4zm-24-21.1h4.78v22.9h11.2v4.04h-16v-26.9z"/></g><path d="m56.1 38.5v-10.6c0-8.87-7.17-16-16-16-8.87 0-16 7.17-16 16v10.6c-1.35 0.439-2.7 0.892-4.01 1.44v24.1c12.8 5.33 27.3 5.33 40.1 0v-24.1c-1.4-0.5-2.7-0.9-4.1-1.4zm-12 21.5h-8.02l1.5-8.97c-0.892-0.736-1.5-1.81-1.5-3.06 0-2.22 1.79-4.01 4.01-4.01s4.01 1.79 4.01 4.01c0 1.25-0.604 2.32-1.5 3.06zm7.5-23c-7.9-1.9-15.2-1.9-23.1 0v-9.05c0-6.66 4.94-12 11.6-12s11.6 5.38 11.6 12z" style="fill:#fff"/></svg>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg class="block h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.

      Open: "block", closed: "hidden"
    -->
    <div class="hidden md:hidden">
      <div class="px-2 pt-2 pb-3 sm:px-3">
        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-gray-900 focus:outline-none focus:text-white focus:bg-gray-700">Dashboard</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Team</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Projects</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Calendar</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Reports</a>
      </div>
    </div>
  </nav>

  <header class="bg-white shadow">
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold leading-tight text-gray-900">
        @yield('title')
      </h1>
    </div>
  </header>
  <main>
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
      @foreach (['error', 'warning', 'success'] as $msg)
        @if(Session::has('laratrust-' . $msg))
        <div class="alert-{{ $msg }}" role="alert">
          <p>{{ Session::get('laratrust-' . $msg) }}</p>
        </div>
        @endif
      @endforeach
      <div class="px-4 py-6 sm:px-0">
        @yield('content')
      </div>
    </div>
  </main>
</div>
</body>
</html>