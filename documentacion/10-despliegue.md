# Manual de Despliegue

## Objetivo

Proveer una guía clara para desplegar UPTEC Cursos en un entorno de producción o desarrollo local.

## Requisitos

- PHP 8.0 o superior
- Extensión PDO MySQL activa
- MySQL 5.7 o superior
- Servidor web Apache o Nginx, o PHP Built-in Server para pruebas
- Carpeta `frontend/` disponible como contenido estático
- Permisos de lectura para archivos HTML, CSS, JS y PHP

## Estructura recomendada

El proyecto debe colocarse en el directorio raíz del servidor web. La estructura mínima es:

```
/var/www/uptec-cursos/
├── backend/
├── db/
├── documentacion/
├── frontend/
└── README.md
```

## Configuración del servidor

### Apache

1. Crear un VirtualHost apuntando a la carpeta del proyecto.
2. Asegurarse de que `AllowOverride None` y que la carpeta tenga permisos de lectura.
3. Reiniciar Apache.

Ejemplo mínimo:

```apache
<VirtualHost *:80>
    ServerName uptec-cursos.local
    DocumentRoot "/var/www/uptec-cursos/frontend"
    <Directory "/var/www/uptec-cursos/frontend">
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

1. Configurar un server block apuntando a `frontend/`.
2. Usar `try_files $uri $uri/ =404;` para servir archivos estáticos.

Ejemplo mínimo:

```nginx
server {
    listen 80;
    server_name uptec-cursos.local;
    root /var/www/uptec-cursos/frontend;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }
}
```

## Base de datos

1. Crear la base de datos en MySQL:

```sql
CREATE DATABASE uptec_cursos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importar el esquema y los datos iniciales:

```bash
mysql -u root -p uptec_cursos < db/schema.sql
mysql -u root -p uptec_cursos < db/seed.sql
```

3. Ajustar las credenciales en `backend/db/config.php`.

## Ajustes de conexión

En `backend/db/config.php`, configurar el host, usuario, contraseña y nombre de la base de datos.

```php
private string $host = 'localhost';
private string $dbName = 'uptec_cursos';
private string $username = 'root';
private string $password = 'tu_password';
```

## Prueba de despliegue

1. Abrir el navegador en `http://uptec-cursos.local/index.html` o en la URL configurada.
2. Iniciar sesión con un usuario de prueba o registrarse como participante.
3. Verificar que las vistas se cargan correctamente y que la API responde.

## Despliegue local rápido

Para pruebas sin servidor web, se puede usar el servidor integrado de PHP desde la raíz del proyecto:

```bash
cd /workspaces/daniel-sotomayoruptec-cursos
php -S localhost:8000 -t frontend
```

Luego abrir `http://localhost:8000/index.html`.

## Notas adicionales

- En un entorno real, asegúrese de deshabilitar el listado de directorios.
- Configure HTTPS si el sistema se expone en producción.
- Realice copias de seguridad regulares de `db/schema.sql` y de la base de datos.
