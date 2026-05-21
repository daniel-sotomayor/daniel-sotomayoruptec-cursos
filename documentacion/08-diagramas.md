# Diagramas del Sistema

## 1. Arquitectura General

El sistema está diseñado como una aplicación web monolítica modular con separación clara entre frontend, API, lógica de negocio y acceso a datos.

![Arquitectura General](./diagrams/architecture.png)

```mermaid
graph TB
    subgraph Frontend
      A[index.html / login.html]
      B[views/{rol}/ dashboard pages]
      C[js/api.js, js/auth.js, js/dashboard.js]
    end

    subgraph Backend
      D[backend/api/api.php]
      E[backend/api/*.php endpoints]
      F[backend/security/*]
      G[backend/db/config.php]
    end

    subgraph Database
      H[MySQL db schema.sql]
    end

    A --> C
    B --> C
    C --> D
    D --> E
    E --> F
    E --> G
    G --> H
    F --> G
``` 

## 2. Diagrama Entidad-Relación (ER)

Este diagrama muestra las entidades principales y sus relaciones en la base de datos.

![Diagrama Entidad-Relación](./diagrams/er-diagram.png)

```mermaid
erDiagram
    USUARIOS {
        INT id PK
        VARCHAR cedula
        VARCHAR nombre
        VARCHAR apellidos
        VARCHAR correo
        VARCHAR telefono
        VARCHAR password
        ENUM rol
        VARCHAR area
        TINYINT activo
        DATETIME ultimo_acceso
    }
    CURSOS {
        INT id PK
        VARCHAR codigo
        VARCHAR nombre
        TEXT descripcion
        INT duracion_horas
        DATE fecha_inicio
        DATE fecha_fin
        INT cupo_maximo
        VARCHAR area
        ENUM nivel
        ENUM estado
        INT facilitador_id FK
        INT analista_id FK
    }
    EVALUACIONES {
        INT id PK
        INT curso_id FK
        VARCHAR nombre
        ENUM tipo
        INT peso
        DATE fecha_evaluacion
        INT orden
    }
    INSCRIPCIONES {
        INT id PK
        INT usuario_id FK
        INT curso_id FK
        DATE fecha_inscripcion
        ENUM estado
        DECIMAL nota_final
        TEXT observaciones
    }
    CALIFICACIONES {
        INT id PK
        INT inscripcion_id FK
        INT evaluacion_id FK
        DECIMAL nota
        INT peso
        DATE fecha_evaluacion
        TEXT observaciones
    }
    HORARIOS {
        INT id PK
        INT curso_id FK
        ENUM dia_semana
        TIME hora_inicio
        TIME hora_fin
        VARCHAR aula
    }
    ASISTENCIAS {
        INT id PK
        INT inscripcion_id FK
        DATE fecha
        ENUM estado
        TEXT observacion
    }
    LOGS_ACTIVIDAD {
        BIGINT id PK
        INT usuario_id FK
        VARCHAR usuario_nombre
        VARCHAR accion
        VARCHAR tabla_afectada
        INT registro_id
        JSON datos_anteriores
        JSON datos_nuevos
        VARCHAR ip_address
        VARCHAR user_agent
        DATETIME fecha_hora
    }

    USUARIOS ||--o{ CURSOS : "asigna"
    USUARIOS ||--o{ CURSOS : "supervisa"
    CURSOS ||--o{ EVALUACIONES : "tiene"
    CURSOS ||--o{ INSCRIPCIONES : "recibe"
    INSCRIPCIONES ||--o{ CALIFICACIONES : "registra"
    CURSOS ||--o{ HORARIOS : "programa"
    INSCRIPCIONES ||--o{ ASISTENCIAS : "genera"
    USUARIOS ||--o{ INSCRIPCIONES : "inscribe"
    USUARIOS ||--o{ LOGS_ACTIVIDAD : "registra"
``` 

## 3. Flujo de Autenticación y Autorización

![Flujo de Autenticación](./diagrams/auth-flow.png)

```mermaid
sequenceDiagram
    participant User as Usuario
    participant Front as Frontend
    participant API as API
    participant Auth as Auth Layer
    participant DB as Base de Datos

    User->>Front: introduce credenciales
    Front->>API: POST /api.php?endpoint=login
    API->>Auth: validar request + CSRF
    Auth->>DB: consultar usuario
    DB-->>Auth: devolver hash
    Auth-->>API: autenticar / crear sesión
    API-->>Front: respuesta con rol
    Front-->>User: redirigir dashboard
```

## 4. Flujo de Inscripción a Curso

![Flujo de Inscripción](./diagrams/inscripcion-flow.png)

```mermaid
sequenceDiagram
    participant Participante
    participant Frontend
    participant API
    participant Cursos
    participant Inscripcion

    Participante->>Frontend: solicita cursos disponibles
    Frontend->>API: GET /api.php?endpoint=cursos_disponibles
    API->>Cursos: consulta cursos abiertos
    Cursos-->>API: lista de cursos
    API-->>Frontend: responde con cursos
    Frontend->>Participante: muestra cursos
    Participante->>Frontend: elige curso y confirma
    Frontend->>API: POST /api.php?endpoint=inscribir
    API->>Inscripcion: crea registro
    Inscripcion-->>API: estado creado
    API-->>Frontend: confirma inscripción
```

## 5. Notas de Diagramas

- El backend usa middleware para `requireAuth()`, `requireAdmin()`, `validateCsrf()`.
- El frontend centraliza la comunicación con la API en `frontend/js/api.js`.
- El flujo de datos está controlado por el router en `backend/api/api.php`.
- Los logs de actividad permiten auditar operaciones críticas del sistema.
