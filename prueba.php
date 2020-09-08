<?php 
    $uri = $_SERVER['REQUEST_URI'];
    $uri_data = explode("/",$uri);
    
    echo extract($uri_data);