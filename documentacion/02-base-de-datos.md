# Base de Datos

## UPTEC Cursos v2.0 - Documentación de Base de Datos

---

## Información General

| Propiedad          | Valor                       |
|--------------------|-----------------------------|
| **Sistema Gestor** | MySQL 5.7+                  |
| **Nombre BD**      | `uptec_cursos`              |
| **Charset**        | `utf8mb4`                   |
| **Collation**      | `utf8mb4_unicode_ci`        |
| **Motor**          | InnoDB (para foreign keys)  |

---

## Diagrama Entidad-Relación

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│    usuarios     │       │     cursos      │       │  evaluaciones   │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ PK id           │──┐    │ PK id           │◄──────┤ PK id           │
│ cedula (UQ)     │  │    │ codigo (UQ)     │       │ FK curso_id     │
│ nombre          │  │    │ nombre          │       │ nombre          │
│ apellidos       │  │    │ descripcion     │       │ tipo            │
│ correo (UQ)     │  │    │ duracion_horas  │       │ peso            │
│ telefono        │  │    │ fecha_inicio    │       │ fecha_evaluacion│
│ password        │  │    │ fecha_fin       │       │ orden           │
│ rol             │  │    │ cupo_maximo     │       └─────────────────┘
│ area            │  │    │ area            │
│ activo          │  │    │ nivel           │
│ ultimo_acceso   │  │    │ estado          │
└─────────────────┘  │    │ FK facilitador_id│◄──────┐
                     │    │ FK analista_id  │◄───────┼──┐
                     │    │ activo          │        │  │
                     │    └─────────────────┘        │  │
                     │                               │  │
                     │    ┌──────────────────┐       │  │
                     │    │   inscripciones  │       │  │
                     │    ├──────────────────┤       │  │
                     │    │ PK id            │       │  │
                     │    │ FK usuario_id    │───────┘  │
                     │    │ FK curso_id      │◄─────────┘
                     │    │ fecha_inscripcion|         │
                     │    │ estado           |         │
                     │    │ nota_final       |         │
                     │    │ observaciones    |         │
                     │    └──────────────────┘         │
                     │              │                  │
                     │              ▼                  │
                     │    ┌─────────────────┐          │
                     │    │  calificaciones │          │
                     │    ├─────────────────┤          │
                     │    │ PK id           │          │
                     └───►│ FK inscripcion_id          │
                          │ FK evaluacion_id │◄────────┘
                          │ tipo_evaluacion │
                          │ nota            │
                          │ peso            │
                          │ fecha_evaluacion│
                          │ observaciones   │
                          └─────────────────┘

┌─────────────────┐       ┌─────────────────┐
│    horarios     │       │   asistencias   │
├─────────────────┤       ├─────────────────┤
│ PK id           │       │ PK id           │
│ FK curso_id     │       │ FK inscripcion_id│
│ dia_semana      │       │ fecha           │
│ hora_inicio     │       │ estado          │
│ hora_fin        │       │ observacion     │
│ aula            │       └─────────────────┘
└─────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                        logs_actividad                               │
├─────────────────────────────────────────────────────────────────────┤
│ PK id (BIGINT)                                                      │
│ FK usuario_id (nullable)                                            │
│ usuario_nombre                                                      │
│ accion (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, etc.)                │
│ tabla_afectada                                                      │
│ registro_id                                                         │
│ datos_anteriores (JSON)                                             │
│ datos_nuevos (JSON)                                                 │
│ ip_address                                                          │
│ user_agent                                                          │
│ fecha_hora                                                          │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Tablas Detalladas

### 1. usuarios

Almacena todos los usuarios del sistema con sus roles.

| Campo            | Tipo                        | Descripción                                     | Índice        |
|------------------|-----------------------------|-------------------------------------------------|---------------|
| `id`             | INT UNSIGNED AUTO_INCREMENT | ID único                                        | PK            |
| `cedula`         | VARCHAR(20)                 | Cédula venezolana (V12345678)                   | UNIQUE, INDEX |
| `nombre`         | VARCHAR(100)                | Nombre del usuario                              | -             |
| `apellidos`      | VARCHAR(100)                | Apellidos del usuario                           | -             |
| `correo`         | VARCHAR(150)                | Correo institucional @uptec.edu.ve              | UNIQUE, INDEX |
| `telefono`       | VARCHAR(20)                 | Teléfono de contacto (0424...)                  | -             |
| `password`       | VARCHAR(255)                | Hash bcrypt de contraseña                       | -             |
| `rol`            | ENUM                        | Participante/Facilitador/Analista/Administrador | INDEX         |
| `area`           | VARCHAR(100)                | Carrera/área (solo participantes)               | INDEX         |
| `activo`         | TINYINT(1)                  | Estado de cuenta (1=activo)                     | INDEX         |
| `creado_en`      | TIMESTAMP                   | Fecha de creación                               | -             |
| `actualizado_en` | TIMESTAMP                   | Última actualización                            | -             |
| `ultimo_acceso`  | DATETIME                    | Último login exitoso                            | -             |
--------------------------------------------------------------------------------------------------------------------
**Notas:**
- Participantes y facilitadores se registran públicamente
- Administradores y analistas son creados por el admin
- El campo `area` solo aplica para participantes

---

### 2. cursos

Catálogo de cursos disponibles.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `codigo` | VARCHAR(20) | Código único del curso (PROG-001) | UNIQUE, INDEX |
| `nombre` | VARCHAR(200) | Nombre del curso | - |
| `descripcion` | TEXT | Descripción detallada | - |
| `duracion_horas` | INT UNSIGNED | Duración en horas académicas | - |
| `fecha_inicio` | DATE | Fecha de inicio | - |
| `fecha_fin` | DATE | Fecha de finalización | - |
| `cupo_maximo` | INT UNSIGNED | Máximo de estudiantes (default 30) | - |
| `area` | VARCHAR(100) | Área de conocimiento | INDEX |
| `nivel` | ENUM | Basico/Intermedio/Avanzado | INDEX |
| `estado` | ENUM | Planificado/En Curso/Finalizado/Cancelado | INDEX |
| `facilitador_id` | INT UNSIGNED | FK a usuarios (rol Facilitador) | FK, INDEX |
| `analista_id` | INT UNSIGNED | FK a usuarios (rol Analista) | FK, INDEX |
| `activo` | TINYINT(1) | 1=visible, 0=eliminado | - |
| `creado_en` | TIMESTAMP | Fecha de creación | - |
| `actualizado_en` | TIMESTAMP | Última actualización | - |

**Foreign Keys:**
- `facilitador_id` → `usuarios(id)` ON DELETE SET NULL
- `analista_id` → `usuarios(id)` ON DELETE SET NULL

---

### 3. evaluaciones

Plan de evaluación de cada curso.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `curso_id` | INT UNSIGNED | Curso al que pertenece | FK, INDEX |
| `nombre` | VARCHAR(100) | Nombre (ej: "Parcial 1") | - |
| `descripcion` | TEXT | Descripción de la evaluación | - |
| `tipo` | ENUM | Parcial/Final/Proyecto/Asistencia/Trabajo/Otro | INDEX |
| `peso` | DECIMAL(4,2) | Peso en nota final (0-100%) | - |
| `fecha_evaluacion` | DATE | Fecha programada | - |
| `orden` | INT UNSIGNED | Orden en el plan (1, 2, 3...) | - |
| `activo` | TINYINT(1) | Estado del registro | - |
| `creado_en` | TIMESTAMP | Fecha de creación | - |

**Foreign Key:**
- `curso_id` → `cursos(id)` ON DELETE CASCADE

---

### 4. inscripciones

Gestiona las inscripciones de participantes a cursos.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `usuario_id` | INT UNSIGNED | Participante inscrito | FK, INDEX |
| `curso_id` | INT UNSIGNED | Curso al que está inscrito | FK, INDEX |
| `fecha_inscripcion` | TIMESTAMP | Fecha de inscripción | - |
| `estado` | ENUM | Inscrito/En Progreso/Completado/Abandonado/Reprobado | INDEX |
| `nota_final` | DECIMAL(4,2) | Calificación final calculada | - |
| `fecha_completado` | DATETIME | Fecha de finalización | - |
| `observaciones` | TEXT | Notas del facilitador | - |

**Constraints:**
- UNIQUE KEY `unique_inscripcion` (`usuario_id`, `curso_id`)
- FK `usuario_id` → `usuarios(id)` ON DELETE CASCADE
- FK `curso_id` → `cursos(id)` ON DELETE CASCADE

---

### 5. calificaciones

Registro detallado de calificaciones por evaluación.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `inscripcion_id` | INT UNSIGNED | Inscripción relacionada | FK, INDEX |
| `evaluacion_id` | INT UNSIGNED | Evaluación del plan (opcional) | FK, INDEX |
| `tipo_evaluacion` | VARCHAR(50) | Tipo: Parcial, Final, Proyecto... | INDEX |
| `descripcion` | VARCHAR(200) | Descripción de la evaluación | - |
| `nota` | DECIMAL(4,2) | Calificación (0.00 - 20.00) | - |
| `peso` | DECIMAL(4,2) | Peso de la evaluación (%) | - |
| `fecha_evaluacion` | DATE | Fecha de la evaluación | - |
| `fecha_registro` | TIMESTAMP | Fecha de registro en sistema | - |
| `observaciones` | TEXT | Notas adicionales | - |

**Foreign Keys:**
- `inscripcion_id` → `inscripciones(id)` ON DELETE CASCADE
- `evaluacion_id` → `evaluaciones(id)` ON DELETE SET NULL

---

### 6. horarios

Almacena los horarios de cada curso.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `curso_id` | INT UNSIGNED | Curso relacionado | FK, INDEX |
| `dia_semana` | ENUM | Lunes a Domingo | INDEX |
| `hora_inicio` | TIME | Hora de inicio | - |
| `hora_fin` | TIME | Hora de finalización | - |
| `aula` | VARCHAR(50) | Salón o aula asignada | - |

**Foreign Key:**
- `curso_id` → `cursos(id)` ON DELETE CASCADE

---

### 7. asistencias

Control de asistencia a sesiones de curso.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | INT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `inscripcion_id` | INT UNSIGNED | Inscripción relacionada | FK, INDEX |
| `fecha` | DATE | Fecha de la sesión | INDEX |
| `estado` | ENUM | Presente/Ausente/Tarde/Justificado | - |
| `observacion` | VARCHAR(255) | Nota sobre la asistencia | - |

**Constraints:**
- UNIQUE KEY `unique_asistencia` (`inscripcion_id`, `fecha`)
- FK `inscripcion_id` → `inscripciones(id)` ON DELETE CASCADE

---

### 8. logs_actividad

Auditoría completa de actividades del sistema.

| Campo | Tipo | Descripción | Índice |
|-------|------|-------------|--------|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | ID único | PK |
| `usuario_id` | INT UNSIGNED | Usuario que realizó la acción | INDEX |
| `usuario_nombre` | VARCHAR(200) | Nombre completo del usuario | - |
| `accion` | VARCHAR(100) | Tipo: CREATE, UPDATE, DELETE, LOGIN... | INDEX |
| `tabla_afectada` | VARCHAR(50) | Tabla modificada | INDEX |
| `registro_id` | INT UNSIGNED | ID del registro afectado | - |
| `datos_anteriores` | JSON | Datos antes de la modificación | - |
| `datos_nuevos` | JSON | Datos después de la modificación | - |
| `ip_address` | VARCHAR(45) | Dirección IP del usuario | INDEX |
| `user_agent` | VARCHAR(255) | Navegador/Dispositivo | - |
| `fecha_hora` | TIMESTAMP | Fecha y hora de la acción | INDEX |

---

## Vistas (Views)

### vista_cursos_facilitadores

Vista de cursos con información del facilitador y analista.

```sql
SELECT
    c.id, c.codigo, c.nombre, c.descripcion, c.duracion_horas,
    c.fecha_inicio, c.fecha_fin, c.cupo_maximo, c.area, c.nivel, c.estado,
    CONCAT(f.nombre, ' ', f.apellidos) AS nombre_facilitador,
    f.correo AS correo_facilitador,
    CONCAT(a.nombre, ' ', a.apellidos) AS nombre_analista,
    (SELECT COUNT(*) FROM inscripciones i WHERE i.curso_id = c.id AND i.estado != 'Abandonado') AS total_inscritos,
    (SELECT COUNT(*) FROM inscripciones i WHERE i.curso_id = c.id AND i.estado = 'Completado') AS total_completados,
    (SELECT ROUND(AVG(nota_final), 2) FROM inscripciones i WHERE i.curso_id = c.id AND i.nota_final IS NOT NULL) AS promedio_general
FROM cursos c
LEFT JOIN usuarios f ON c.facilitador_id = f.id
LEFT JOIN usuarios a ON c.analista_id = a.id
WHERE c.activo = 1;
```

### vista_participantes_cursos

Vista de participantes con sus cursos inscritos.

```sql
SELECT
    u.id AS participante_id, u.cedula,
    CONCAT(u.nombre, ' ', u.apellidos) AS nombre_completo,
    u.correo, u.telefono,
    c.codigo AS codigo_curso, c.nombre AS nombre_curso,
    i.fecha_inscripcion, i.estado AS estado_inscripcion,
    i.nota_final, i.fecha_completado,
    CONCAT(f.nombre, ' ', f.apellidos) AS nombre_facilitador
FROM usuarios u
INNER JOIN inscripciones i ON u.id = i.usuario_id
INNER JOIN cursos c ON i.curso_id = c.id
LEFT JOIN usuarios f ON c.facilitador_id = f.id
WHERE u.rol = 'Participante';
```

---

## Triggers

### actualizar_nota_final_inscripcion

Actualiza automáticamente la nota final cuando se inserta una calificación.

```sql
DELIMITER //
CREATE TRIGGER actualizar_nota_final_inscripcion
AFTER INSERT ON calificaciones
FOR EACH ROW
BEGIN
    DECLARE promedio DECIMAL(4,2);
    DECLARE total_peso DECIMAL(6,2);

    SELECT SUM(nota * (peso/100)), SUM(peso/100) INTO promedio, total_peso
    FROM calificaciones
    WHERE inscripcion_id = NEW.inscripcion_id;

    IF total_peso > 0 THEN
        SET promedio = ROUND(promedio / total_peso, 2);
    END IF;

    UPDATE inscripciones
    SET nota_final = promedio,
        estado = CASE
            WHEN promedio >= 10 THEN 'Completado'
            WHEN promedio < 10 AND promedio > 0 THEN 'Reprobado'
            ELSE estado
        END,
        fecha_completado = CASE
            WHEN promedio >= 10 THEN NOW()
            ELSE fecha_completado
        END
    WHERE id = NEW.inscripcion_id;
END//
```

### actualizar_nota_final_update

Igual que el anterior, pero para actualizaciones (AFTER UPDATE).

---

## Datos Iniciales (Seed)

### Usuarios de Prueba

| Rol | Cédula | Nombre | Correo | Contraseña |
|-----|--------|--------|--------|------------|
| Administrador | V12345678 | Administrador Sistema UPTEC | admin@uptec.edu.ve | admin123 |
| Analista | V87654321 | Analista Académico | analista@uptec.edu.ve | analista123 |
| Facilitador | V11111111 | Juan Pérez García | jperez@uptec.edu.ve | facilitador123 |
| Facilitador | V22222222 | María López Rodríguez | mlopez@uptec.edu.ve | facilitador123 |
| Facilitador | V33333333 | Carlos González Martínez | cgonzalez@uptec.edu.ve | facilitador123 |
| Facilitador | V44444444 | Ana Ramírez Silva | aramirez@uptec.edu.ve | facilitador123 |

### Cursos de Ejemplo

| Código | Nombre | Área | Nivel | Estado |
|--------|--------|------|-------|--------|
| PROG-001 | Programación en PHP | Informática | Intermedio | En Curso |
| WEB-001 | Desarrollo Web Frontend | Informática | Básico | En Curso |
| BASE-001 | Bases de Datos MySQL | Informática | Intermedio | Planificado |
| RED-001 | Fundamentos de Redes | Informática | Básico | Planificado |
| ADM-001 | Gestión de Proyectos | Administración | Intermedio | Finalizado |
| MEC-001 | Mecánica Automotriz Básica | Mecánica | Básico | En Curso |
| ELEC-001 | Electricidad Industrial | Eléctrica | Intermedio | Planificado |
| MANT-001 | Mantenimiento Preventivo | Mantenimiento | Básico | En Curso |
| TF-001 | Sistemas de Transporte Ferroviario | Transporte Ferroviario | Intermedio | Planificado |
| OFI-001 | Ofimática Avanzada | Administración | Básico | Planificado |

---

## Consultas Comunes

### Estudiantes por Curso
```sql
SELECT 
    c.codigo, c.nombre,
    COUNT(i.id) AS total_inscritos,
    COUNT(CASE WHEN i.estado = 'Completado' THEN 1 END) AS completados
FROM cursos c
LEFT JOIN inscripciones i ON c.id = i.curso_id AND i.estado != 'Abandonado'
WHERE c.activo = 1
GROUP BY c.id;
```

### Promedio de Notas por Curso
```sql
SELECT 
    c.codigo, c.nombre,
    ROUND(AVG(i.nota_final), 2) AS promedio,
    COUNT(i.id) AS total_con_notas
FROM cursos c
LEFT JOIN inscripciones i ON c.id = i.curso_id AND i.nota_final IS NOT NULL
WHERE c.activo = 1
GROUP BY c.id;
```

### Actividad Reciente
```sql
SELECT 
    usuario_nombre, accion, tabla_afectada, fecha_hora
FROM logs_actividad
ORDER BY fecha_hora DESC
LIMIT 10;
```
