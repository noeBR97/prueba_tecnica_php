<?php
include_once __DIR__ . '/../Repositories/reserva_dao.php';
include_once __DIR__ . '/../Models/reserva.php';

class ControladorReserva {
    public function crearReserva($datos) {
        $campos_requeridos = ['nombre_huesped', 'email_huesped', 'nombre_alojamiento', 'fecha_entrada', 'fecha_salida', 'importe'];

        foreach ($campos_requeridos as $campo) {
            if (empty($datos[$campo])) {
                throw new Exception('El campo ' . $campo . ' es obligatorio.', 400);
            }
        }

        if (!filter_var($datos['email_huesped'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del correo electrónico no es válido.', 400);
        }

        if ($datos['fecha_salida'] <= $datos['fecha_entrada']) {
            throw new Exception('La fecha de salida debe ser posterior a la fecha de entrada.', 400);
        }
    
        $reserva = new Reserva(
            $datos['nombre_huesped'],
            $datos['email_huesped'],
            $datos['nombre_alojamiento'],
            $datos['fecha_entrada'],
            $datos['fecha_salida'],
            $datos['importe'],
            $datos['notas'] ?? null
        );

        return ReservaDAO::crearReserva($reserva);
    }
}