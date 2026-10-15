<?php

include_once __DIR__ . '/../Helper/parametros.php';

class Database {

    public static function connect() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conexion = new mysqli(Parametros::$host, Parametros::$usuario, Parametros::$clave, Parametros::$bd);
        if ($conexion->connect_error) {
            throw new Exception("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }
}