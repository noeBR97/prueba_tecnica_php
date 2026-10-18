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

    public function obtenerReserva($id) {
        if (!is_numeric($id) || intval($id) <= 0) {
            throw new Exception('ID de reserva no válido.', 400);
        }

        $reserva = ReservaDAO::obtenerDetalleReserva($id);

        if (!$reserva) {
            throw new Exception('Reserva no encontrada.', 404);
        }

        return $reserva;
    }

    public function cambiarEstadoReserva($id, $nuevo_estado) {
        if (!is_numeric($id) || intval($id) <= 0) {
            throw new Exception('ID de reserva no válido.', 400);
        }

        if (!in_array($nuevo_estado, ['pendiente', 'confirmada', 'cancelada'])) {
            throw new Exception('Estado no válido.', 400);
        }

        $reserva = new Reserva(null, null, null, null, null, null, null, intval($id));
        $reserva -> estado = $nuevo_estado;

        ReservaDao::cambiarEstado($reserva);
        return true;
    }

    public function listarReservasFiltradas($filtros) {
        $estado = $filtros['estado'] ?? null;
        $fecha_entrada = $filtros['fecha_entrada'] ?? null;
        $fecha_salida = $filtros['fecha_salida'] ?? null;
        $nombre_huesped = $filtros['nombre_huesped'] ?? null;

        $reservas = ReservaDAO::obtenerReservasFiltradas($estado, $fecha_entrada, $fecha_salida, $nombre_huesped);
        return $reservas;
    }
}