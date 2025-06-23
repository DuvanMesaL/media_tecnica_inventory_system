
# 🎒 Media Técnica Inventory System

Sistema de gestión de inventario desarrollado por estudiantes y docentes del programa de media técnica en sistemas de la Institución Educativa Bertha Suttner. Diseñado para optimizar el préstamo y control de herramientas usadas en clases técnicas en distintos salones y almacenes.

---

### ✨ Características Destacadas

- 📖 **Documentación completa** de todas las funcionalidades
- 🚀 **Guía de instalación paso a paso** con comandos específicos
- ⚙️ **Configuración detallada** de Brevo SMTP y variables de entorno
- 👥 **Roles y permisos** explicados claramente por tipo de usuario
- 📊 **API documentation** con ejemplos de uso `curl`
- 🛠️ **Comandos Artisan** personalizados para mantenimiento
- 🚀 **Guía de despliegue** en producción con Apache/Nginx
- 🔒 **Medidas de seguridad** implementadas y recomendaciones
- 🐛 **Solución de problemas** comunes con comandos específicos
- 🎯 **Roadmap** con versiones futuras planificadas

---

### 🎯 Secciones Incluidas

1. **Introducción** con características principales
2. **Tecnologías** utilizadas
3. **Requisitos** del sistema
4. **Instalación** completa paso a paso
5. **Roles y permisos** detallados
6. **Guía de uso** por tipo de usuario
7. **API endpoints** con ejemplos
8. **Comandos** de mantenimiento
9. **Monitoreo** y logs
10. **Despliegue** en producción
11. **Seguridad** y mejores prácticas
12. **Solución de problemas**
13. **Soporte** y contribución
14. **Roadmap** futuro

---

### 🧪 Tecnologías utilizadas

- PHP 8.2 (Laravel)
- MySQL
- TailwindCSS
- Blade
- JavaScript
- Brevo SMTP
- Laravel UI

---

### 🛠 Requisitos del sistema

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM
- Servidor local (como Laragon, XAMPP o Docker)

---

### 📦 Instalación

```bash
git clone https://github.com/usuario/inventory-system.git
cd inventory-system
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

### ⚙️ Configuración Brevo SMTP

En tu `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@dominio.com
MAIL_PASSWORD=clave_brevo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_correo@dominio.com
MAIL_FROM_NAME="Sistema de Inventario"
```

---

### 👥 Roles y Permisos

- **Administrador**: gestión completa de usuarios, almacenes, herramientas y préstamos.
- **Logística**: controla el inventario y valida préstamos.
- **Docente**: solicita herramientas para sus clases.
- **Estudiante**: visualiza préstamos y estado de herramientas.

---

### 📘 Guía de Uso

1. Inicia sesión según tu rol.
2. Administra herramientas desde el panel correspondiente.
3. Solicita o aprueba préstamos con un solo clic.
4. Usa el panel de reportes para seguimiento.

---

### 📡 API Endpoints (ejemplo)

```bash
curl -X POST http://localhost/api/login -d 'email=admin@admin.com&password=admin'
```

Más documentación en `/routes/api.php`

---

### ⚙️ Comandos Artisan personalizados

```bash
php artisan user:promote {email} {role}
php artisan clean:logs
```

---

### 📈 Monitoreo y Logs

- Laravel Log: `storage/logs/laravel.log`
- Sesiones en base de datos: tabla `sessions`

---

### 🚀 Despliegue en producción

1. Configura Apache/Nginx apuntando a `/public`
2. Activa HTTPS
3. Crea cron jobs si se usan tareas programadas
4. Configura permisos de carpetas `storage/` y `bootstrap/cache/`

---

### 🔒 Seguridad

- Hash de contraseñas con BCRYPT
- Verificación de roles
- Protección CSRF
- Validación de entrada

---

### 🧩 Solución de Problemas

```bash
php artisan migrate:fresh --seed
composer dump-autoload
php artisan config:clear
php artisan route:clear
```

---

### 🤝 Soporte y Contribución

Este proyecto es educativo. Si deseas contribuir, abre un Pull Request.

---
