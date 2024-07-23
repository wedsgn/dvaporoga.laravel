<!DOCTYPE html>
<html lang="ru">

@include('parts.head')

<body>

    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    @vite('resources/js/app.js')
</body>

</html>
