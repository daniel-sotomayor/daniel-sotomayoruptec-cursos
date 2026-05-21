# Guía de Instalación

## UPTEC Cursos v2.0 - Instalación en XAMPP (Windows)

---

## Requisitos del Sistema

### Software Requerido

| Componente | Versión Mínima | Descargar |
|------------|----------------|-----------|
| XAMPP | 7.4+ (PHP 8.0+) | https://www.apachefriends.org |
| PHP | 8.0 o superior | Incluido en XAMPP |
| MySQL | 5.7 o superior | Incluido en XAMPP |
| Apache | 2.4+ | Incluido en XAMPP |
| Navegador | Chrome/Firefox/Edge | Actualizado |

### Requisitos de Hardware

| Componente | Mínimo | Recomendado |
|------------|--------|-------------|
| RAM | 2 GB | 4 GB |
| Disco | 500 MB libres | 1 GB libres |
| Procesador | Dual-core | Quad-core |

---

## Instalación Paso a Paso

### Paso 1: Instalar XAMPP

1. Descargar XAMPP desde https://www.apachefriends.org
2. Ejecutar el instalador como administrador
3. Seleccionar los componentes:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
4. Elegir carpeta de instalación (por defecto: `C:\xampp`)
5. Finalizar instalación

### Paso 2: Iniciar Servicios

1. Abrir **XAMPP Control Panel**
2. Click en **Start** para Apache
3. Click en **Start** para MySQL
4. Verificar que ambos servicios muestren el color verde

```
┌─────────────────────────────────────┐
│        XAMPP Control Panel          │
├─────────────────────────────────────┤
│  Module     │  PID   │  Status      │
│  Apache     │  1234  │  🟢 Running  │
│  MySQL      │  5678  │  🟢 Running  │
└─────────────────────────────────────┘
```

### Paso 3: Copiar Archivos del Proyecto

1. Descomprimir el archivo `uptec-cursos.zip`
2. Copiar la carpeta `uptec-cursos` completa a:
   ```
   C:\xampp\htdocs\
   ```
3. La ruta final debe ser:
   ```
   C:\xampp\htdocs\uptec-cursos\
   ```

**Estructura esperada:**
```
C:\xampp\htdocs\uptec-cursos\
├── backend\
├── db\
├── frontend\
└── documentacion\
```

### Paso 4: Crear la Base de Datos

#### Opción A: Usando phpMyAdmin (Recomendado)

1. Abrir navegador y visitar: `http://localhost/phpmyadmin`
2. Click en **Nueva** (pestaña superior)
3. Ingresar nombre de base de datos: `uptec_cursos`
4. Seleccionar cotejamiento: `utf8mb4_unicode_ci`
5. Click en **Crear**

#### Opción B: Usando MySQL CLI

```bash
cd C:\xampp\mysql\bin
mysql -u root -p
```

```sql
CREATE DATABASE uptec_cursos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE uptec_cursos;
```

### Paso 5: Importar Esquema y Datos

#### Usando phpMyAdmin:

1. Seleccionar base de datos `uptec_cursos` (click en el nombre)
2. Click en pestaña **Importar**
3. Click en **Seleccionar archivo**
4. Seleccionar: `C:\xampp\htdocs\uptec-cursos\db\schema.sql`
5. Click en **Continuar** (abajo de todo)
6. Repetir para `seed.sql`

#### Usando MySQL CLI:

```bash
cd C:\xampp\htdocs\uptec-cursos\db

# Importar esquema
C:\xampp\mysql\bin\mysql -u root -p uptec_cursos < schema.sql

# Importar datos iniciales
C:\xampp\mysql\bin\mysql -u root -p uptec_cursos < seed.sql
```

**Nota:** Si MySQL no tiene contraseña, omitir `-p`

### Paso 6: Configurar Base de Datos (Opcional)

Si tu MySQL tiene configuración diferente (usuario/contraseña), editar:

**Archivo:** `C:\xampp\htdocs\uptec-cursos\backend\db\config.php`

```php
private string $host = 'localhost';
private string $database = 'uptec_cursos';
private string $username = 'root';    // Tu usuario MySQL
private string $password = '';        // Tu contraseña MySQL
private string $charset = 'utf8mb4';
```

**Configuración por defecto de XAMPP:**
- Usuario: `root`
- Contraseña: (vacía)
- Host: `localhost`

### Paso 7: Verificar Instalación

1. Abrir navegador
2. Visitar: `http://localhost/uptec-cursos/frontend/`
3. Debe mostrar la landing page de UPTEC

**URLs de acceso:**

| Descripción | URL |
|-------------|-----|
| Landing Page | `http://localhost/uptec-cursos/frontend/` |
| Login | `http://localhost/uptec-cursos/frontend/login.html` |
| Panel Admin | `http://localhost/uptec-cursos/frontend/views/administrador/dashboard.html` |
| Panel Analista | `http://localhost/uptec-cursos/frontend/views/analista/dashboard.html` |
| Panel Facilitador | `http://localhost/uptec-cursos/frontend/views/facilitador/dashboard.html` |
| Panel Participante | `http://localhost/uptec-cursos/frontend/views/participante/dashboard.html` |
| phpMyAdmin | `http://localhost/phpmyadmin` |

---

## Usuarios de Prueba

| Rol | Cédula | Correo | Contraseña |
|-----|--------|--------|------------|
| **Administrador** | V12345678 | admin@uptec.edu.ve | admin123 |
| **Analista** | V87654321 | analista@uptec.edu.ve | analista123 |
| **Facilitador** | V11111111 | jperez@uptec.edu.ve | facilitador123 |
| **Facilitador** | V22222222 | mlopez@uptec.edu.ve | facilitador123 |

**Para participantes:** Registrarse en `login.html#register`

---

## Solución de Problemas

### Error: "Error al conectar con la base de datos"

**Causas posibles:**
1. MySQL no está iniciado
2. Credenciales incorrectas
3. Base de datos no existe

**Solución:**
```bash
# Verificar MySQL está corriendo
# XAMPP Control Panel → MySQL → Start

# Verificar base de datos existe
mysql -u root -e "SHOW DATABASES;"

# Recrear base de datos si es necesario
mysql -u root -e "DROP DATABASE IF EXISTS uptec_cursos;"
mysql -u root -e "CREATE DATABASE uptec_cursos CHARACTER SET utf8mb4;"
```

### Error: "Acceso denegado" / "403 Forbidden"

**Causa:** Permisos de Apache

**Solución:**
1. Verificar que la carpeta está en `C:\xampp\htdocs\`
2. Reiniciar Apache desde XAMPP Control Panel

### Error: "CSRF Token inválido"

**Causa:** Sesión expirada o cookies bloqueadas

**Solución:**
1. Limpiar cookies del navegador
2. Recargar la página
3. Cerrar y abrir navegador

### Error: "Endpoint no encontrado" (404)

**Causas posibles:**
1. URL incorrecta
2. Mod rewrite no habilitado

**Solución:**
```
Verificar URL: http://localhost/uptec-cursos/backend/api/api.php?endpoint=login
```

### Error de caracteres (tildes, ñ)

**Causa:** Configuración de charset

**Verificación:**
```sql
-- En phpMyAdmin, ejecutar:
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';

-- Deben mostrar utf8mb4
```

---

## Configuración para Producción

### 1. Cambiar Contraseñas de Usuarios de Prueba

```sql
-- Generar nuevo hash con bcrypt cost 12
-- Actualizar en tabla usuarios
UPDATE usuarios SET password = '$2b$12$...' WHERE id = 1;
```

### 2. Configurar HTTPS

1. Obtener certificado SSL (Let's Encrypt, etc.)
2. Configurar Apache para usar HTTPS
3. Forzar redirección HTTP → HTTPS

### 3. Configurar Session Security

**En `backend/security/auth.php`:**
```php
ini_set('session.cookie_secure', '1');  // Requiere HTTPS
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
```

### 4. Cambiar Puerto de MySQL (Opcional)

**Si se requiere MySQL en puerto diferente:**

1. Editar `C:\xampp\mysql\bin\my.ini`
2. Cambiar `port=3306` al puerto deseado
3. Actualizar `backend/db/config.php`

### 5. Backup Automático

**Script de backup (Windows):**
```batch
@echo off
set FECHA=%date:~-4,4%%date:~-10,2%%date:~-7,2%
C:\xampp\mysql\bin\mysqldump -u root uptec_cursos > C:\backups\uptec_cursos_%FECHA%.sql
```

---

## Estructura de Carpetas Final

```
C:\xampp\htdocs\uptec-cursos\
├── backend\
│   ├── api\
│   │   ├── api.php
│   │   ├── auth.php
│   │   ├── usuarios.php
│   │   ├── cursos.php
│   │   ├── inscripciones.php
│   │   ├── calificaciones.php
│   │   ├── reportes.php
│   │   └── backup.php
│   ├── security\
│   │   ├── auth.php
│   │   ├── csrf.php
│   │   ├── sanitizer.php
│   │   └── validator.php
│   └── db\
│       └── config.php
├── db\
│   ├── schema.sql
│   └── seed.sql
├── documentacion\
│   ├── README.md
│   ├── 01-arquitectura.md
│   ├── 02-base-de-datos.md
│   ├── 03-api-endpoints.md
│   ├── 04-seguridad.md
│   ├── 05-roles-permisos.md
│   ├── 06-instalacion.md
│   └── 07-frontend.md
└── frontend\
    ├── index.html
    ├── login.html
    ├── css\
    │   ├── main.css
    │   └── login.css
    ├── js\
    │   ├── api.js
    │   ├── auth.js
    │   └── dashboard.js
    └── views\
        ├── administrador\
        │   ├── dashboard.html
        │   ├── usuarios.html
        │   ├── cursos.html
        │   ├── inscripciones.html
        │   ├── calificaciones.html
        │   ├── reportes.html
        │   ├── auditoria.html
        │   └── backup.html
        ├── analista\
        │   └── cursos.html
        ├── facilitador\
        │   ├── dashboard.html
        │   ├── calificaciones.html
        │   ├── estudiantes.html
        │   ├── mis-cursos.html
        │   └── reportes.html
        └── participante\
            ├── dashboard.html
            ├── mis-cursos.html
            ├── mis-notas.html
            └── inscribir.html
```

---

## Comandos Útiles

### Reiniciar Servicios

```bash
# XAMPP Control Panel
Stop Apache → Start Apache
Stop MySQL → Start MySQL
```

### Ver Logs

| Log | Ubicación |
|-----|-----------|
| Apache Error | `C:\xampp\apache\logs\error.log` |
| MySQL Error | `C:\xampp\mysql\data\mysql_error.log` |
| PHP Error | `C:\xampp\php\logs\php_error_log` |

### Acceso Rápido a Configuraciones

| Archivo | Ubicación |
|---------|-----------|
| PHP Config | `C:\xampp\php\php.ini` |
| Apache Config | `C:\xampp\apache\conf\httpd.conf` |
| MySQL Config | `C:\xampp\mysql\bin\my.ini` |

---

## Soporte

Para reportar problemas o consultas:

1. Revisar logs de Apache y MySQL
2. Verificar configuración en `config.php`
3. Consultar documentación técnica en `/documentacion/`

---

**¡Instalación completada!** El sistema está listo para usar.
