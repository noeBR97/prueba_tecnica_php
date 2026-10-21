# PRUEBA TÉCNICA - GESTOR DE RESERVAS
Aplicación web Full-Stack desarrollada con **Vanilla JavaScript**, **PHP** y **MySQL**.

## URL de la App desplegada
[gestion-reservas.freehosting.dev](https://gestion-reservas.freehosting.dev)

## Funcionalidades
- **Visualización de reservas:** Listado en tiempo real de todas las reservas registradas, con información sobre el huésped, contacto, alojamiento, fechas, importe, notas y estado.
- **Filtrado avanzado:** Búsqueda y filtrado combinable por nombre de huésped, estado (`confirmada`, `pendiente` y `cancelada`) y rango de fechas (entrada/salida).
- **Creación de reservas:** Formulario con validaciones en cliente y servidor para registrar nuevas reservas.
- **Gestión de estados:** Acción directa desde la interfaz para cancelar reservas de forma instantánea.

## Endpoints de la API backend

**NOTA:** todas estas rutas se pueden probar con la colección `endpoints_backend.postman_collection.json` ubicada en /backend si se importa en Postman. En la ruta **GET /reservas** ya vienen los filtros preparados para probar combinaciones.

---

| Acción | Método HTTP | Ruta (Endpoint) | Descripción / Parámetros | 
| :--- | :---: | :--- | :--- |
| **Crear Reserva** | `POST` | `/reserva/crear` | Crea un nuevo registro de reserva. **Body JSON:** `nombre_huesped`, `email_huesped`, `nombre_alojamiento`, `fecha_entrada`, `fecha_salida`, `importe`, `notas`| 
| **Listar Reservas** | `GET` | `/reservas` | Obtiene el listado completo de reservas. Admite filtros opcionales vía query params (`estado`, `nombre_huesped`, `fecha_entrada`, `fecha_salida`). |
| **Ver Detalle Reserva** | `GET` | `/reservas/{id}` | Recupera los detalles de una reserva específica identificada por su ID. |
| **Cambiar Estado** | `PUT` | `/reserva/estado/{id}` | Actualiza el estado de una reserva específica pasándole el ID en la URL. **Body JSON:** `estado`|

## Instrucciones Setup Local
1. Crear la BBDD de MySQL en **phpMyAdmin** con el nombre `prueba_tecnica_reservas`. (En mi caso he utilizado Xampp como herramienta). 
2. Importar el archivo .sql ubicado en la carpeta `/backend`. (Ya viene con datos de prueba)

   ---

   ### Cómo importar el .sql
   1. Una vez creada la BBDD en **phpMyAdmin**, ir a la pestaña **Importar** en la barra superior.
   2. Seleccionar el archivo y dar a importar abajo del todo en la web.
   3. Automáticamente se crearán las tablas **reserva** (con datos de prueba) y **reserva_evento**.

    ---
      
3. En la terminal: `cd backend` e instalar dependencias con `composer install`.
4. Para levantar el backend: `cd backend` y `php -S localhost:8090 src\index.php`
5. Abrir el `index.html` para ver frontend con backend.

---

# Decisiones de Diseño
He aplicado una estructura modular basada en el patrón Modelo-Vista-Controlador.
Este patrón me resulta más limpio y facilita la reutilización.
- El controlador gestiona la lógica de negocio.
- El DAO centraliza las consultas SQL, así aislo la lógica de la BBDD del resto de la aplicación.

### Por qué MySQL?
Es una de las BBDD que más he utilizado, por lo que me hace sentir más cómoda a la hora de trabajar con ella.

---

# Qué haría con más tiempo?
- Añadiría un sistema de login con autenticación y seguridad con JWT y también diferenciaría los roles, ya sea admin, usuarios con permisos...

- También utilizaría algún framework para el frontend, como React para dar a la interfaz una interactividad más fluida y reactiva.

**NOTA:** No he implementado las vistas de Twig, ya que no lo he utilizado nunca y, aunque he dedicado tiempo en intentar aprenderlo, he visto que me tomaría más tiempo del necesario para poder aplicarlo a este proyecto. Pero es una herramienta muy útil para la reutilización del html.

---

# Escalabilidad
Para evitar cuellos de botella implementaría una capa de caché en memoria, como Redis, ya que se reducen mucho las consultas directas a MySQL.

También metería la aplicación en un contenedor Docker, así se empaquetaría la app completa con todo lo que necesita para funcionar. Con un solo comando se levantarían todos los servicios que tengamos, como back, front, la bbdd, nginx/apache, redis...

---

## Autora

**Noelia Barrionuevo** 
  * Full Stack Web Developer
  * [LinkedIn](https://www.linkedin.com/in/noelia-barrionuevo-romero-44209b178) | [GitHub](https://github.com/noeBR97)

---

## Licencia

Este proyecto está bajo la Licencia MIT.