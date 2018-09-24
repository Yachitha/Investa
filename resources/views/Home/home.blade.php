<!Doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Login to Southern Property Developers</title>
    </head>
    <body>
        <div id="app">
            <home-component></home-component>
            <notifications group="auth"></notifications>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
