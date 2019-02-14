<!Doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Pdf</title>
</head>
<body>
<div id="app">
    <pdf-generator></pdf-generator>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
