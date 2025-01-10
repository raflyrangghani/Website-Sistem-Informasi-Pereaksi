<!DOCTYPE html>
<html>
<head>
    <title>Stock Notification</title>
</head>
<body>
    <h1>{{ $pereaksi->ITEM }} is {{ $status }}</h1>
    <p>Dear user,</p>
    <p>The reagent <strong>{{ $pereaksi->ITEM }}</strong> (Code: {{ $pereaksi->KODE }}) is currently <strong>{{ $status }}</strong>.</p>
    <p>Please take appropriate action.</p>
</body>
</html>