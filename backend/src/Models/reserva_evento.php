<?php

class Reserva_evento {
    public $id;
    public $reserva_id;
    public $tipo;
    public $descripcion;
    public $fecha_creacion;

    public function __construct($reserva_id, $tipo, $descripcion, $fecha_creacion, $id = null) {
        $this -> id = id;
        $this -> reserva_id = $reserva_id;
        $this -> tipo = $tipo;
        $this -> descripcion = $descripcion;
        $this -> fecha_creacion = $fecha_creacion;
    }
}