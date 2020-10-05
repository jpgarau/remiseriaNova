<?php


include_once 'modelo/telegram.php';

$pruebaTel = new Telegram();

$respuesta = $pruebaTel->actualizarTabla();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <pre>
        <?php var_dump($respuesta); ?>
    </pre>
</body>
</html>