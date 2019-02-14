<!Doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Day Sheet</title>
</head>
<body>
<div id="app">
    <day-sheet-component></day-sheet-component>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
