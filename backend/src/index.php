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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}