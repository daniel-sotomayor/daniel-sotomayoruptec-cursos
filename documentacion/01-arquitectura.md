# Arquitectura del Sistema

## UPTEC Cursos v2.0 - Documentación de Arquitectura

---

## Visión General

El sistema implementa una **arquitectura monolítica modular** con separación clara entre capas:

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │ Landing Page │  │ Login/Registro│ │  Dashboard Views │   │
│  │  (index.html)│  │ (login.html)  │ │ (views/{rol}/)   │   │
│  └──────────────┘  └──────────────┘  └──────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     API LAYER (RESTful)                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │    Router    │  │  Endpoints   │  │   Middleware     │   │
│  │  (api.php)   │  │(auth, users) │  │(Auth, CSRF, etc) │   │
│  └──────────────┘  └──────────────┘  └──────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   BUSINESS LOGIC LAYER                      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │    Auth      │  │   Validator  │  │    Sanitizer     │   │
│  │   (auth.php) │  │(validator.php)│ │ (sanitizer.php)  │   │
│  └──────────────┘  └──────────────┘  └──────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    DATA ACCESS LAYER                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │   Database   │  │    MySQL     │  │  PDO (Prepared)  │   │
│  │(config.php)  │  │   (schema)   │  │    Statements    │   │
│  └──────────────┘  └──────────────┘  └──────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## Patrones de Diseño Implementados

### 1. Singleton Pattern
**Uso**: Conexión a base de datos
**Archivo**: `backend/db/config.php`

```php
class Database {
    private static ?self $instance = null;
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

**Ventaja**: Una única conexión PDO compartida en toda la aplicación.

---

### 2. Router Pattern
**Uso**: Enrutamiento de API RESTful
**Archivo**: `backend/api/api.php`

```php
class Router {
    private array $routes = [];
    
    public function register(string $method, string $pattern, callable $handler, array $middleware = []): void
    public function dispatch(): void
}
```

**Ventaja**: Endpoints definidos de forma declarativa con soporte para middleware.

---

### 3. Middleware Pattern
**Uso**: Autenticación y autorización
**Archivo**: `backend/api/api.php`

```php
function requireAuth(): callable
function requireAdmin(): callable
function requireAnalystOrAbove(): callable
function requireTeacherOrAbove(): callable
function validateCsrf(): callable
```

**Ventaja**: Cadena de responsabilidad para validar requests antes de procesarlos.

---

### 4. DRY (Don't Repeat Yourself)

**Ejemplos de implementación:**

| Componente | Reutilización |
|------------|---------------|
| `API` (api.js) | Cliente HTTP compartido en todos los dashboards |
| `Auth` (auth.php) | Autenticación centralizada para todos los endpoints |
| `CSRF` (csrf.php) | Tokens compartidos en forms y AJAX |
| `Sanitizer` | Sanitización consistente de inputs |

---

## Estructura de Directorios Detallada

### Backend (`/backend/`)

```
backend/
├── api/
│   ├── api.php              # Router principal y helpers
│   ├── auth.php             # Endpoints: login, logout, register, password
│   ├── usuarios.php         # Endpoints: CRUD usuarios, facilitadores, analistas
│   ├── cursos.php           # Endpoints: CRUD cursos, evaluaciones, mis-cursos
│   ├── inscripciones.php    # Endpoints: inscripciones, cursos-disponibles
│   ├── calificaciones.php   # Endpoints: calificaciones, mis-notas, estudiantes-curso
│   ├── reportes.php         # Endpoints: resumen, estadisticas, auditoria, certificado
│   └── backup.php           # Endpoint: backup de base de datos
│
├── security/
│   ├── auth.php             # Clase Auth: sesiones, bcrypt, roles, logs
│   ├── csrf.php             # Clase CSRF: tokens, validación, regeneración
│   ├── sanitizer.php        # Clase Sanitizer: XSS prevention, limpieza de datos
│   └── validator.php        # Clase Validator: validación de inputs
│
└── db/
    └── config.php           # Clase Database (Singleton), función getDB()
```

### Frontend (`/frontend/`)

```
frontend/
├── index.html               # Landing page institucional (Glassmorphism)
├── login.html               # Login/registro con animaciones
│
├── css/
│   ├── main.css             # Estilos del sistema (dashboards)
│   └── login.css            # Estilos de login y landing
│
├── js/
│   ├── api.js               # Cliente API: GET, POST, PUT, DELETE, CSRF
│   ├── auth.js              # Módulo de autenticación del frontend
│   └── dashboard.js         # Funcionalidades compartidas del dashboard
│
└── views/
    ├── administrador/         # Vistas del rol Administrador
    │   ├── dashboard.html     # Panel principal con métricas
    │   ├── usuarios.html      # Gestión de usuarios
    │   ├── cursos.html        # Gestión de cursos
    │   ├── reportes.html      # Reportes y estadísticas
    │   ├── auditoria.html     # Logs del sistema
    │   ├── backup.html        # Respaldo de BD
    │   ├── calificaciones.html
    │   └── inscripciones.html
    │
    ├── analista/              # Vistas del rol Analista
    │   ├── dashboard.html
    │   ├── cursos.html
    │   └── (comparte vistas de admin para reportes)
    │
    ├── facilitador/           # Vistas del rol Facilitador
    │   ├── dashboard.html
    │   ├── calificaciones.html
    │   ├── estudiantes.html
    │   ├── mis-cursos.html
    │   └── reportes.html
    │
    └── participante/          # Vistas del rol Participante
        ├── dashboard.html
        ├── mis-cursos.html
        ├── mis-notas.html
        └── inscribir.html
```

### Base de Datos (`/db/`)

```
db/
├── schema.sql               # Esquema completo de la BD
│                           # Tablas: usuarios, cursos, evaluaciones,
│                           #         inscripciones, calificaciones, horarios,
│                           #         asistencias, logs_actividad
│                           # Vistas: vista_cursos_facilitadores,
│                           #         vista_participantes_cursos
│                           # Triggers: actualizar_nota_final_inscripcion
└── seed.sql                 # Datos iniciales de prueba
```

---

## Flujo de Datos

### 1. Autenticación

```
Usuario → login.html → POST /api.php?endpoint=login
                              ↓
                    ┌─────────────────────┐
                    │   Auth::login()     │
                    │  - Validar creden.  │
                    │  - Bcrypt verify    │
                    │  - Crear sesión     │
                    │  - Log actividad    │
                    └─────────────────────┘
                              ↓
                    Redirección según rol
```

### 2. Petición API Autenticada

```
Frontend → API.get()/post() → + Header X-CSRF-Token
                                     ↓
                    ┌─────────────────────────────────────┐
                    │          Router::dispatch()         │
                    │  1. Verificar endpoint registrado   │
                    │  2. Ejecutar middleware             │
                    │     - requireAuth()                 │
                    │     - validateCsrf()                │
                    │  3. Ejecutar handler                │
                    │  4. Log actividad (si aplica)       │
                    │  5. Retornar JSON                   │
                    └─────────────────────────────────────┘
```

### 3. Operación CRUD

```
Endpoint → Validación (Validator) → Sanitización (Sanitizer)
                                           ↓
                              Prepared Statement (PDO)
                                           ↓
                              ┌─────────────────────┐
                              │    MySQL Database   │
                              └─────────────────────┘
                                           ↓
                              Log de Auditoría (Auth::logActivity)
                                           ↓
                              JSON Response
```

---

## Convenciones de Código

### PHP

| Convención | Ejemplo |
|------------|---------|
| Namespaces | No se usan (estructura plana) |
| Clases | PascalCase: `Auth`, `Database`, `Validator` |
| Métodos | camelCase: `getInstance()`, `validateCsrf()` |
| Constantes | UPPER_SNAKE_CASE: `SESSION_USER`, `INACTIVITY_LIMIT` |
| Archivos | lowercase-with-dashes: `api.php`, `sanitizer.php` |

### JavaScript

| Convención | Ejemplo |
|------------|---------|
| Variables | camelCase: `csrfToken`, `currentUser` |
| Funciones | camelCase: `fetchCsrfToken()`, `handleLogin()` |
| Objetos globales | UPPER_CASE: `API`, `AUTH` |
| Archivos | lowercase-with-dashes: `api.js`, `dashboard.js` |

### CSS

| Convención | Ejemplo |
|------------|---------|
| Variables CSS | `--uptec-blue`, `--space-lg` |
| Clases BEM | `.landing-nav__logo`, `.btn--primary` |

---

## Dependencias Externas

El sistema **no utiliza dependencias externas** (zero-dependency):

| Funcionalidad | Implementación |
|---------------|----------------|
| HTTP Requests | Fetch API (nativo) |
| UI Components | CSS/JS vanilla |
| Iconos | Emojis Unicode |
| Animaciones | CSS Keyframes + JS |
| Base de Datos | MySQLi/PDO (nativo PHP) |

---

## Escalabilidad

### Puntos de Extensión

1. **Nuevos Endpoints**: Registrar en archivos de `/backend/api/`
2. **Nuevos Roles**: Agregar a jerarquía en `Auth::$roleHierarchy`
3. **Nuevas Tablas**: Agregar a `schema.sql` con índices apropiados
4. **Nuevas Vistas**: Crear en `/frontend/views/{rol}/`

### Límites Conocidos

| Aspecto | Límite | Nota |
|---------|--------|------|
| Sesiones | 30 minutos | Tiempo de inactividad configurable |
| Intentos login | 5 | Bloqueo de 15 minutos |
| CSRF Token | 1 hora | Regeneración automática |
| Logs auditoría | 500 últimos | Endpoint de auditoría |

---

## Seguridad en la Arquitectura

Ver documento completo: [04-seguridad.md](./04-seguridad.md)

```
┌────────────────────────────────────────────────────────────┐
│                      CAPA DE SEGURIDAD                     │
├────────────────────────────────────────────────────────────┤
│ 1. CORS Headers    → Permisos estrictos de origen          │
│ 2. CSRF Tokens    → Validación en POST/PUT/DELETE          │
│ 3. Auth Middleware → Verificación de sesión y roles        │
│ 4. Input Sanitizer → Limpieza de XSS                       │
│ 5. Validator      → Validación de tipos y formatos         │
│ 6. PDO Prepared    → Prevención SQL Injection              │
│ 7. Bcrypt          → Hashing de contraseñas (cost 12)      │
│ 8. Session Security→ HttpOnly, Secure, SameSite            │
└────────────────────────────────────────────────────────────┘
```
