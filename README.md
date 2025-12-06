# 🏕️ Sistema de Reservas - Sitio Campestre Oasis

Sistema web completo para gestión de reservas de espacios, pedidos de productos y administración de recursos para un sitio campestre.

## 🚀 Instalación Rápida

### 1. Instalar dependencias
```bash
composer install
```

### 2. Configurar variables de entorno

**⚠️ IMPORTANTE:** Renombrar `env` a `.env`

**Windows:**
```bash
ren env .env
```

**Linux/Mac:**
```bash
mv env .env
```

Editar `.env` con tus datos de MySQL:
```env
DB_DATABASE=nombre_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 3. Configurar aplicación
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 4. Iniciar servidor
```bash
php artisan serve
```

Acceder a: **http://localhost:8000**

---

## 👥 Credenciales de Acceso

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Administrador** | admin@campestre.com | password |
| **Cliente** | cliente@campestre.com | password |

---

## 📦 Funcionalidades Principales

### 🔧 Panel Administrador
- Gestión de espacios físicos
- Gestión de productos y stock separado
- Gestión de servicios extras
- Gestión de horarios y mesas
- Aprobación/rechazo de reservas
- Gestión de pedidos (cambio de estados)
- Dashboard con estadísticas

### 👤 Panel Cliente
- Reserva de múltiples espacios con horarios diferentes
- Selección de servicios adicionales
- Carrito de compras para productos
- Métodos de pago: Efectivo, Yape, Tarjeta
- Visualización de historial de reservas y pedidos
- Asociar pedidos a reservas activas

---

## 🛠️ Tecnologías Utilizadas

- **Framework:** Laravel 12
- **Base de Datos:** MySQL
- **Frontend:** Bootstrap 5 + CSS Custom
- **Autenticación:** Sistema personalizado
- **Diseño:** Responsive (móvil, tablet, desktop)

---

## 📝 Notas Adicionales

### QR de Yape
Para mostrar tu código QR de Yape en los pagos, coloca la imagen en:
```
public/images/qr-yape.png
```

### Gestión de Stock
El stock de productos se gestiona en un módulo separado del CRUD principal, según requerimiento del proyecto.

### Reservas Múltiples
El sistema permite seleccionar varios espacios físicos, cada uno con su propio horario, calculando automáticamente los costos.

---

## 🐛 Solución de Problemas Comunes

### Error de permisos
```bash
chmod -R 775 storage bootstrap/cache
```

### Resetear base de datos
```bash
php artisan migrate:fresh --seed
```

### Limpiar caché
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

**Desarrollado con Laravel 12** 💙
