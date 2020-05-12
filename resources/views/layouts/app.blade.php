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
    <script src="{{ asset('js/slick-1.8.1/slick/slick.js') }}" defer></script>
    <script src="{{ asset('js/aos.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('js/slick-1.8.1/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('css/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('timetable/styles/timetablejs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="mb-5">
            @include('inc.navbar')
        </main>
        
        @include('inc.footer')
        
    </div>

</body>

<script type="application/javascript">
    AOS.init();
</script>

</html>
