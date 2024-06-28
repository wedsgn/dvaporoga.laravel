<!DOCTYPE html>
<html lang="ru-RU">

@include('parts.head')

<body>

    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    @vite('resources/js/app.js')
</body>

</html>
