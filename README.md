🏕️ Sistema de Reservas - Sitio Campestre Oasis
🚀 Instalación Rápida
1. Instalar dependencias
bashcomposer install
2. Configurar variables de entorno
⚠️ IMPORTANTE: Renombrar env a .env
bash# Windows:
ren env .env

# Linux/Mac:
mv env .env
Editar .env con tus datos de MySQL:
envDB_DATABASE=nombre_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
3. Configurar aplicación
bashphp artisan key:generate
php artisan migrate --seed
php artisan storage:link
4. Iniciar servidor
bashphp artisan serve
Acceder a: http://localhost:8000
👥 Credenciales
Admin: admin@campestre.com / password
Cliente: cliente@campestre.com / password
