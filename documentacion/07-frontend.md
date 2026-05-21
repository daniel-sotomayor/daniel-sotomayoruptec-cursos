# Frontend

## UPTEC Cursos v2.0 - Documentación del Frontend

---

## Visión General

El frontend está construido con tecnologías web nativas (vanilla stack):

| Tecnología            | Uso                                  |
|-----------------------|--------------------------------------|
| **HTML5**             | Estructura semántica                 |
| **CSS3**              | Estilos con variables, grid, flexbox |
| **JavaScript (ES6+)** | Interactividad, API calls            |
| **Fetch API**         | Comunicación con backend             |
| **Emojis Unicode**    | Iconografía (sin dependencias)       |

---

## Estructura de Carpetas

```
frontend/
├── index.html                 # Landing page institucional
├── login.html                 # Login y registro
├── css/
│   ├── main.css              # Estilos de dashboard
│   └── login.css             # Estilos de login/landing
├── js/
│   ├── api.js                # Cliente API
│   ├── auth.js               # Módulo de autenticación
│   └── dashboard.js          # Utilidades de dashboard
└── views/
    ├── administrador/        # 8 vistas
    ├── analista/             # 2 vistas
    ├── facilitador/          # 5 vistas
    └── participante/         # 4 vistas
```

---

## Landing Page (`index.html`)

### Diseño: Glassmorphism

Estilo moderno con efectos de transparencia y desenfoque.

```css
.landing-feature {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 24px;
}
```

### Secciones

| Sección | Descripción |
|---------|-------------|
| **Navbar** | Navegación fija con logo UPTEC |
| **Hero** | Título principal, descripción, CTAs |
| **Características** | 6 cards con features del sistema |
| **Roles** | 4 cards describiendo cada rol |
| **Stats** | Estadísticas visuales |
| **CTA** | Llamado a la acción final |
| **Footer** | Copyright UPTEC |

### Animaciones

- Partículas flotantes (fondo animado)
- Fade-in en scroll (Intersection Observer)
- Efecto hover en cards (transform + shadow)
- Navbar con efecto al hacer scroll

---

## Login (`login.html`)

### Diseño: Slider de Formularios

Dos formularios en un contenedor deslizante:

```
┌─────────────────────────────────────────┐
│  INFO PANEL         │    FORM SLIDER   │
│                     │                  │
│  ¿Ya tienes         │  ┌───────────┐   │
│  cuenta?            │  │  LOGIN    │   │
│                     │  │  Form     │   │
│  [Iniciar Sesion]   │  └───────────┘   │
│                     │                  │
├─────────────────────┤  ┌───────────┐   │
│  ¿Eres nuevo?       │  │ REGISTER  │   │
│                     │  │  Form     │   │
│  [Crear Cuenta]     │  └───────────┘   │
└─────────────────────────────────────────┘
```

### Modos

| Modo | Descripción |
|------|-------------|
| **Login** | Formulario de inicio de sesión (email + password) |
| **Register** | Formulario de registro con selector de rol |

### Selector de Rol (Registro)

```html
<div class="role-selector">
    <label class="role-option">
        <input type="radio" name="rol" value="Participante">
        <div class="role-card">
            <span class="role-icon">👨‍🎓</span>
            <span class="role-label">Participante</span>
        </div>
    </label>
    <label class="role-option">
        <input type="radio" name="rol" value="Facilitador">
        <div class="role-card">
            <span class="role-icon">👨‍🏫</span>
            <span class="role-label">Facilitador</span>
        </div>
    </label>
</div>
```

### Campo de Área (Solo Participantes)

Se muestra/oculta dinámicamente según el rol seleccionado.

**Áreas disponibles:**
- Administración
- Informática
- Mecánica
- Eléctrica
- Mantenimiento
- Transporte Ferroviario

### Sistema de Notificaciones Toast

```javascript
// Uso
showToast('success', 'Éxito', 'Operación completada');
showToast('error', 'Error', 'Credenciales inválidas');
```

---

## Módulos JavaScript

### `api.js` - Cliente API

**Funcionalidades:**
- Gestión automática de tokens CSRF
- Métodos HTTP: GET, POST, PUT, DELETE
- Descarga de archivos (backup)
- Manejo de errores global

**API expuesta:**
```javascript
window.API = {
    init()           // Obtener CSRF token inicial
    get(endpoint, params)
    post(endpoint, data)
    put(endpoint, id, data)
    delete(endpoint, id)
    download(endpoint, params, filename)
}
```

**Ejemplo de uso:**
```javascript
// Obtener lista de cursos
const response = await API.get('cursos', { estado: 'En Curso' });
console.log(response.cursos);

// Crear usuario
await API.post('usuario', {
    cedula: 'V12345678',
    nombre: 'Juan',
    apellidos: 'Perez',
    correo: 'jperez@uptec.edu.ve',
    telefono: '04121111111',
    rol: 'Facilitador'
});
```

### `auth.js` - Autenticación

**Funciones:**
```javascript
// Módulo AUTH global
AUTH.login(email, password)
AUTH.logout()
AUTH.check()          // Verificar sesión activa
AUTH.user()           // Obtener datos del usuario
AUTH.require()        // Redirigir si no hay sesión
AUTH.isAdmin()
AUTH.isAnalyst()
AUTH.isTeacher()
AUTH.isStudent()
```

### `dashboard.js` - Utilidades de Dashboard

**Componentes compartidos:**
```javascript
// Sidebar toggle
initSidebar()

// Cerrar sesión
setupLogout()

// Mostrar info del usuario en navbar
showUserInfo()

// Sistema de tabs
initTabs()

// Modales
openModal(id)
closeModal(id)

// Tablas dinámicas
renderTable(data, columns, container)
```

---

## Vistas por Rol

### ADMINISTRADOR (`views/administrador/`)

#### 1. `dashboard.html`

**Métricas mostradas:**
- Cards con totales (estudiantes, facilitadores, analistas, cursos, inscripciones)
- Gráfico de cursos por estado
- Lista de cursos recientes
- Lista de usuarios recientes
- Actividad reciente (últimos logs)
- Gráfico de estudiantes por área
- Top 5 cursos con más estudiantes

**Widgets:**
```javascript
loadResumen()      // Métricas principales
loadCursosPorEstado()
loadCursosRecientes()
loadUsuariosRecientes()
loadActividadReciente()
loadEstudiantesPorArea()
loadTopCursos()
```

#### 2. `usuarios.html`

**Funcionalidades:**
- Tabla de usuarios con paginación
- Filtros: por rol, estado, búsqueda
- Modal para crear usuario
- Edición inline de usuarios
- Desactivar usuario (soft delete)

**Roles gestionables:**
- Administrador (solo creación)
- Analista
- Facilitador
- Participante

#### 3. `cursos.html`

**Funcionalidades:**
- Tabla de cursos con filtros
- Modal para crear curso
- Asignar facilitador desde lista desplegable
- Asignar analista
- Crear plan de evaluación
- Ver detalles del curso (estudiantes inscritos, horarios, evaluaciones)

#### 4. `inscripciones.html`

- Listado de todas las inscripciones
- Filtros por curso, estado, búsqueda
- Cambiar estado de inscripción

#### 5. `calificaciones.html`

- Ver todas las calificaciones del sistema
- Filtrar por curso o estudiante

#### 6. `reportes.html`

**Reportes disponibles:**
- Estadísticas globales
- Promedio general de notas
- Tasa de aprobación
- Cursos por área
- Inscripciones por mes (últimos 6 meses)
- Top facilitadores

#### 7. `auditoria.html`

- Logs de actividad del sistema
- Filtros: por acción, tabla, fechas
- Visualización de datos antes/después (JSON)
- Paginación (500 registros máximo)

#### 8. `backup.html`

- Botón para descargar backup SQL
- Información sobre la última exportación

---

### ANALISTA (`views/analista/`)

#### 1. `dashboard.html`

**Métricas:**
- Total de cursos
- Cursos planificados (sin iniciar)
- Total de facilitadores
- Total de inscripciones
- Cursos por área
- Cursos sin facilitador asignado

#### 2. `cursos.html`

Similar al admin pero con restricciones:
- ✅ Crear, editar cursos
- ✅ Asignar facilitadores
- ✅ Crear plan de evaluación
- ❌ Eliminar cursos
- ❌ Ver todos los usuarios (solo facilitadores)

---

### FACILITADOR (`views/facilitador/`)

#### 1. `dashboard.html`

**Métricas:**
- Mis cursos (cantidad)
- Mis estudiantes (total inscritos en sus cursos)
- Lista de cursos activos con conteo de inscritos

#### 2. `mis-cursos.html`

- Lista de cursos donde es facilitador asignado
- Acceso rápido a calificaciones por curso

#### 3. `estudiantes.html`

- Listado de estudiantes por curso
- Información de contacto
- Estado de inscripción
- Nota final (si aplica)

#### 4. `calificaciones.html`

**Funcionalidades:**
- Seleccionar curso
- Ver lista de estudiantes inscritos
- Registrar calificación:
  - Tipo (Parcial, Final, Proyecto, Asistencia, Trabajo, Otro)
  - Descripción
  - Nota (0-20)
  - Peso (%)
  - Fecha
- Ver calificaciones registradas
- Editar calificaciones existentes

**Cálculo automático:**
- Al registrar/editar calificación, el backend calcula la nota final ponderada
- El trigger actualiza el estado de la inscripción (Completado/Reprobado)

#### 5. `reportes.html`

- Reportes limitados a sus cursos
- Estadísticas de aprobación por curso
- Listado de estudiantes aprobados/reprobados

---

### PARTICIPANTE (`views/participante/`)

#### 1. `dashboard.html`

**Métricas:**
- Mis cursos (inscritos)
- Cursos completados
- Promedio general
- Lista de inscripciones con estado y nota final

#### 2. `inscribir.html`

**Flujo de inscripción:**
1. Sistema verifica área/carrera del participante
2. Muestra solo cursos de esa área
3. Excluye cursos en los que ya está inscrito
4. Solo cursos en estado "Planificado" o "En Curso"
5. Verifica cupos disponibles

**Restricciones:**
- Si no tiene área asignada → mensaje de error
- Si no hay cursos disponibles → mensaje informativo

#### 3. `mis-cursos.html`

- Lista de cursos inscritos
- Información del facilitador
- Estado de inscripción
- Fecha de inscripción

#### 4. `mis-notas.html`

**Información mostrada por curso:**
- Datos del curso (nombre, código, área)
- Facilitador
- Plan de evaluación del curso
- Calificaciones obtenidas
- Nota final calculada
- Estado (En Progreso, Completado, Reprobado)

**Visualización:**
```
Curso: Programación en PHP
Facilitador: Carlos González

Plan de Evaluación:
- Parcial 1 (20%)
- Parcial 2 (25%)
- Proyecto Final (30%)
- Asistencia (25%)

Mis Calificaciones:
- Parcial 1: 16.00 / 20
- Parcial 2: --
...

Nota Final: -- (En Progreso)
```

---

## Componentes UI Compartidos

### Sidebar de Navegación

```html
<nav class="sidebar">
    <div class="sidebar__logo">UPTEC</div>
    <ul class="sidebar__menu">
        <li><a href="dashboard.html">📊 Dashboard</a></li>
        <li><a href="cursos.html">📚 Cursos</a></li>
        <!-- Items específicos por rol -->
    </ul>
    <button class="btn-logout">🚪 Cerrar Sesión</button>
</nav>
```

### Tablas de Datos

```javascript
// Ejemplo de configuración de columnas
const columns = [
    { key: 'codigo', label: 'Código' },
    { key: 'nombre', label: 'Nombre' },
    { key: 'estado', label: 'Estado', render: (v) => `<span class="badge badge-${v}">${v}</span>` },
    { key: 'acciones', label: 'Acciones', render: (row) => `
        <button onclick="editar(${row.id})">✏️</button>
        <button onclick="eliminar(${row.id})">🗑️</button>
    `}
];

renderTable(data, columns, '#tabla-container');
```

### Modales

```html
<div class="modal" id="modal-curso">
    <div class="modal__content">
        <h3>Nuevo Curso</h3>
        <form id="form-curso">
            <!-- Campos -->
        </form>
        <button onclick="closeModal('modal-curso')">Cerrar</button>
    </div>
</div>
```

### Badges de Estado

| Estado | Clase CSS | Color |
|--------|-----------|-------|
| Activo | `badge-activo` | 🟢 Verde |
| Inactivo | `badge-inactivo` | 🔴 Rojo |
| Planificado | `badge-planificado` | 🔵 Azul |
| En Curso | `badge-en-curso` | 🟡 Amarillo |
| Finalizado | `badge-finalizado` | 🟢 Verde oscuro |
| Cancelado | `badge-cancelado` | 🔴 Rojo |

---

## CSS Variables (Design System)

### Colores UPTEC

```css
:root {
    --uptec-blue: #0061f1;
    --uptec-blue-dark: #004fa3;
    --uptec-red: #e00000;
    --uptec-orange: #ff5e00;
    --uptec-green: #18ad72;
}
```

### Espaciado

```css
:root {
    --space-xs: 0.5rem;
    --space-sm: 1rem;
    --space-md: 1.5rem;
    --space-lg: 2rem;
    --space-xl: 3rem;
}
```

### Sombras

```css
:root {
    --shadow-sm: 0 2px 10px rgba(0,0,0,0.1);
    --shadow-md: 0 10px 30px rgba(0,0,0,0.2);
    --shadow-lg: 0 20px 50px rgba(0,0,0,0.3);
}
```

---

## Responsive Design

### Breakpoints

| Breakpoint | Descripción |
|------------|-------------|
| `< 768px` | Mobile - Sidebar colapsado, tablas scrollables |
| `768px - 1024px` | Tablet - Sidebar compacto |
| `> 1024px` | Desktop - Layout completo |

### Patrón Mobile-First

```css
/* Base: Mobile */
.sidebar { display: none; }

/* Tablet */
@media (min-width: 768px) {
    .sidebar { display: block; width: 200px; }
}

/* Desktop */
@media (min-width: 1024px) {
    .sidebar { width: 260px; }
}
```

---

## Accesibilidad

- **Contraste**: Todos los textos cumplen WCAG 2.1 AA
- **Navegación por teclado**: Tabindex en formularios
- **ARIA labels**: En botones e iconos
- **Reduced motion**: `@media (prefers-reduced-motion)`

---

## Performance

| Optimización | Implementación |
|--------------|----------------|
| **Sin frameworks** | Carga rápida, zero dependencies |
| **CSS en archivos** | Cacheable, sin inline styles masivos |
| **Lazy loading** | Intersection Observer para animaciones |
| **Fetch API** | Requests asíncronos sin recarga |
| **Minificación** | Recomendado para producción |
