# Prueba tecnica, respuestas

---

## Respuestas a prueba tecnica

### Modelo de datos

Se realiza el análisis basado en el caso entregado y se define las tablas con las cuales se van a trabajar, son las siguientes:
  - clientes
  - estados
  - logSolicitud
  - rol
  - rolicitudes
  - usuarios

las relaciones implementas son 1:1 entre la tabla **usuarios** y **roles**. Para las relaciones 1:n se asociaron las siguientes tablas: **Solicitudes** con el id de la tabla **Clientes**, id del estado con la tabla **Estados** y id usuario con la tabla usuario **Usuarios**. Esto con el fin de tener presente cual es la solicitud, el estado presentado, a quien esta asociado dicha solicitud y como asesor y auxiliar, quien se encarga del proceso.

### Backend api rest

