# Resumen Ejecutivo

## UPTEC Cursos

**UPTEC Cursos** es un sistema web integral para la gestión académica de cursos, inscripciones, calificaciones y reportes, desarrollado para la Universidad Politécnica Territorial de Caracas "Mariscal Sucre".

### Propósito

El proyecto facilita la administración de la oferta académica y la interacción entre cuatro roles principales: Administrador, Analista, Facilitador y Participante. Brinda control de usuarios, cursos, inscripciones, evaluaciones y auditoría de actividades.

### Alcance

- Gestión completa de usuarios y roles.
- Catálogo de cursos con asignación de facilitadores.
- Inscripciones en línea para participantes.
- Registro y cálculo de calificaciones.
- Generación de reportes y respaldo de la base de datos.
- Seguridad con autenticación, CSRF, validación y logging.
- Frontend responsivo construido con HTML, CSS y JavaScript nativo.

### Componentes principales

- `frontend/` - Interfaz de usuario, páginas de login y dashboards por rol.
- `backend/` - API RESTful en PHP con endpoints para autenticación, usuarios, cursos, inscripciones, calificaciones, reportes y backup.
- `db/` - Esquema SQL y datos de prueba para inicializar la base de datos.
- `documentacion/` - Manuales técnicos, operativos y visualizaciones del sistema.

### Funcionalidades destacadas

- Control de acceso por rol con permisos diferenciados.
- Plan de evaluación por curso y cálculo de nota final.
- Auditoría de acciones con logs detallados.
- Backup manual de la base de datos.
- Dashboard con métricas y reportes específicos para cada rol.

### Estado actual

- Documentación técnica completa con arquitectura, base de datos, API, seguridad, roles y permisos, frontend, instalación, despliegue, pruebas y diagramas visuales.
- Diagramas generados en formatos Markdown, SVG y PNG.
- Revisión de interfaz del sidebar y corrección del logo para mejor presentación.
- Cambios subidos al repositorio remoto en `main`.

### Beneficios

- Permite una gestión académica centralizada y segura.
- Facilita la visualización de información para tomadores de decisiones.
- Reduce el esfuerzo administrativo con flujos claros de inscripción y evaluación.
- Está documentado para facilitar instalación, despliegue y mantenimiento.

### Próximos pasos recomendados

1. Validar el sistema en un entorno de staging.
2. Ajustar el despliegue con HTTPS y configuración de servidor en producción.
3. Realizar pruebas de usuario con cada rol.
4. Actualizar los datos iniciales y el manual de usuario según la operación real.
