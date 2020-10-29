<?php

$token = "1193011370:AAGaX5JUWguGXf74ehEnr-dqqmoM0RLYIn0";
$id = "1374488713";
$urlMsg = "https://api.telegram.org/bot{$token}/sendMessage";
$msg = "Formosa 75";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlMsg);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "chat_id={$id}&parse_mode=HTML&text=$msg");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
$server_output = curl_exec($ch);
curl_close($ch);

?>