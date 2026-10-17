CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_huesped VARCHAR(100) NOT NULL,
    email_huesped VARCHAR(100) NOT NULL,
    nombre_alojamiento VARCHAR(100) NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    importe DECIMAL(10,2) NOT NULL,
    notas VARCHAR(200),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reserva_evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reserva_id) REFERENCES reserva(id) ON DELETE CASCADE
);