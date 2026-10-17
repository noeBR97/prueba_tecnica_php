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

        if ($stmt -> affected_rows === 0) {
            $stmt -> close();
            throw new Exception('No existe la reserva con el ID: ' . $id);
        }

        self::registrarEvento($id, 'CAMBIO_ESTADO', 'La reserva ha cambiado de estado a ' . $estado);
        $stmt -> close();
    }

    public static function obtenerDetalleReserva($id): ?Reserva {
        $conexion = Database::connect();
        $stmt = $conexion -> prepare("SELECT id, nombre_huesped, email_huesped, nombre_alojamiento, fecha_entrada, fecha_salida, estado, importe, notas, fecha_creacion 
                                        FROM reserva WHERE id = ?");
        $stmt -> bind_param("i", $id);
        $stmt -> execute();

        $resultado = $stmt -> get_result();

        if ($fila = $resultado -> fetch_assoc()) {
            $stmt -> close();

            $reserva = new Reserva(
                $fila['nombre_huesped'],
                $fila['email_huesped'],
                $fila['nombre_alojamiento'],
                $fila['fecha_entrada'],
                $fila['fecha_salida'],
                $fila['importe'],
                $fila['notas'],
                $fila['id']
            );
            $reserva -> estado = $fila['estado'];
            $reserva -> fecha_creacion = $fila['fecha_creacion'];

            return $reserva;
        }

        $stmt -> close();
        return null;
    }

    public static function obtenerReservasFiltradas($estado = null, $fecha_entrada = null, $fecha_salida = null, $nombre_huesped = null): array {
        $conexion = Database::connect();

        $sql = "SELECT FROM reserva WHERE 1=1";
        $tipos = "";
        $parametros = [];

        if (!empty($estado)) {
            $sql .= "AND estado = ?";
            $tipos .= "s";
            $parametros[] = $estado;
        }

        if (!empty($fecha_entrada)) {
            $sql .= "AND fecha_entrada >= ?";
            $tipos .= "s";
            $parametros[] = $fecha_entrada;
        }

        if (!empty($fecha_fin)) {
            $sql .= "AND fecha_salida <= ?";
            $tipos .= "s";
            $parametros[] = $fecha_fin;
        }

        if (!empty($nombre_huesped)) {
            $sql .= "AND nombre_huesped LIKE ?";
            $tipos .= "s";
            $parametros[] = "%" . $nombre_huesped . "%";
        }

        $sql .= "ORDER BY fecha_creacion DESC";

        $stmt = $conexion -> prepare($sql);

        if (!empty($parametros)) {
            $stmt -> bind_param($tipos, ...$parametros);
        }

        $stmt -> execute();
        $resultado = $stmt -> get_result();
        $reservas = $resultado -> fetch_all(MYSQLI_ASSOC);
        $stmt -> close();
        return $reservas;
    }

    private static function registrarEvento($reserva_id, $tipo, $descripcion) {
        $conexion = Database::connect();
        $stmt = $conexion -> prepare("INSERT INTO reserva_evento (reserva_id, tipo, descripcion, fecha_creacion) VALUES (?, ?, ?, NOW()");
        $stmt -> bind_param("iss", $reserva_id, $tipo, $descripcion);
        $stmt -> execute();
        $stmt -> close();
    }
}