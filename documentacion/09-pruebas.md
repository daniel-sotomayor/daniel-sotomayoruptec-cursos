# Plan de Pruebas

## Objetivo

Verificar que UPTEC Cursos funcione correctamente en todos los roles, asegurando la integridad de los datos, la seguridad y la experiencia de usuario.

## Alcance

- Autenticación y registro
- Gestión de usuarios, cursos e inscripciones
- Control de calificaciones y reportes
- Seguridad de sesión, CSRF y validación de entradas
- Flujo de roles (Administrador, Analista, Facilitador, Participante)

## Entorno de Pruebas

- PHP 8.0+ con extensión PDO
- MySQL 5.7+
- Navegador moderno (Chrome, Firefox)
- Servidor web local (Apache, Nginx o PHP Built-in)
- Base de datos cargada con `db/schema.sql` y `db/seed.sql`

## Casos de Prueba Principales

### 1. Autenticación

- Iniciar sesión con credenciales válidas para cada rol
- Intentar iniciar sesión con contraseña incorrecta
- Registrar un nuevo participante
- Validar redirección al dashboard correcto según rol
- Verificar que no se permite acceso a vistas sin autenticación

### 2. Gestión de Usuarios (Administrador)

- Crear usuario nuevo con rol Analista
- Editar datos de un facilitador existente
- Eliminar usuario y verificar que sus datos ya no aparecen
- Validar restricciones de correo y cédula únicos

### 3. Gestión de Cursos

- Crear nuevo curso con facilitador asignado
- Editar curso y cambiar fechas o cupo
- Cancelar curso y verificar estado
- Consultar lista de cursos disponibles para inscripción

### 4. Inscripciones

- Inscribir participante en curso disponible
- Confirmar que el cupo máximo se respeta
- Verificar historial de inscripciones del participante
- Cambiar estado de inscripción (aprobado, pendiente, rechazado)

### 5. Calificaciones

- Registrar nota para cada evaluación
- Verificar cálculo de nota final en la inscripción
- Consultar notas desde el dashboard del participante
- Exportar o generar reporte de calificaciones

### 6. Reportes y Auditoría

- Generar reporte de cursos y estadística de inscripciones
- Revisar logs de auditoría para acciones CRUD
- Descargar backup de la base de datos
- Validar que las métricas reflejan datos reales

### 7. Seguridad

- Enviar request con token CSRF inválido y verificar rechazo
- Intentar inyección SQL y comprobar que no se ejecuta
- Validar sanitización de entrada en formularios de usuario
- Probar cierre de sesión y verificación de sesión expirada

## Resultados Esperados

- Todas las operaciones CRUD deben devolver éxito con datos consistentes
- Los permisos deben respetar el rol asignado
- Las páginas protegidas deben redirigir al login si no hay sesión
- Los datos introducidos deben almacenarse correctamente en la base de datos
- Los reportes y dashboards deben reflejar la información real

## Observaciones

- Si se detectan errores de validación, deben mostrarse mensajes claros al usuario.
- La auditoría debe registrar acciones relevantes con usuario, fecha y tabla afectada.
- El sistema debe evitar la corrupción de datos al editar o eliminar registros.
