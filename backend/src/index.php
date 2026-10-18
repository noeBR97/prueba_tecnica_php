<?php

header('Content-Type: application/json');

include_once __DIR__ . '/Repositories/database.php';
include_once __DIR__ . '/Controller/controlador_reserva.php';
include_once __DIR__ . '/Models/reserva.php';
require_once __DIR__ . '/../vendor/autoload.php';

//limpieza de url
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parametros = explode("/", trim($uri, "/"));

$controlador_reserva = new ControladorReserva();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // endpoint: POST /reserva/crear
        if (isset($parametros[0]) && $parametros[0] == "reserva" && isset($parametros[1]) && $parametros[1] == "crear") {
            $datos = json_decode(file_get_contents('php://input'), true);
            
            $id_reserva = $controlador_reserva->crearReserva($datos);
            
            http_response_code(201);
            echo json_encode([
                'mensaje' => 'Reserva insertada en BBDD correctamente.',
                'id_reserva' => $id_reserva
            ]);
            exit;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
        // endpoint: PUT /reserva/estado/{id}
        if (isset($parametros[0]) && $parametros[0] == "reserva" && isset($parametros[1]) && $parametros[1] == "estado" && isset($parametros[2])) {
            $id_reserva = intval($parametros[2]);
            $datos = json_decode(file_get_contents('php://input'), true);
            $nuevo_estado = $datos['estado'] ?? null;

            $controlador_reserva->cambiarEstadoReserva($id_reserva, $nuevo_estado);

            http_response_code(200);
            echo json_encode([
                'mensaje' => 'El estado de la reserva se ha actualizado correctamente.',
                'id_reserva' => $id_reserva,
                'nuevo_estado' => $nuevo_estado
            ]);
            exit;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($parametros[0]) && $parametros[0] == "reservas") {
            // endpoint: GET /reservas/{id} -> ver detalles de una reserva
            if (isset($parametros[1]) && is_numeric($parametros[1])) {
                $id_reserva = intval($parametros[1]);
                $detalle_reserva = $controlador_reserva->obtenerReserva($id_reserva);

                http_response_code(200);
                echo json_encode([
                    'reserva' => $detalle_reserva
                ]);
                exit;
            }
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}