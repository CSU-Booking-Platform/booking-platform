<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      @include('app_head')
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
