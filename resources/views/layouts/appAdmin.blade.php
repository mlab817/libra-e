<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Libra-E') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('css/fontawesome-free/js/all.js') }}" defer></script>
  <script src="{{ asset('js/jquery.js') }}" defer></script>
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('js/Chart.bundle.js') }}" defer></script>

  <script>
    function toggleMenu() {
      var element = document.getElementById("wrapper");
      element.classList.toggle("toggled");
    }
  </script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
  <link href="{{ asset('css/fontawesome-free/css/all.css') }}" rel="stylesheet">
  <link href="{{ asset('timetable/styles/timetablejs.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

</head>

<body>
  <div id="app">
    <main>
      @include('inc.sidebar')
    </main>
  </div>
</body>

</html>
