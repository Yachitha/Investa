<!Doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Main Dashboard - Southern Property Developers</title>
    </head>
    <body>
        <div id="app">
            <layout></layout>
            <notifications group="auth" style="font-family: Verdana;"></notifications>
            <notifications group="general"></notifications>
            <vue-progress-bar></vue-progress-bar>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
