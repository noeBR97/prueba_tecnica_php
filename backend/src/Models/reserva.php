<?php

class Reserva {
    public $id;
    public $nombre_huesped;
    public $email_huesped;
    public $nombre_alojamiento;
    public $fecha_entrada;
    public $fecha_salida;
    public $estado;
    public $importe;
    public $notas;
    public $fecha_creacion;

    public function __construct($nombre_huesped, $email_huesped, $nombre_alojamiento, $fecha_entrada, $fecha_salida, $importe, $notas, $id = null) {
        $this -> id = $id;
        $this -> nombre_huesped = $nombre_huesped;
        $this -> email_huesped = $email_huesped;
        $this -> nombre_alojamiento = $nombre_alojamiento;
        $this -> fecha_entrada = $fecha_entrada;
        $this -> fecha_salida = $fecha_salida;
        $this -> importe = $importe;
        $this -> notas = $notas;
    }
}