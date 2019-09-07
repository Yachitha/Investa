<!Doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Login to Southern Property Developers</title>
        <meta name="description" content="Southern Property Developers is a company which helps entrepreneurs to invest
on their projects. Join with us, attractive interest rates to choose your plan as you wish.">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/spd.ico') }}"/>
    </head>
    <body>
        <div id="app">
            <home-component></home-component>
            <notifications group="auth" style="font-family: Verdana;"></notifications>
            <vue-progress-bar></vue-progress-bar>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
