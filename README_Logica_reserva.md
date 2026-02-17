Resumen del flujo de negocio de una Reserva

Usuario solicita reservar un libro -> se valida y se comprueba disponibilidad.
Si está disponible, se crea Reserva con estado "pendiente" y fecha_vencimiento (p. ej. 48h).
Usuario recoge el libro -> se confirma la reserva: se crea Prestamo asociado y reserva pasa a "confirmada".
Si no se recoge antes de fecha_vencimiento -> tarea programada pone estado "vencida".
Usuario puede cancelar reserva antes de confirmarla.
Reglas importantes

Solo 1 reserva activa por usuario por mismo libro.
No crear reserva si existe préstamo activo del mismo libro (según reglas del negocio).
Control de concurrencia: usar transacciones y lock (select for update) sobre el libro (o sobre fila de inventario) para evitar doble reserva simultánea.
Mantener constantes de estados en modelos (evita strings mágicos).



Para ejecutar las tareas en segundo plano ejecutar: php artisan schedule:work
