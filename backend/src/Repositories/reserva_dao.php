<?php

include_once __DIR__ . '/database.php';
include_once __DIR__ . '/../Model/reserva.php';

class ReservaDAO {
    public static function crearReserva (Reserva $reserva) {
        $conexion = Database::connect();
        $stmt = $conexion -> prepare("INSERT INTO reserva (nombre_huesped, email_huesped, nombre_alojamiento, fecha_entrada, fecha_salida, estado, importe, notas, fecha_creacion) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nombre_huesped = $reserva -> nombre_huesped;
        $email_huesped = $reserva -> email_huesped;
        $nombre_alojamiento = $reserva -> nombre_alojamiento;
        $fecha_entrada = $reserva -> fecha_entrada;
        $fecha_salida = $reserva -> fecha_salida;
        $estado = $reserva -> estado;
        $importe = $reserva -> importe;
        $notas = $reserva -> notas;
        $fecha_creacion = $reserva -> fecha_creacion;

        $stmt -> bind_param (
            "ssssssdss",
            $nombre_huesped,
            $email_huesped,
            $nombre_alojamiento,
            $fecha_entrada,
            $fecha_salida,
            $estado,
            $importe,
            $notas,
            $fecha_creacion
        );

        $stmt -> execute();
        
        $idGenerado = $conexion -> insert_id;

        //registro automatico en la tabla reserva_evento
        self::registrarEvento($idGenerado, 'CREACION', 'Reserva creada.');

        $stmt -> close();
        
        return $idGenerado;
    }

    public static function cambiarEstado(Reserva $reserva) {
        $conexion = Database::connect(); 
        $stmt = $conexion -> prepare("UPDATE reserva SET estado = ? WHERE id = ?");
        $estado = $reserva -> estado;
        $id = $reserva -> id;
        $stmt -> bind_param("si", $estado, $id);
        $stmt -> execute();

        self::registrarEvento($id, 'CAMBIO_ESTADO', 'La reserva ha cambiado de estado a ' . $estado);
        $stmt -> close();
    }

    private static function registrarEvento($reserva_id, $tipo, $descripcion) {
        $conexion = Database::connect();
        $stmt = $conexion -> prepare("INSERT INTO reserva_evento (reserva_id, tipo, descripcion, fecha_creacion) VALUES (?, ?, ?, NOW()");
        $stmt -> bind_param("iss", $reserva_id, $tipo, $descripcion);
        $stmt -> execute();
        $stmt -> close();
    }
}