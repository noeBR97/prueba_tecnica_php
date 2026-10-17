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

-- INSERTS DE PRUEBA
INSERT INTO reserva (nombre_huesped, email_huesped, nombre_alojamiento, fecha_entrada, fecha_salida, estado, importe, notas) VALUES
('Carlos Pérez', 'carlos.perez@example.com', 'Hotel Rural El Descanso', '2026-11-01', '2026-11-05', 'confirmada', 350.50, 'Cerca de la chimenea por favor'),
('Lucía Fernández', 'lucia.f@example.com', 'Parador de Toledo', '2026-11-10', '2026-11-12', 'pendiente', 220.00, 'Llegada tarde'),
('Marcos Ruiz', 'marcos.ruiz@example.com', 'Hotel Sercotel Granada', '2026-12-01', '2026-12-06', 'confirmada', 600.00, 'Habitación doble'),
('Elena Gómez', 'elena.gomez@example.com', 'Hotel Alfonso XIII', '2026-12-15', '2026-12-20', 'pendiente', 950.00, 'Aniversario'),
('David Martínez', 'david.m@example.com', 'Hotel Silken Al-Andalus', '2027-01-05', '2027-01-08', 'cancelada', 280.00, 'Cancelado por motivos personales'),
('Sofía López', 'sofia.l@example.com', 'Hotel Playa Senator', '2027-01-15', '2027-01-22', 'confirmada', 840.50, 'Vistas al mar'),
('Javier Romero', 'javier.romero@example.com', 'Hotel Ritz Madrid', '2027-02-01', '2027-02-03', 'pendiente', 750.00, 'Cama supletoria'),
('Carmen Navarro', 'carmen.n@example.com', 'Hotel Catalonia Barcelona', '2027-02-14', '2027-02-17', 'confirmada', 450.00, 'Escapada romántica'),
('Alejandro Torres', 'alejandro.t@example.com', 'Hotel Meliá Sevilla', '2027-03-01', '2027-03-04', 'pendiente', 310.00, 'Sin preferencias'),
('Paula Jiménez', 'paula.j@example.com', 'Hotel Barceló Valencia', '2027-03-10', '2027-03-15', 'confirmada', 590.00, 'Piso alto'),
('Sergio Ramos', 'sergio.r@example.com', 'Hotel NH Collection', '2027-04-01', '2027-04-03', 'cancelada', 299.99, 'Cambio de fechas'),
('Laura Morales', 'laura.morales@example.com', 'Hotel Vincci Málaga', '2027-04-10', '2027-04-17', 'confirmada', 720.00, 'Desayuno incluido'),
('Adrián Gil', 'adrian.gil@example.com', 'Hotel Eurostars Bilbao', '2027-05-01', '2027-05-04', 'pendiente', 380.00, 'Cerca del centro'),
('Beatriz Ortega', 'beatriz.o@example.com', 'Hotel Hospes Amerigo', '2027-05-15', '2027-05-18', 'confirmada', 670.00, 'Check-in temprano'),
('Mario Alonso', 'mario.alonso@example.com', 'Hotel Abba Burgos', '2027-06-01', '2027-06-05', 'pendiente', 420.00, 'Parking necesario'),
('Sara Mendoza', 'sara.mendoza@example.com', 'Hotel Palacio de Santa Paula', '2027-06-10', '2027-06-13', 'confirmada', 550.00, 'Habitación silenciosa'),
('Pablo Vargas', 'pablo.v@example.com', 'Hotel Westin Palace', '2027-07-01', '2027-07-05', 'confirmada', 1200.00, 'VIP'),
('Irene Castro', 'irene.castro@example.com', 'Hotel Barceló Málaga', '2027-07-15', '2027-07-20', 'pendiente', 680.00, 'Cerca de la estación'),
('Raúl Domínguez', 'raul.d@example.com', 'Hotel Carlton Bilbao', '2027-08-01', '2027-08-05', 'cancelada', 490.00, 'Imprevisto de última hora'),
('Clara Fuentes', 'clara.fuentes@example.com', 'Hotel Maria Cristina', '2027-08-10', '2027-08-15', 'confirmada', 1450.00, 'Luna de miel');