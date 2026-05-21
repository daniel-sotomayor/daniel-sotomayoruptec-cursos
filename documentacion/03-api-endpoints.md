# API Endpoints

## UPTEC Cursos v2.0 - Documentación de API RESTful

---

## Información General

| Propiedad | Valor |
|-----------|-------|
| **Base URL** | `/uptec-cursos/backend/api/api.php` |
| **Formato** | JSON |
| **Autenticación** | Sesiones PHP + CSRF Tokens |
| **Charset** | UTF-8 |

### Headers Requeridos

```
Content-Type: application/json
X-CSRF-Token: {token_csrf}
```

### CORS Headers

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token
```

---

## Respuestas

### Formato de Respuesta Exitosa

```json
{
  "success": true,
  "data": { ... },
  "message": "Opcional"
}
```

### Formato de Respuesta de Error

```json
{
  "success": false,
  "error": "Descripción del error",
  "errors": { "campo": "error específico" }
}
```

### Códigos de Estado HTTP

| Código | Significado |
|--------|-------------|
| 200 | Éxito |
| 201 | Creado |
| 204 | Sin contenido |
| 400 | Petición inválida |
| 401 | No autenticado |
| 403 | Prohibido (sin permisos) |
| 404 | No encontrado |
| 409 | Conflicto (duplicado) |
| 422 | Error de validación |
| 500 | Error del servidor |

---

## Endpoints de Autenticación

### POST /api.php?endpoint=login

Autentica un usuario y crea sesión.

**Body:**
```json
{
  "email": "admin@uptec.edu.ve",
  "password": "admin123"
}
```

**Respuesta Exitosa:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "cedula": "V12345678",
    "nombre": "Administrador",
    "apellidos": "Sistema UPTEC",
    "correo": "admin@uptec.edu.ve",
    "telefono": "0412-1234567",
    "rol": "Administrador"
  },
  "token": "a1b2c3d4e5f6...",
  "redirect": "/uptec-cursos/frontend/views/administrador/dashboard.html"
}
```

**Roles y Redirecciones:**
| Rol | Redirección |
|-----|-------------|
| Administrador | `/views/administrador/dashboard.html` |
| Analista | `/views/analista/dashboard.html` |
| Facilitador | `/views/facilitador/dashboard.html` |
| Participante | `/views/participante/dashboard.html` |

---

### POST /api.php?endpoint=logout

Cierra la sesión del usuario actual.

**Middleware:** `requireAuth()`

**Respuesta:**
```json
{
  "success": true,
  "message": "Sesion cerrada exitosamente"
}
```

---

### GET /api.php?endpoint=me

Obtiene información del usuario autenticado.

**Middleware:** `requireAuth()`

**Respuesta:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "cedula": "V12345678",
    "nombre": "Administrador",
    "apellidos": "Sistema UPTEC",
    "correo": "admin@uptec.edu.ve",
    "telefono": "0412-1234567",
    "rol": "Administrador"
  }
}
```

---

### POST /api.php?endpoint=register

Registro público de participantes y facilitadores.

**Permisos:** Público (solo roles Participante y Facilitador)

**Body (Participante):**
```json
{
  "cedula": "V55555555",
  "nombre": "Pedro",
  "apellidos": "Hernandez Castro",
  "correo": "phernandez@uptec.edu.ve",
  "telefono": "04125555555",
  "password": "contraseña123",
  "password_confirm": "contraseña123",
  "rol": "Participante",
  "area": "Informatica"
}
```

**Body (Facilitador):**
```json
{
  "cedula": "V99999999",
  "nombre": "Jose",
  "apellidos": "Jimenez Aguilar",
  "correo": "jjimenez@uptec.edu.ve",
  "telefono": "04129999999",
  "password": "contraseña123",
  "password_confirm": "contraseña123",
  "rol": "Facilitador"
}
```

**Áreas válidas:** Administracion, Informatica, Mecanica, Electrica, Mantenimiento, Transporte Ferroviario

---

### PUT /api.php?endpoint=password

Cambio de contraseña (usuario autenticado).

**Middleware:** `requireAuth()`

**Body:**
```json
{
  "current_password": "actual123",
  "new_password": "nueva123"
}
```

---

### GET /api.php?endpoint=csrf

Obtiene un token CSRF nuevo.

**Permisos:** Público

**Respuesta:**
```json
{
  "success": true,
  "token": "a1b2c3d4e5f6..."
}
```

---

## Endpoints de Usuarios

### GET /api.php?endpoint=usuarios

Lista de usuarios con filtros.

**Middleware:** `requireAnalystOrAbove()`

**Query Parameters:**
| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `rol` | Filtrar por rol | `?rol=Facilitador` |
| `activo` | Estado (0/1) | `?activo=1` |
| `buscar` | Búsqueda general | `?buscar=pedro` |

**Respuesta:**
```json
{
  "success": true,
  "usuarios": [
    {
      "id": 1,
      "cedula": "V12345678",
      "nombre": "Administrador",
      "apellidos": "Sistema UPTEC",
      "correo": "admin@uptec.edu.ve",
      "telefono": "0412-1234567",
      "rol": "Administrador",
      "activo": 1,
      "ultimo_acceso": "2026-01-15 10:30:00",
      "creado_en": "2026-01-01 00:00:00"
    }
  ],
  "total": 1
}
```

**Nota:** Analista solo ve Facilitadores y Participantes.

---

### GET /api.php?endpoint=usuario&id={id}

Detalle de un usuario específico.

**Middleware:** `requireAnalystOrAbove()`

---

### POST /api.php?endpoint=usuario

Crear nuevo usuario (Admin solo).

**Middleware:** `requireAdmin()`

**Body:**
```json
{
  "cedula": "V12345678",
  "nombre": "Juan",
  "apellidos": "Perez",
  "correo": "jperez@uptec.edu.ve",
  "telefono": "04121111111",
  "rol": "Analista"
}
```

**Respuesta:**
```json
{
  "success": true,
  "message": "Usuario creado exitosamente",
  "user_id": 10,
  "temp_password": "a3f7b2d9"
}
```

---

### PUT /api.php?endpoint=usuario&id={id}

Actualizar usuario.

**Middleware:** `requireAdmin()`

**Body (campos opcionales):**
```json
{
  "nombre": "Nuevo Nombre",
  "apellidos": "Nuevos Apellidos",
  "telefono": "04129998888",
  "activo": 1,
  "rol": "Facilitador"
}
```

---

### DELETE /api.php?endpoint=usuario&id={id}

Eliminar/desactivar usuario (soft delete).

**Middleware:** `requireAdmin()`

**Restricción:** No se puede eliminar el administrador principal (id=1).

---

### GET /api.php?endpoint=facilitadores

Lista simplificada de facilitadores activos.

**Middleware:** `requireAnalystOrAbove()`

**Respuesta:**
```json
{
  "success": true,
  "facilitadores": [
    {
      "id": 3,
      "cedula": "V11111111",
      "nombre_completo": "Juan Perez Garcia",
      "correo": "jperez@uptec.edu.ve"
    }
  ]
}
```

---

### GET /api.php?endpoint=analistas

Lista simplificada de analistas activos.

**Middleware:** `requireAdmin()`

---

## Endpoints de Cursos

### GET /api.php?endpoint=cursos

Lista de cursos con filtros.

**Permisos:** Público (no requiere auth)

**Query Parameters:**
| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `estado` | Estado del curso | `?estado=En%20Curso` |
| `facilitador_id` | Por facilitador | `?facilitador_id=3` |
| `area` | Área de conocimiento | `?area=Informatica` |
| `buscar` | Búsqueda general | `?buscar=php` |

**Respuesta:**
```json
{
  "success": true,
  "cursos": [
    {
      "id": 1,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP",
      "descripcion": "Curso completo de desarrollo web...",
      "duracion_horas": 40,
      "fecha_inicio": "2026-01-15",
      "fecha_fin": "2026-03-15",
      "cupo_maximo": 25,
      "area": "Informatica",
      "nivel": "Intermedio",
      "estado": "En Curso",
      "facilitador_id": 3,
      "nombre_facilitador": "Carlos Gonzalez Martinez",
      "correo_facilitador": "cgonzalez@uptec.edu.ve",
      "total_inscritos": 12
    }
  ],
  "total": 1
}
```

---

### GET /api.php?endpoint=curso&id={id}

Detalle completo de un curso.

**Permisos:** Público

**Respuesta:**
```json
{
  "success": true,
  "curso": { ... },
  "evaluaciones": [
    {
      "id": 1,
      "nombre": "Parcial 1",
      "tipo": "Parcial",
      "peso": "20.00",
      "fecha_evaluacion": "2026-02-01",
      "orden": 1
    }
  ],
  "horarios": [
    {
      "id": 1,
      "dia_semana": "Lunes",
      "hora_inicio": "08:00:00",
      "hora_fin": "12:00:00",
      "aula": "Laboratorio 1"
    }
  ],
  "estudiantes": [
    {
      "inscripcion_id": 2,
      "estado": "En Progreso",
      "nota_final": null,
      "estudiante_id": 5,
      "cedula": "V55555555",
      "nombre_estudiante": "Pedro Hernandez Castro",
      "correo": "phernandez@uptec.edu.ve"
    }
  ]
}
```

---

### POST /api.php?endpoint=curso

Crear nuevo curso.

**Middleware:** `requireAnalystOrAbove()`

**Body:**
```json
{
  "codigo": "NEW-001",
  "nombre": "Nuevo Curso",
  "descripcion": "Descripción del curso",
  "duracion_horas": 30,
  "fecha_inicio": "2026-02-01",
  "fecha_fin": "2026-04-01",
  "cupo_maximo": 25,
  "area": "Informatica",
  "nivel": "Basico",
  "estado": "Planificado",
  "facilitador_id": 3
}
```

---

### PUT /api.php?endpoint=curso&id={id}

Actualizar curso.

**Middleware:** `requireAnalystOrAbove()`

---

### DELETE /api.php?endpoint=curso&id={id}

Eliminar curso (soft delete).

**Middleware:** `requireAdmin()`

---

### GET /api.php?endpoint=areas

Lista de áreas de conocimiento únicas.

**Permisos:** Público

**Respuesta:**
```json
{
  "success": true,
  "areas": ["Administracion", "Informatica", "Mecanica", "Electrica"]
}
```

---

### POST /api.php?endpoint=evaluacion

Crear evaluación para un curso.

**Middleware:** `requireAnalystOrAbove()`

**Body:**
```json
{
  "curso_id": 1,
  "nombre": "Parcial 1",
  "descripcion": "Primera evaluacion parcial",
  "tipo": "Parcial",
  "peso": 25.00,
  "fecha_evaluacion": "2026-02-01",
  "orden": 1
}
```

**Tipos válidos:** Parcial, Final, Proyecto, Asistencia, Trabajo, Otro

---

### GET /api.php?endpoint=mis-cursos

Cursos del facilitador o participante logueado.

**Middleware:** `requireAuth()`

**Respuesta para Facilitador:**
```json
{
  "success": true,
  "cursos": [
    {
      "id": 1,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP",
      "total_inscritos": 12
    }
  ]
}
```

**Respuesta para Participante:**
```json
{
  "success": true,
  "cursos": [
    {
      "id": 1,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP",
      "estado_inscripcion": "En Progreso",
      "nota_final": null
    }
  ]
}
```

---

## Endpoints de Inscripciones

### GET /api.php?endpoint=inscripciones

Lista de inscripciones con filtros.

**Middleware:** `requireTeacherOrAbove()`

**Query Parameters:**
| Parámetro | Descripción |
|-----------|-------------|
| `curso_id` | Filtrar por curso |
| `estado` | Estado de inscripción |
| `buscar` | Búsqueda por cédula/nombre |

**Nota:** Facilitador solo ve inscripciones de sus cursos.

---

### POST /api.php?endpoint=inscripcion

Inscribir participante en un curso.

**Middleware:** `requireAuth()`

**Body (Participante se inscribe a sí mismo):**
```json
{
  "curso_id": 1
}
```

**Body (Admin/Analista inscribe a otro):**
```json
{
  "curso_id": 1,
  "usuario_id": 5
}
```

**Validaciones:**
- Verifica que el curso esté activo y no cancelado/finalizado
- Verifica cupos disponibles
- Evita inscripciones duplicadas

---

### PUT /api.php?endpoint=inscripcion&id={id}

Actualizar estado de inscripción.

**Middleware:** `requireTeacherOrAbove()`

**Body:**
```json
{
  "estado": "Completado",
  "observaciones": "Excelente desempeño"
}
```

**Estados válidos:** Inscrito, En Progreso, Completado, Abandonado, Reprobado

---

### DELETE /api.php?endpoint=inscripcion&id={id}

Cancelar inscripción (marcar como Abandonado).

**Middleware:** `requireAuth()`

**Nota:** Participante solo puede cancelar sus propias inscripciones.

---

### GET /api.php?endpoint=cursos-disponibles

Cursos disponibles para inscripción (para participante logueado).

**Middleware:** `requireRole('Participante')`

**Filtros automáticos:**
- Solo cursos del área/carrera del participante
- Excluye cursos en los que ya está inscrito
- Solo cursos Planificados o En Curso

---

## Endpoints de Calificaciones

### GET /api.php?endpoint=calificaciones

Listar calificaciones.

**Middleware:** `requireAuth()`

**Query Parameters:**
| Parámetro | Descripción |
|-----------|-------------|
| `inscripcion_id` | Filtrar por inscripción |
| `curso_id` | Filtrar por curso |

**Notas:**
- Participante solo ve sus propias calificaciones
- Facilitador solo ve calificaciones de sus cursos
- Analista/Admin ven todas

---

### GET /api.php?endpoint=mis-notas

Notas completas del participante logueado.

**Middleware:** `requireRole('Participante')`

**Respuesta:**
```json
{
  "success": true,
  "inscripciones": [
    {
      "inscripcion_id": 2,
      "estado": "En Progreso",
      "nota_final": null,
      "curso_id": 1,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP",
      "duracion_horas": 40,
      "area": "Informatica",
      "nombre_facilitador": "Carlos Gonzalez Martinez",
      "calificaciones": [
        {
          "tipo_evaluacion": "Parcial",
          "descripcion": "Primera evaluacion parcial",
          "nota": "16.00",
          "peso": "20.00",
          "fecha_evaluacion": "2026-02-01"
        }
      ],
      "plan_evaluacion": [
        {
          "id": 1,
          "nombre": "Parcial 1",
          "tipo": "Parcial",
          "peso": "20.00"
        }
      ]
    }
  ]
}
```

---

### POST /api.php?endpoint=calificacion

Registrar calificación.

**Middleware:** `requireTeacherOrAbove()`

**Body:**
```json
{
  "inscripcion_id": 2,
  "evaluacion_id": 1,
  "tipo_evaluacion": "Parcial",
  "descripcion": "Primera evaluacion",
  "nota": 16.5,
  "peso": 20.0,
  "fecha_evaluacion": "2026-02-01",
  "observaciones": "Buen trabajo"
}
```

**Validaciones:**
- Nota entre 0 y 20
- Peso entre 0 y 100
- Facilitador debe ser el asignado al curso

**Nota:** El trigger `actualizar_nota_final_inscripcion` actualiza automáticamente la nota final y el estado de la inscripción.

---

### PUT /api.php?endpoint=calificacion&id={id}

Actualizar calificación.

**Middleware:** `requireTeacherOrAbove()`

---

### GET /api.php?endpoint=estudiantes-curso&id={curso_id}

Estudiantes de un curso específico con calificaciones.

**Middleware:** `requireTeacherOrAbove()`

**Respuesta:**
```json
{
  "success": true,
  "estudiantes": [
    {
      "inscripcion_id": 2,
      "estado": "En Progreso",
      "nota_final": null,
      "usuario_id": 5,
      "cedula": "V55555555",
      "nombre_estudiante": "Pedro Hernandez Castro",
      "correo": "phernandez@uptec.edu.ve",
      "total_calificaciones": 1,
      "calificaciones": [
        {
          "tipo_evaluacion": "Parcial",
          "nota": "16.00",
          "peso": "20.00"
        }
      ]
    }
  ],
  "plan_evaluacion": [...],
  "total_estudiantes": 1
}
```

---

## Endpoints de Reportes

### GET /api.php?endpoint=resumen

Resumen del dashboard según el rol del usuario.

**Middleware:** `requireAuth()`

**Respuesta para Administrador:**
```json
{
  "success": true,
  "usuario": { ... },
  "total_estudiantes": 6,
  "total_facilitadores": 4,
  "total_analistas": 1,
  "total_cursos": 10,
  "total_inscripciones": 12,
  "cursos_por_estado": [...],
  "cursos_recientes": [...],
  "usuarios_recientes": [...],
  "actividad_reciente": [...],
  "estudiantes_por_area": [...],
  "cursos_con_estudiantes_por_area": [...],
  "top_cursos": [...]
}
```

**Respuesta para Analista:**
```json
{
  "success": true,
  "usuario": { ... },
  "total_cursos": 10,
  "cursos_planificados": 5,
  "total_facilitadores": 4,
  "total_inscripciones": 12,
  "cursos_por_area": [...],
  "cursos_sin_facilitador": [...]
}
```

**Respuesta para Facilitador:**
```json
{
  "success": true,
  "usuario": { ... },
  "mis_cursos": 3,
  "mis_estudiantes": 15,
  "cursos_activos": [...]
}
```

**Respuesta para Participante:**
```json
{
  "success": true,
  "usuario": { ... },
  "mis_cursos": 2,
  "cursos_completados": 1,
  "promedio_general": 17.5,
  "inscripciones": [...]
}
```

---

### GET /api.php?endpoint=estadisticas

Estadísticas globales del sistema.

**Middleware:** `requireAdmin()`

**Respuesta:**
```json
{
  "success": true,
  "promedio_general": 16.75,
  "tasa_aprobacion": 85.5,
  "cursos_por_area": [...],
  "inscripciones_por_mes": [...],
  "top_facilitadores": [...]
}
```

---

### GET /api.php?endpoint=auditoria

Logs de auditoría del sistema.

**Middleware:** `requireAdmin()`

**Query Parameters:**
| Parámetro | Descripción |
|-----------|-------------|
| `accion` | Filtrar por tipo de acción |
| `tabla` | Filtrar por tabla afectada |
| `fecha_desde` | Fecha inicial (YYYY-MM-DD) |
| `fecha_hasta` | Fecha final (YYYY-MM-DD) |

**Respuesta:**
```json
{
  "success": true,
  "logs": [
    {
      "id": 1,
      "usuario_nombre": "Administrador Sistema UPTEC",
      "accion": "LOGIN",
      "tabla_afectada": "usuarios",
      "registro_id": 1,
      "datos_anteriores": null,
      "datos_nuevos": null,
      "ip_address": "127.0.0.1",
      "fecha_hora": "2026-01-15 10:30:00"
    }
  ],
  "total": 100
}
```

**Límite:** 500 registros más recientes.

---

### GET /api.php?endpoint=certificado&id={inscripcion_id}

Generar datos de certificado de aprobación.

**Middleware:** `requireAuth()`

**Requisito:** La inscripción debe tener estado "Completado".

---

## Endpoints de Backup

### GET /api.php?endpoint=backup

Descargar respaldo de base de datos en formato SQL.

**Middleware:** `requireAdmin()`

**Respuesta:** Archivo `.sql` descargable.

---

## Endpoints de Verificación (Nuevos)

### PUT /api.php?endpoint=curso-verificar&id=X

Verifica un curso cambiando su estado a "Verificado".

**Middleware:** `requireAnalystOrAbove()`

**Parámetros:**
- `id` (query): ID del curso

**Respuesta:**
```json
{
  "success": true,
  "message": "Curso verificado exitosamente"
}
```

**Campos actualizados:**
- `estado`: 'Verificado'
- `verificado_por`: ID del usuario que verificó
- `fecha_verificacion`: Fecha y hora actual

---

## Endpoints de Descarga PDF (Nuevos)

### GET /api.php?endpoint=mis-notas-pdf

Descarga las notas del participante logueado en formato PDF.

**Middleware:** `requireAuth()`

**Respuesta:** Archivo PDF descargable con:
- Datos del estudiante (nombre, cédula, correo, área)
- Lista de cursos inscritos
- Estado de inscripción
- Nota final
- Facilitador de cada curso

---

### GET /api.php?endpoint=reportes-pdf

Descarga reportes estadísticos en PDF según el rol del usuario.

**Middleware:** `requireAnalystOrAbove()`

**Contenido según rol:**

**Administrador:**
- Total de estudiantes, facilitadores, analistas
- Total de cursos y cursos activos
- Total de inscripciones y completadas
- Top 5 cursos con más estudiantes

**Analista:**
- Total de cursos por estado
- Total de facilitadores
- Cursos sin facilitador asignado

**Facilitador:**
- Mis cursos (total y activos)
- Total de estudiantes
- Listado de cursos con inscritos

**Respuesta:** Archivo PDF descargable

---

## Middleware de Autenticación

| Middleware | Descripción | Roles Permitidos |
|------------|-------------|------------------|
| `requireAuth()` | Usuario autenticado | Todos |
| `requireAdmin()` | Solo administradores | Administrador |
| `requireAnalystOrAbove()` | Analista o superior | Analista, Administrador |
| `requireTeacherOrAbove()` | Facilitador o superior | Facilitador, Analista, Administrador |
| `requireRole('Participante')` | Solo participantes | Participante |
| `validateCsrf()` | Validar token CSRF | Todos (POST/PUT/DELETE) |

---

## Jerarquía de Roles

```
Administrador (4) ─────────┐
                           │
Analista (3) ──────────────┤──► Puede acceder a roles inferiores
                           │
Facilitador (2) ───────────┤
                           │
Participante (1) ──────────┘
```

- `isAdmin()`: Solo Administrador
- `isAnalystOrAbove()`: Analista y Administrador
- `isTeacherOrAbove()`: Facilitador, Analista y Administrador
- `hasRole('Participante')`: Cualquier rol puede ver participantes
