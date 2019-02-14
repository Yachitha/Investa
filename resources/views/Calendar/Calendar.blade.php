<!Doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendar</title>
</head>
<body>
<div id="app">
    <calendar></calendar>
    <notifications group="auth" style="font-family: Verdana;"></notifications>
    <vue-progress-bar></vue-progress-bar>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
