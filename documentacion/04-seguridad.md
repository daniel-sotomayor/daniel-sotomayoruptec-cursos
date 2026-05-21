# Seguridad

## UPTEC Cursos v2.0 - Documentación de Seguridad

---

## Visión General

El sistema implementa las mejores prácticas de seguridad OWASP (Open Web Application Security Project) para proteger contra las vulnerabilidades más comunes en aplicaciones web.

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPAS DE SEGURIDAD                       │
├─────────────────────────────────────────────────────────────┤
│ Layer 1: Transporte        → HTTPS (configurado en server) │
│ Layer 2: CORS/Headers      → Headers de seguridad HTTP      │
│ Layer 3: Autenticación     → Sesiones + Bcrypt              │
│ Layer 4: Autorización      → Roles y permisos               │
│ Layer 5: CSRF Protection   → Tokens synchronizer pattern    │
│ Layer 6: Input Validation  → Validator class                │
│ Layer 7: Output Encoding   → Sanitizer (XSS prevention)     │
│ Layer 8: SQL Injection     → PDO Prepared Statements        │
│ Layer 9: Logging           → Auditoría completa             │
└─────────────────────────────────────────────────────────────┘
```

---

## 1. CSRF Protection (Cross-Site Request Forgery)

**Archivo:** `backend/security/csrf.php`

### Implementación: Synchronizer Token Pattern

```php
class CSRF {
    private const TOKEN_NAME = '_csrf_token';
    private const TOKEN_LIFETIME = 3600; // 1 hora
    
    public static function generateToken(): string {
        $token = bin2hex(random_bytes(32)); // 64 caracteres hex
        $_SESSION[self::TOKEN_NAME] = [
            'token' => $token,
            'time' => time()
        ];
        return $token;
    }
    
    public static function validate(?string $token = null): bool {
        // hash_equals previene timing attacks
        return hash_equals($_SESSION[self::TOKEN_NAME]['token'], $token);
    }
}
```

### Uso en Frontend

```javascript
// api.js - Cliente HTTP
async function fetchCsrfToken() {
    const response = await fetch(`${API_BASE}?endpoint=csrf`);
    const data = await response.json();
    csrfToken = data.token;
}

// Toda petición incluye el token
async post(endpoint, data) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()  // Header personalizado
        },
        body: JSON.stringify(data)
    });
}
```

### Cobertura

| Operación | Protección CSRF |
|-----------|-----------------|
| POST | ✅ Requerido |
| PUT | ✅ Requerido |
| DELETE | ✅ Requerido |
| GET | ❌ No aplica |

---

## 2. XSS Prevention (Cross-Site Scripting)

**Archivo:** `backend/security/sanitizer.php`

### Estrategia: Output Encoding

```php
class Sanitizer {
    // Para contenido HTML
    public static function html(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    // Para atributos HTML
    public static function attr(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    // Para JavaScript
    public static function js(string $text): string {
        return str_replace(['\\', "'", '"', '\n', '\r', '&', '<', '>', '/'],
                           ['\\\\', "\\'", '\\"', '\\n', '\\r', '\\x26', '\\x3c', '\\x3e', '\\x2f'],
                           $text);
    }
    
    // Para emails
    public static function email(string $email): string {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
```

### Tipos de XSS Mitigados

| Tipo | Prevención |
|------|------------|
| **Reflejado** | Sanitización de todos los inputs |
| **Almacenado** | Escapado de salida en base de datos |
| **DOM-based** | No se usa innerHTML con datos dinámicos |

### Cobertura de Contextos

| Contexto | Función | Ejemplo |
|----------|---------|---------|
| HTML Body | `Sanitizer::html()` | `echo Sanitizer::html($nombre)` |
| Atributo | `Sanitizer::attr()` | `value="<?= Sanitizer::attr($email) ?>"` |
| JavaScript | `Sanitizer::js()` | `const name = '<?= Sanitizer::js($name) ?>'` |
| URL | `filter_var()` | `filter_var($url, FILTER_VALIDATE_URL)` |

---

## 3. SQL Injection Prevention

**Implementación:** PDO Prepared Statements en toda la aplicación.

### Ejemplo de Uso Correcto

```php
// ✅ CORRECTO - Prepared Statement
$stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = ? AND activo = ?");
$stmt->execute([$email, 1]);

// ❌ INCORRECTO - Concatenación
$stmt = $db->query("SELECT * FROM usuarios WHERE correo = '$email'");
```

### Configuración Segura de PDO

```php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,  // ✅ Usar prepared statements reales
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];
```

### Cobertura

| Operación | Protección |
|-----------|------------|
| SELECT | ✅ Prepared statements |
| INSERT | ✅ Prepared statements |
| UPDATE | ✅ Prepared statements |
| DELETE | ✅ Prepared statements |
| LIKE | ✅ Escapado de wildcards: `str_replace(['\\', '%', '_'], ...)` |

---

## 4. Password Security

**Archivo:** `backend/security/auth.php`

### Bcrypt con Cost 12

```php
// Hash de contraseña
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Resultado: $2y$12$...
// 2y = Bcrypt
// 12 = Cost factor (2^12 = 4096 iteraciones)
```

### Verificación y Re-hash

```php
// Verificar
if (!password_verify($password, $user['password'])) {
    // Credenciales inválidas
}

// Re-hash si es necesario (algoritmo mejorado)
if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
    self::updatePassword($user['id'], $password);
}
```

### Requisitos de Contraseña

| Requisito | Validación |
|-----------|------------|
| Longitud mínima | 6 caracteres |
| Contiene letra | `[A-Za-z]` |
| Contiene número | `[0-9]` |
| Confirmación | Debe coincidir |

**Archivo:** `backend/security/validator.php`

```php
public function password(string $password, string $field = 'password'): bool {
    if (strlen($password) < 6) {
        $this->errors[$field] = 'La contrasena debe tener al menos 6 caracteres';
        return false;
    }
    
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $this->errors[$field] = 'Debe contener al menos una letra y un numero';
        return false;
    }
    
    return true;
}
```

---

## 5. Session Security

**Configuración en:** `backend/security/auth.php`

```php
public static function init(): void {
    ini_set('session.cookie_httponly', '1');     // ❌ No accesible por JS
    ini_set('session.cookie_secure', '1');       // 🔒 Solo HTTPS
    ini_set('session.use_strict_mode', '1');     // 🛡️ Prevenir session fixation
    ini_set('session.cookie_samesite', 'Strict'); // 🚫 No enviar en cross-site
    ini_set('session.gc_maxlifetime', '1800');   // ⏱️ 30 minutos
    
    session_start();
    session_regenerate_id(true); // Regenerar ID en login
}
```

### Protección contra Brute Force

```php
private const MAX_LOGIN_ATTEMPTS = 5;
private const LOCKOUT_TIME = 900; // 15 minutos

private static function isLockedOut(): bool {
    $key = '_uptec_login_attempts_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    
    if ($_SESSION[$key]['count'] >= self::MAX_LOGIN_ATTEMPTS) {
        if (time() - $_SESSION[$key]['time'] < self::LOCKOUT_TIME) {
            return true; // 🔒 Cuenta bloqueada
        }
    }
    return false;
}
```

### Inactividad

```php
private const INACTIVITY_LIMIT = 1800; // 30 minutos

private static function checkInactivity(): void {
    if (isset($_SESSION[self::SESSION_LAST_ACTIVITY])) {
        $inactive = time() - $_SESSION[self::SESSION_LAST_ACTIVITY];
        
        if ($inactive > self::INACTIVITY_LIMIT) {
            self::logout();
            header('Location: /login.html?expired=1');
            exit;
        }
    }
}
```

---

## 6. Input Validation

**Archivo:** `backend/security/validator.php`

### Validaciones Implementadas

| Método | Descripción | Regla |
|--------|-------------|-------|
| `email()` | Correo electrónico | `@uptec.edu.ve` requerido para staff |
| `password()` | Contraseña | ≥6 chars, letra+número |
| `name()` | Nombres | 2-100 chars, solo letras y espacios |
| `cedula()` | Cédula venezolana | 6-9 dígitos |
| `telefono()` | Teléfono | Formato 04xxxxxxxx |
| `nota()` | Calificación | 0-20 decimal |
| `rol()` | Rol de usuario | Enum válido |
| `date()` | Fecha | Formato YYYY-MM-DD |

### Ejemplo de Uso

```php
$validator = new Validator();

$validator->email($data['correo'], true); // true = requiere @uptec.edu.ve
$validator->cedula($data['cedula']);
$validator->name($data['nombre'], 'nombre');
$validator->passwordConfirm($data['password'], $data['password_confirm']);

if ($validator->hasErrors()) {
    http_response_code(422);
    return jsonResponse(false, ['errors' => $validator->getErrors()]);
}
```

---

## 7. HTTP Security Headers

**Archivo:** `backend/api/api.php`

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');        // ⛔ No sniff MIME type
header('X-Frame-Options: DENY');                   // 🖼️ No permitir iframes
header('X-XSS-Protection: 1; mode=block');          // 🛡️ Filtro XSS del navegador
header('Referrer-Policy: strict-origin-when-cross-origin'); // 🔗 Controlar referrer
```

---

## 8. Authorization (RBAC)

**Archivo:** `backend/security/auth.php`

### Jerarquía de Roles

```php
private static array $roleHierarchy = [
    'Administrador' => 4,
    'Analista'      => 3,
    'Facilitador'   => 2,
    'Participante'  => 1
];
```

### Verificación de Permisos

```php
// Verificar rol específico
public static function hasRole($roles): bool {
    $userLevel = self::$roleHierarchy[$userRole] ?? 0;
    $requiredLevel = self::$roleHierarchy[$roles] ?? 0;
    return $userLevel >= $requiredLevel;
}

// Helpers
public static function isAdmin(): bool;
public static function isAnalystOrAbove(): bool;
public static function isTeacherOrAbove(): bool;
```

### Middleware de Autorización

```php
function requireAdmin(): callable {
    return function() {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Se requiere rol Administrador']);
            return false;
        }
        return true;
    };
}
```

---

## 9. Audit Logging

**Archivo:** `backend/security/auth.php`

### Toda Actividad Registrada

```php
public static function logActivity(
    ?int $userId, 
    string $userName, 
    string $action,      // CREATE, UPDATE, DELETE, LOGIN, LOGOUT...
    string $table, 
    ?int $recordId,
    ?array $datosAnteriores = null,
    ?array $datosNuevos = null
): void {
    $stmt = $db->prepare("INSERT INTO logs_actividad (...) VALUES (...)");
    $stmt->execute([
        $userId, $userName, $action, $table, $recordId,
        json_encode($datosAnteriores),
        json_encode($datosNuevos),
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}
```

### Datos Capturados

| Campo | Descripción |
|-------|-------------|
| `usuario_id` | ID del usuario que realizó la acción |
| `usuario_nombre` | Nombre completo |
| `accion` | Tipo: LOGIN, LOGOUT, CREATE, UPDATE, DELETE |
| `tabla_afectada` | Tabla modificada |
| `registro_id` | ID del registro afectado |
| `datos_anteriores` | JSON con valores antes del cambio |
| `datos_nuevos` | JSON con valores después del cambio |
| `ip_address` | IP del cliente |
| `user_agent` | Navegador/dispositivo |
| `fecha_hora` | Timestamp |

---

## 10. File Uploads (No implementado)

El sistema actualmente **no permite subida de archivos**, eliminando riesgos de:
- Malware upload
- Path traversal
- File inclusion vulnerabilities

---

## Resumen de Medidas OWASP

| Riesgo | Mitigación | Archivo |
|--------|------------|---------|
| A01: Broken Access Control | RBAC + Middleware | `auth.php` |
| A02: Cryptographic Failures | Bcrypt + HTTPS | `auth.php` |
| A03: Injection | PDO Prepared Statements | Todos los endpoints |
| A04: Insecure Design | Validación estricta | `validator.php` |
| A05: Security Misconfiguration | Headers de seguridad | `api.php` |
| A06: Vulnerable Components | Zero-dependency | - |
| A07: Auth Failures | Sesiones seguras + bloqueo | `auth.php` |
| A08: Data Integrity | CSRF Tokens | `csrf.php` |
| A09: Logging Failures | Auditoría completa | `auth.php` |
| A10: SSRF | No requests a URLs externas | - |

---

## Recomendaciones para Producción

1. **HTTPS obligatorio** - Configurar certificado SSL/TLS
2. **Rate limiting** - Implementar límite de requests por IP
3. **WAF** - Considerar Web Application Firewall (Cloudflare, mod_security)
4. **Backups** - Respaldos automáticos diarios de la BD
5. **Monitoreo** - Alertas de intentos de login fallidos
6. **Updates** - Mantener PHP y MySQL actualizados
