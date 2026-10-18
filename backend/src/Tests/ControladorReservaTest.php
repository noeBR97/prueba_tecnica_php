<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Controller/controlador_reserva.php';

class ControladorReservaTest extends TestCase {
    public function test_email_invalido() {
        $this -> expectException(Exception::class);
        $this -> expectExceptionCode(400);

        $controlador = new ControladorReserva();

        $datos_erroneos = [
            'nombre_huesped' => 'Ana',
            'email_huesped' => 'introduzco-email-invalido',
            'nombre_alojamiento' => 'Hotel Central',
            'fecha_entrada' => '2026-11-01',
            'fecha_salida' => '2026-11-05',
            'importe' => 150.00
        ];

        $controlador -> crearReserva($datos_erroneos);
    }
}