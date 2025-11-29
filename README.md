# Instrucciones de Instalación y Ejecución

## Requisitos previos
- PHP 8.2 o superior
- Composer
- MySQL o MariaDB
- Servidor web (Apache/Nginx) o usar el servidor integrado de Laravel

## Pasos para ejecutar el proyecto

### 1. Clonar el repositorio
```bash
git clone [url-del-repositorio]
cd nombre-del-proyecto
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar el archivo de entorno
```bash
cp .env.example .env
```

Luego edita el archivo `.env` y configura la conexión a tu base de datos:
```
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar la clave de la aplicación
```bash
php artisan key:generate
```

### 5. Crear el enlace simbólico para las imágenes
```bash
php artisan storage:link
```

### 6. Crear la base de datos
Crea manualmente una base de datos en MySQL con el nombre que pusiste en el archivo `.env`

### 7. Ejecutar las migraciones
```bash
php artisan migrate
```

### 8. Iniciar el servidor
```bash
php artisan serve
```

El proyecto estará disponible en: http://127.0.0.1:8000

## Usuarios de prueba

Puedes crear usuarios desde el registro o crear uno manualmente en la base de datos:

**Para crear un administrador:**
- Registra un usuario normal
- En la base de datos, cambia el campo `role` de 'cliente' a 'admin' en la tabla `users`

**Para crear un cliente:**
- Simplemente registra un usuario desde el formulario de registro

## Estructura de acceso

- **Administrador:** /admin/dashboard
- **Cliente:** /cliente/home

## Notas adicionales

- Las imágenes se guardan en la carpeta `public/storage`
- Asegúrate de tener los permisos correctos en las carpetas `storage` y `bootstrap/cache`
- Si tienes problemas con permisos en Linux/Mac, ejecuta:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

## Solución de problemas comunes

**Error de conexión a la base de datos:**
- Verifica que los datos en el archivo `.env` sean correctos
- Asegúrate de que el servidor MySQL esté corriendo

**Las imágenes no se muestran:**
- Verifica que ejecutaste `php artisan storage:link`
- Comprueba que la carpeta `public/storage` existe

**Error 500 al cargar la página:**
- Ejecuta `php artisan config:clear`
- Ejecuta `php artisan cache:clear`
