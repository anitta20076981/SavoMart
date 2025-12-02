<!DOCTYPE html>
<html>
<head>
    <title>Display Excel Data</title>
</head>
<body>
    <iframe srcdoc="{{ base64_encode($content) }}" style="width: 100%; height: 600px;"></iframe>
</body>
</html>
