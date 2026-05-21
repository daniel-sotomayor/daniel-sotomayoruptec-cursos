# Manual de Usuario - Administrador

## UPTEC Cursos v2.0 - Guía del Administrador del Sistema

---

## Introducción

Bienvenido al sistema **UPTEC Cursos**. Como Administrador, tienes control total sobre el sistema de gestión académica. Este manual te guiará paso a paso en todas las funcionalidades disponibles.

**Tu rol incluye:**
- Gestión completa de usuarios (todos los roles)
- Control de cursos y oferta académica
- Monitoreo de inscripciones y calificaciones
- Generación de reportes y estadísticas
- Auditoría de actividad del sistema
- Respaldo de la base de datos

---

## Acceso al Sistema

### 1. Iniciar Sesión

1. Abre tu navegador web (Chrome, Firefox, Edge)
2. Ve a: `http://localhost/uptec-cursos/frontend/login.html`
3. Ingresa tus credenciales:
   - **Correo:** `admin@uptec.edu.ve`
   - **Contraseña:** `admin123` (cámbiala después del primer ingreso)
4. Haz clic en **"Iniciar Sesión"**
5. Serás redirigido automáticamente al panel de administrador

### 2. Cambiar Contraseña (Recomendado)

1. Ve al panel de usuario (icono 👤 en la esquina superior)
2. Selecciona **"Cambiar Contraseña"**
3. Ingresa tu contraseña actual
4. Ingresa tu nueva contraseña (mínimo 6 caracteres, letra y número)
5. Confirma la nueva contraseña
6. Haz clic en **"Guardar"**

---

## Dashboard - Panel Principal

Al iniciar sesión verás el dashboard con métricas del sistema:

### Tarjetas de Resumen (Parte Superior)

| Tarjeta | Información | Acción |
|---------|-------------|--------|
| 👥 Estudiantes | Total de participantes registrados | Click para ver lista |
| 👨‍🏫 Facilitadores | Total de docentes | Click para ver lista |
| 👨‍💼 Analistas | Total de analistas académicos | Click para ver lista |
| 📚 Cursos | Total de cursos en el sistema | Click para ver lista |
| 📝 Inscripciones | Total de inscripciones | Click para detalles |

### Gráficos y Estadísticas

- **Cursos por Estado:** Visualiza cuántos cursos están Planificados, En Curso, Finalizados o Cancelados
- **Estudiantes por Área:** Distribución de participantes por carrera (Informática, Mecánica, etc.)
- **Top 5 Cursos:** Los cursos con mayor cantidad de inscritos

### Listas Rápidas

- **Cursos Recientes:** Últimos cursos creados
- **Usuarios Recientes:** Últimos usuarios registrados
- **Actividad Reciente:** Últimas acciones en el sistema (login, creaciones, modificaciones)

---

## Gestión de Usuarios

### Crear Nuevo Usuario

1. En el menú lateral, haz clic en **"👥 Usuarios"**
2. Haz clic en el botón **"+ Nuevo Usuario"**
3. Completa el formulario:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Cédula** | V + número (6-9 dígitos) | V12345678 |
   | **Nombre** | Primer nombre | Juan |
   | **Apellidos** | Apellidos completos | Pérez García |
   | **Correo** | @uptec.edu.ve para staff | jperez@uptec.edu.ve |
   | **Teléfono** | 04XXXXXXXX | 04121234567 |
   | **Rol** | Selecciona el rol | Facilitador |

4. Haz clic en **"Crear Usuario"**
5. **Importante:** Anota la contraseña temporal que se genera automáticamente
6. Entrega la contraseña al usuario (debe cambiarla en su primer ingreso)

### Editar Usuario

1. En la lista de usuarios, busca el usuario (usa el buscador 🔍)
2. Haz clic en el botón **"✏️"** (editar) al lado del usuario
3. Modifica los campos necesarios:
   - Nombre, apellidos, teléfono
   - Estado (Activo/Inactivo)
   - Rol (con precaución)
4. Haz clic en **"Guardar Cambios"**

### Desactivar/Eliminar Usuario

⚠️ **Advertencia:** Esta acción no elimina el usuario permanentemente, solo lo desactiva.

1. Busca el usuario en la lista
2. Haz clic en el botón **"🗑️"** (eliminar)
3. Confirma la acción en el mensaje de confirmación
4. El usuario quedará inactivo y no podrá iniciar sesión

**Nota:** No puedes eliminar tu propia cuenta de administrador (protección del sistema).

### Ver Detalles de Usuario

1. Haz clic en el nombre del usuario
2. Se abrirá una ventana con:
   - Información personal completa
   - Último acceso
   - Cursos asociados (si es facilitador o participante)
   - Historial de inscripciones

---

## Gestión de Cursos

### Crear Nuevo Curso

1. En el menú lateral, haz clic en **"📚 Cursos"**
2. Haz clic en **"+ Nuevo Curso"**
3. Completa el formulario:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Código** | Identificador único | PROG-001 |
   | **Nombre** | Nombre completo | Programación en PHP |
   | **Descripción** | Detalle del contenido | Curso de desarrollo web... |
   | **Duración** | Horas totales | 40 |
   | **Fecha Inicio** | YYYY-MM-DD | 2026-02-01 |
   | **Fecha Fin** | YYYY-MM-DD | 2026-04-01 |
   | **Cupo Máximo** | Límite de estudiantes | 25 |
   | **Área** | Carrera/departamento | Informática |
   | **Nivel** | Dificultad | Intermedio |
   | **Estado** | Planificado, En Curso... | Planificado |
   | **Facilitador** | Docente asignado | Juan Pérez |

4. Haz clic en **"Crear Curso"**

### Asignar Facilitador a Curso

1. Ve a **"📚 Cursos"**
2. Busca el curso y haz clic en **"✏️"**
3. En el campo **"Facilitador"**, selecciona de la lista desplegable
4. Solo aparecerán facilitadores activos
5. Guarda los cambios

### Crear Plan de Evaluación

Cada curso necesita un plan de evaluación antes de iniciar:

1. Ve a detalles del curso
2. Sección **"Plan de Evaluación"**
3. Haz clic en **"+ Agregar Evaluación"**
4. Completa:
   - **Nombre:** Ej. "Parcial 1"
   - **Tipo:** Parcial, Final, Proyecto, Asistencia, Trabajo, Otro
   - **Peso:** Porcentaje que representa (la suma debe ser 100%)
   - **Fecha:** Cuándo se evaluará
   - **Orden:** Secuencia (1, 2, 3...)
5. Repite hasta completar el 100%
6. Verifica que la suma de pesos sea exactamente 100%

### Editar Curso

1. Busca el curso en la lista
2. Haz clic en **"✏️"**
3. Modifica los campos necesarios
4. Guarda cambios

**Nota:** Si un curso ya tiene inscritos, ten cuidado al modificar fechas o estado.

### Eliminar Curso

⚠️ **Solo elimina cursos sin inscripciones.**

1. Busca el curso
2. Haz clic en **"🗑️"**
3. Confirma la eliminación

---

## Gestión de Inscripciones

### Ver Todas las Inscripciones

1. Menú lateral → **"📝 Inscripciones"**
2. Verás una tabla con:
   - Participante (nombre y cédula)
   - Curso
   - Fecha de inscripción
   - Estado actual
   - Nota final (si aplica)

### Filtrar Inscripciones

Usa los filtros superiores:
- **Por Curso:** Selecciona un curso específico
- **Por Estado:** Inscrito, En Progreso, Completado, Abandonado, Reprobado
- **Por Participante:** Busca por nombre o cédula

### Cambiar Estado de Inscripción

1. Busca la inscripción en la lista
2. Haz clic en el estado actual
3. Selecciona el nuevo estado:
   - **Inscrito:** Registrado pero no inicia
   - **En Progreso:** Cursando actualmente
   - **Completado:** Aprobó el curso
   - **Abandonado:** Se retiró
   - **Reprobado:** No aprobó
4. Agrega observaciones si es necesario
5. Guarda

---

## Calificaciones

### Ver Calificaciones del Sistema

1. Menú lateral → **"📊 Calificaciones"**
2. Verás todas las calificaciones registradas
3. Puedes filtrar por:
   - Curso
   - Participante
   - Tipo de evaluación

### Entender el Sistema de Notas

- Escala: **0 a 20** puntos
- **Aprobación:** Generalmente 10 o más (según política de UPTEC)
- Cálculo automático: El sistema pondera según el peso de cada evaluación

---

## Reportes y Estadísticas

### Dashboard de Reportes

1. Menú lateral → **"📈 Reportes"**
2. Visualiza gráficos de:
   - Promedio general del sistema
   - Tasa de aprobación
   - Cursos por área
   - Inscripciones mensuales
   - Top facilitadores

### Interpretar Estadísticas

| Métrica | Significado |
|---------|-------------|
| **Promedio General** | Media de todas las notas finales |
| **Tasa de Aprobación** | % de inscripciones completadas exitosamente |
| **Inscripciones por Mes** | Tendencia de participación |
| **Top Facilitadores** | Docentes con mejor desempeño (por promedio de sus estudiantes) |

---

## Auditoría del Sistema

### Ver Logs de Actividad

1. Menú lateral → **"🔍 Auditoría"**
2. Verás todas las acciones realizadas en el sistema:
   - Login/Logout de usuarios
   - Creaciones de registros
   - Modificaciones
   - Eliminaciones

### Información del Log

Cada registro muestra:
- **Usuario:** Quién realizó la acción
- **Acción:** Tipo (LOGIN, CREATE, UPDATE, DELETE)
- **Tabla Afectada:** usuarios, cursos, inscripciones...
- **Registro ID:** ID del dato modificado
- **Cambios:** Datos anteriores y nuevos (en formato JSON)
- **IP:** Dirección desde donde se realizó
- **Fecha/Hora:** Timestamp exacto

### Filtrar Logs

- **Por Acción:** Solo login, solo modificaciones...
- **Por Tabla:** Solo usuarios, solo cursos...
- **Por Fecha:** Rango específico
- **Por Usuario:** Acciones de un usuario específico

**Uso:** La auditoría sirve para investigar problemas, verificar cambios sospechosos o cumplir requisitos de control.

---

## Respaldo de Base de Datos

### Crear Backup

1. Menú lateral → **"💾 Backup"**
2. Haz clic en **"Descargar Respaldo"**
3. Se descargará un archivo `.sql` con toda la base de datos
4. Guarda este archivo en un lugar seguro

### Frecuencia Recomendada

| Período | Acción |
|---------|--------|
| **Diario** | Backup automático (configurar en servidor) |
| **Semanal** | Backup manual antes de cambios importantes |
| **Antes de actualizaciones** | Siempre crear respaldo |

### Restaurar Backup (Emergencia)

⚠️ **Solo en caso de pérdida de datos:**

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Selecciona la base de datos `uptec_cursos`
3. Ve a la pestaña **"Importar"**
4. Selecciona el archivo `.sql` de respaldo
5. Haz clic en **"Continuar"**

---

## Consejos y Buenas Prácticas

### Seguridad

1. **Cambia tu contraseña** cada 3 meses
2. **No compartas** tu cuenta de administrador
3. **Verifica logs** semanalmente en busca de accesos sospechosos
4. **Crea backups** antes de hacer cambios masivos

### Gestión de Usuarios

1. **Verifica cédulas** antes de crear usuarios (evita duplicados)
2. **Usa correos institucionales** (@uptec.edu.ve) para staff
3. **Desactiva, no elimines** usuarios (mantiene historial)
4. **Revisa inactivos** periódicamente

### Gestión de Cursos

1. **Planifica con anticipación:** Crea cursos al menos 2 semanas antes
2. **Asigna facilitadores** temprano
3. **Completa el plan de evaluación** antes de iniciar el curso
4. **Comunica cambios** a los involucrados

### Soporte a Usuarios

Cuando un usuario reporta problemas:

1. **Verifica su estado** (¿está activo?)
2. **Revisa el log de auditoría** (¿hubo cambios recientes?)
3. **Intenta replicar** el problema
4. **Contacta** al desarrollador si es un bug del sistema

---

## Solución de Problemas Comunes

### "No puedo iniciar sesión"

1. Verifica que estás usando el correo correcto
2. Asegúrate de que el bloqueo de mayúsculas (Caps Lock) esté apagado
3. Si olvidaste la contraseña, contacta a otro administrador o desarrollador

### "No se crea el usuario"

- Verifica que la cédula no esté duplicada
- Verifica que el correo no esté en uso
- Revisa que todos los campos obligatorios estén completos

### "No puedo asignar facilitador"

- Verifica que el facilitador esté activo
- Verifica que el curso no esté cancelado o finalizado

### "Las notas no se calculan"

- Verifica que el plan de evaluación sume exactamente 100%
- Asegúrate de que todas las evaluaciones tengan notas registradas

---

## Contacto y Soporte

Para reportar errores del sistema o solicitar nuevas funcionalidades:

1. Documenta el problema (capturas de pantalla ayudan)
2. Revisa si aparece en los logs de auditoría
3. Contacta al equipo de desarrollo de UPTEC

---

**Fin del Manual del Administrador**

*Sistema UPTEC Cursos v2.0 - Última actualización: 2026*
