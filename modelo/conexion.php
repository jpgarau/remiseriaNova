<?php

class Conexion{
    static $mysqli;
	public static function abrir(){
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$bbdd = 'remiseria';
		$mysqli = new mysqli($host,$user,$pass,$bbdd);
		return $mysqli;
	}
}