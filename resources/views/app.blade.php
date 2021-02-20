<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <title>{{ config('app.name', 'Laravel') }}</title>
      @include('app_head')
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
