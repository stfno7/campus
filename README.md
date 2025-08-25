# Proyecto educativo para la materia PP3. 
## Plantilla de campus educativo para la enseñanza online
### Tecnologías utilizadas
- Xampp
- PHP
- PHPMailer (librería para gestionar el envío de emails)
- MYSQL
- Tailwind para los estilos
### La aplicación cuenta con una carpeta que se encuentra organizada de la siguiente manera:
- Carpeta components (Componentes reutilizables como el footer/header para incorporar a las páginas restantes)
- Carpeta config (Configuración a la base de datos)
- Carpeta img (source de imagenes)
- phpmailer (Carpeta por default que viene al instalar la aplicación phpmailer)
- Archivos generales (pages de la aplicación)
### Sprint 1: Abril-Mayo de 2025. Período de 3 semanas. Durante el período del primer sprint, se trabajo en la gestión de usuarios: Login, configuración a la base de datos, creación de cuenta (Dos opciones: A través de un boton en el logueo o insertando un usuario en la base de datos), crear una nueva contraseña (50%).
### Sprint 2: 05 de mayo de 2025. Período de 3 semanas.
### Estudiante: Stefano Parrachini


### Final 22/07
### Modificaciones 11/07:
- Implementación de validación más segura para contraseña: deben tener al menos 5 caracteres y un número como mínimo (por requerimiento del entorno de pruebas, se descartó el uso de password_hash(), es decir, hasheo para contraseñas). // registro.php
- Se reemplazó la lógica de recuperación de contraseña (contraseña dada por default por el sistema) por un flujo con token temporal, enviado por correo al usuario./recuperar.php
- Se crearon los archivos:
--reset_form.php: Formulario envíado al email, para ingresar una nueva contraseña usando el token.
--reset_process.php: Procesa el cambio de contraseña y actualiza la base de datos. Solo contiene lógica de PHP (Verificaciones)
- Se agregó la tabla reset_tokens en la base de datos para almacenar tokens temporales de recuperación de contraseña.
- En estudiantes.php, se agregó una ventana de confirmación (confirm()) al deshabilitar estudiantes.
## Modificaciones 15/07:
- Se creó la página `recursos.php` para mostrar al estudiante los recursos de las materias en las que está inscrito.
- Se agregó la página `perfil.php` que muestra información básica del usuario.
- Se añadió la página `calendario.php` con calendario para mostrar fechas de inicio de clases o exámenes.
- Se agregó la tabla `recursos` en la base de datos, con las columnas necesarias para asociar material didáctico (título y URL) a cada materia.
- Se creó la funcionalidad `recursos.php` que muestra todos los recursos correspondientes a las materias en las que el estudiante está inscrito.
- Se validó que la visualización de los recursos dependa del usuario autenticado y sus inscripciones.