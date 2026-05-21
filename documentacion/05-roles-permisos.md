# Roles y Permisos

## UPTEC Cursos v2.0 - Sistema de Control de Acceso Basado en Roles (RBAC)

---

## Visión General

El sistema implementa un modelo **RBAC (Role-Based Access Control)** con jerarquía de 4 niveles. Cada rol superior hereda los permisos de los roles inferiores.

```
                    ┌──────────────────┐
                    │  ADMINISTRADOR   │ ← Control total del sistema
                    │     (Nivel 4)     │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │    ANALISTA      │ ← Gestión académica
                    │     (Nivel 3)     │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │   FACILITADOR    │ ← Gestión de cursos asignados
                    │     (Nivel 2)     │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │   PARTICIPANTE   │ ← Acceso a información personal
                    │     (Nivel 1)     │
                    └──────────────────┘
```

---

## Jerarquía de Roles

```php
private static array $roleHierarchy = [
    'Administrador' => 4,
    'Analista'      => 3,
    'Facilitador'   => 2,
    'Participante'  => 1
];
```

### Reglas de Herencia

- Un usuario puede acceder a funcionalidades de su nivel o inferior
- `hasRole('Analista')` permite acceso a Analista y Administrador
- `isAdmin()` solo permite acceso a Administrador
- `isAnalystOrAbove()` permite Analista y Administrador
- `isTeacherOrAbove()` permite Facilitador, Analista y Administrador

---

## ADMINISTRADOR (Nivel 4)

### Descripción
Control total del sistema. Gestiona usuarios, analistas, facilitadores, respaldos y auditoría.

### Permisos

| Módulo | Permisos |
|--------|----------|
| **Usuarios** | ✅ Crear, leer, actualizar, eliminar (todos los roles) |
| **Cursos** | ✅ Crear, leer, actualizar, eliminar |
| **Inscripciones** | ✅ Ver todas, modificar estado |
| **Calificaciones** | ✅ Ver todas |
| **Reportes** | ✅ Todos los reportes y estadísticas |
| **Auditoría** | ✅ Ver logs completos del sistema |
| **Backup** | ✅ Exportar base de datos |
| **Configuración** | ✅ Acceso a todas las configuraciones |

### Vistas Disponibles

- `/views/administrador/dashboard.html` - Panel principal con métricas globales
- `/views/administrador/usuarios.html` - Gestión de usuarios
- `/views/administrador/cursos.html` - Gestión de cursos
- `/views/administrador/inscripciones.html` - Ver todas las inscripciones
- `/views/administrador/calificaciones.html` - Ver todas las calificaciones
- `/views/administrador/reportes.html` - Reportes y estadísticas
- `/views/administrador/auditoria.html` - Logs del sistema
- `/views/administrador/backup.html` - Respaldo de BD

### Datos del Dashboard

```json
{
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

---

## ANALISTA (Nivel 3)

### Descripción
Verifica la información de los cursos, organiza la asignación de facilitadores y gestiona la oferta académica.

### Permisos

| Módulo | Permisos |
|--------|----------|
| **Usuarios** | ✅ Ver facilitadores y participantes (no admin/analistas) |
| **Cursos** | ✅ Crear, leer, actualizar, asignar facilitadores |
| **Inscripciones** | ✅ Ver todas |
| **Calificaciones** | ✅ Ver todas |
| **Reportes** | ✅ Reportes de cursos |
| **Auditoría** | ❌ Sin acceso |
| **Backup** | ❌ Sin acceso |

### Vistas Disponibles

- `/views/analista/dashboard.html` - Panel del analista
- `/views/analista/cursos.html` - Gestión de cursos
- Comparte reportes con administrador

### Restricciones

- No puede ver administradores u otros analistas
- No puede crear usuarios (solo admin)
- No puede eliminar cursos (solo admin)
- No puede acceder a auditoría ni backup

### Datos del Dashboard

```json
{
  "total_cursos": 10,
  "cursos_planificados": 5,
  "total_facilitadores": 4,
  "total_inscripciones": 12,
  "cursos_por_area": [...],
  "cursos_sin_facilitador": [...]
}
```

---

## FACILITADOR (Nivel 2)

### Descripción
Docente que imparte cursos, registra calificaciones y gestiona las evaluaciones de sus estudiantes.

### Permisos

| Módulo | Permisos |
|--------|----------|
| **Usuarios** | ✅ Ver sus estudiantes inscritos |
| **Cursos** | ✅ Ver sus cursos asignados |
| **Inscripciones** | ✅ Ver inscripciones de sus cursos |
| **Calificaciones** | ✅ Registrar, editar calificaciones de sus cursos |
| **Reportes** | ✅ Reportes de sus cursos |
| **Auditoría** | ❌ Sin acceso |
| **Backup** | ❌ Sin acceso |

### Vistas Disponibles

- `/views/facilitador/dashboard.html` - Panel del facilitador
- `/views/facilitador/mis-cursos.html` - Cursos asignados
- `/views/facilitador/estudiantes.html` - Estudiantes inscritos
- `/views/facilitador/calificaciones.html` - Registrar calificaciones
- `/views/facilitador/reportes.html` - Reportes de sus cursos

### Restricciones

- Solo ve cursos donde es el facilitador asignado
- Solo puede calificar a estudiantes de sus cursos
- No puede crear/editar cursos
- No puede modificar inscripciones

### Datos del Dashboard

```json
{
  "mis_cursos": 3,
  "mis_estudiantes": 15,
  "cursos_activos": [
    {
      "id": 1,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP",
      "total_inscritos": 5
    }
  ]
}
```

---

## PARTICIPANTE (Nivel 1)

### Descripción
Estudiante que se inscribe en cursos, consulta sus notas, plan de evaluación y obtiene certificados.

### Permisos

| Módulo | Permisos |
|--------|----------|
| **Usuarios** | ✅ Ver solo su perfil |
| **Cursos** | ✅ Ver cursos disponibles de su área |
| **Inscripciones** | ✅ Inscribirse, ver sus inscripciones, cancelar |
| **Calificaciones** | ✅ Ver solo sus calificaciones |
| **Reportes** | ❌ Sin acceso |
| **Auditoría** | ❌ Sin acceso |
| **Backup** | ❌ Sin acceso |

### Vistas Disponibles

- `/views/participante/dashboard.html` - Panel del estudiante
- `/views/participante/inscribir.html` - Inscribirse en cursos
- `/views/participante/mis-cursos.html` - Cursos inscritos
- `/views/participante/mis-notas.html` - Calificaciones y notas

### Restricciones

- Solo ve cursos de su área/carrera
- No puede ver otros estudiantes
- No puede modificar calificaciones
- No puede inscribir a otros

### Datos del Dashboard

```json
{
  "mis_cursos": 2,
  "cursos_completados": 1,
  "promedio_general": 17.5,
  "inscripciones": [
    {
      "id": 2,
      "estado": "En Progreso",
      "nota_final": null,
      "codigo": "PROG-001",
      "nombre": "Programacion en PHP"
    }
  ]
}
```

---

## Matriz de Permisos Completa

| Funcionalidad | Administrador | Analista | Facilitador | Participante |
|---------------|:-------------:|:--------:|:-----------:|:------------:|
| **Usuarios** |||||
| Crear usuarios | ✅ | ❌ | ❌ | ❌ |
| Ver todos los usuarios | ✅ | ✅* | ❌ | ❌ |
| Editar usuarios | ✅ | ❌ | ❌ | ❌ |
| Eliminar usuarios | ✅ | ❌ | ❌ | ❌ |
| Ver facilitadores | ✅ | ✅ | ✅ | ❌ |
| Ver analistas | ✅ | ❌ | ❌ | ❌ |
| **Cursos** |||||
| Crear cursos | ✅ | ✅ | ❌ | ❌ |
| Ver todos los cursos | ✅ | ✅ | ✅** | ✅*** |
| Editar cursos | ✅ | ✅ | ❌ | ❌ |
| Eliminar cursos | ✅ | ❌ | ❌ | ❌ |
| Asignar facilitador | ✅ | ✅ | ❌ | ❌ |
| Crear evaluaciones | ✅ | ✅ | ❌ | ❌ |
| **Inscripciones** |||||
| Ver todas las inscripciones | ✅ | ✅ | ✅** | ❌ |
| Inscribir participante | ✅ | ✅ | ❌ | ✅ (a sí mismo) |
| Modificar estado | ✅ | ❌ | ❌ | ❌ |
| Cancelar inscripción | ✅ | ❌ | ❌ | ✅ (propias) |
| **Calificaciones** |||||
| Ver todas | ✅ | ✅ | ✅** | ❌ |
| Registrar calificación | ✅ | ❌ | ✅** | ❌ |
| Editar calificación | ✅ | ❌ | ✅** | ❌ |
| Ver mis notas | ✅ | ❌ | ❌ | ✅ |
| **Reportes** |||||
| Estadísticas globales | ✅ | ❌ | ❌ | ❌ |
| Reportes de cursos | ✅ | ✅ | ✅** | ❌ |
| Auditoría | ✅ | ❌ | ❌ | ❌ |
| Backup | ✅ | ❌ | ❌ | ❌ |

\* Analista solo ve facilitadores y participantes  
\** Solo sus cursos asignados  
\*** Solo cursos de su área disponibles para inscripción

---

## Middleware de Autorización

### Implementación

```php
function requireAuth(): callable {
    return function() {
        Auth::init();
        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Sesion requerida']);
            return false;
        }
        return true;
    };
}

function requireAdmin(): callable {
    return function() {
        Auth::init();
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Se requiere rol Administrador']);
            return false;
        }
        return true;
    };
}

function requireAnalystOrAbove(): callable {
    return function() {
        Auth::init();
        if (!Auth::check() || !Auth::isAnalystOrAbove()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Se requiere rol Analista o superior']);
            return false;
        }
        return true;
    };
}

function requireTeacherOrAbove(): callable {
    return function() {
        Auth::init();
        if (!Auth::check() || !Auth::isTeacherOrAbove()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Se requiere rol Facilitador o superior']);
            return false;
        }
        return true;
    };
}

function requireRole(string $role): callable {
    return function() use ($role) {
        Auth::init();
        if (!Auth::check() || !Auth::hasRole($role)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => "Se requiere rol {$role}"]);
            return false;
        }
        return true;
    };
}
```

### Uso en Endpoints

```php
// Solo administradores
$router->register('DELETE', '/backend/api/api.php', $handler, [requireAdmin()], 'usuario');

// Analista o superior
$router->register('POST', '/backend/api/api.php', $handler, [requireAnalystOrAbove()], 'curso');

// Facilitador o superior
$router->register('GET', '/backend/api/api.php', $handler, [requireTeacherOrAbove()], 'inscripciones');

// Solo participantes
$router->register('GET', '/backend/api/api.php', $handler, [requireRole('Participante')], 'cursos-disponibles');

// Cualquier usuario autenticado
$router->register('GET', '/backend/api/api.php', $handler, [requireAuth()], 'mis-notas');
```

---

## Flujo de Registro de Usuarios

### Registro Público (Participantes y Facilitadores)

```
┌─────────────┐    POST /register    ┌──────────────┐
│   Visitante │ ───────────────────► │   Sistema    │
│  (sin auth) │                      │              │
└─────────────┘                      │ 1. Validar   │
                                     │    datos     │
                                     │ 2. Verificar │
                                     │    duplicados│
                                     │ 3. Hash      │
                                     │    password  │
                                     │ 4. Insertar  │
                                     │    usuario   │
                                     │ 5. Log       │
                                     │    actividad │
                                     └──────────────┘
```

### Creación por Administrador (Analistas)

```
┌─────────────┐    POST /usuario   ┌──────────────┐
│   Admin     │ ───────────────────► │   Sistema    │
│  (authed)   │                      │              │
└─────────────┘                      │ 1. Validar   │
                                     │    rol Admin │
                                     │ 2. Validar   │
                                     │    datos     │
                                     │ 3. Generar   │
                                     │    password  │
                                     │    temporal  │
                                     │ 4. Insertar  │
                                     │    usuario   │
                                     │ 5. Retornar  │
                                     │    temp_pass │
                                     └──────────────┘
```

---

## Cambio de Contraseña

| Rol | Método |
|-----|--------|
| Todos | Iniciar sesión → PUT /password |
| Creados por Admin | Usar contraseña temporal → Cambiar en primer login |

---

## Cuotas y Límites por Rol

| Aspecto | Administrador | Analista | Facilitador | Participante |
|---------|:-------------:|:--------:|:-----------:|:------------:|
| Cursos | Ilimitado | Ilimitado | Solo asignados | Solo inscritos |
| Estudiantes visibles | Todos | Todos | Solo sus cursos | Solo sí mismo |
| Inscripciones que puede crear | Ilimitado | Ilimitado | Ninguna | Solo propias |
| Calificaciones que puede editar | Todas | Ninguna | Solo sus cursos | Ninguna |

---

## Escenarios de Uso

### Escenario 1: Nuevo Curso

1. **Analista** crea el curso en estado "Planificado"
2. **Analista** asigna facilitador al curso
3. **Analista** crea plan de evaluación
4. **Facilitador** ve el curso en su dashboard
5. **Participante** se inscribe (si está en su área)
6. **Facilitador** registra calificaciones
7. **Sistema** calcula nota final automáticamente

### Escenario 2: Reporte de Notas

1. **Participante** consulta sus notas (solo las suyas)
2. **Facilitador** consulta notas de estudiantes de sus cursos
3. **Analista** consulta todas las inscripciones y notas
4. **Administrador** genera estadísticas globales

### Escenario 3: Auditoría

1. **Administrador** revisa logs de actividad
2. Cada acción está registrada con: usuario, acción, tabla, datos antes/después, IP, timestamp
3. **Administrador** puede filtrar por fecha, usuario, o tipo de acción
