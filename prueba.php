<?php 

    $uri = $_SERVER['REQUEST_URI'];
    $uri_data = explode("/",$uri);
    
    var_dump(extract($uri_data));
?>