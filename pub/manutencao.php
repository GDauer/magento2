<?php
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 300');//300 seconds
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loja Virtual Cozinhas Itatiaia</title>
    <style>
        body { padding: 200px 0; margin: 0; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #333; text-align: center }
        .box { margin: 0 auto; }
        .box img { margin: 0 0 20px 0 }
        .box p { color: #fff; font-size: 16px; margin: 5px 0; opacity: 0.7 }
    </style>
</head>
<body>
<div class="box">
    <img src="manutencao.png" alt="Digital Hub">
</div>
</html>
<?php exit; ?>
