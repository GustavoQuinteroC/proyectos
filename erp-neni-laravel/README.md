# erp-neni-laravel

## Descripción General

`erp-neni-laravel` es un sistema ERP desarrollado en Laravel para la gestión de almacenes, productos, ventas, entregas, recepciones y puntos de entrega. El sistema está diseñado para facilitar la administración de inventarios, usuarios y operaciones logísticas, integrando funcionalidades modernas y una interfaz amigable basada en AdminLTE.

## Arquitectura del Proyecto

La arquitectura del proyecto sigue la estructura estándar de Laravel, separando la lógica de negocio, las vistas, las rutas y la configuración. A continuación se describe la función de cada subcarpeta principal:

---

### app/

Contiene la lógica principal de la aplicación, incluyendo controladores, modelos, políticas y proveedores.

- **Console/**: Comandos personalizados de Artisan.
- **Exceptions/**: Manejo de excepciones personalizadas.
- **Http/**: Controladores, middleware y requests.
- **Models/**: Modelos Eloquent para la base de datos.
- **Policies/**: Políticas de autorización.
- **Providers/**: Proveedores de servicios de Laravel.

---

### bootstrap/

Incluye archivos de arranque y configuración inicial de la aplicación.

- **app.php**: Inicialización de la aplicación Laravel.
- **cache/**: Archivos de caché generados por el framework.

---

### config/

Archivos de configuración de la aplicación y servicios externos (base de datos, correo, archivos, etc.).

---

### database/

Migraciones, seeders y factories para la gestión de la base de datos.

---

### lang/

Archivos de localización y traducción para varios idiomas.

---

### public/

Archivos accesibles públicamente como assets, imágenes, scripts y hojas de estilo.

---

### resources/

Recursos de la aplicación, incluyendo vistas Blade, archivos de idioma y assets sin compilar.

- **views/**: Vistas Blade organizadas por módulos del sistema.
- **lang/**: Archivos de traducción adicionales.
- **js/**, **css/**: Recursos front-end.

---

### routes/

Definición de rutas web, API y consola.

---

### storage/

Archivos generados y almacenados por la aplicación, como logs, caché y archivos subidos.

---

### tests/

Pruebas automatizadas del sistema.

---

### vendor/

Dependencias instaladas por Composer.

---

## Instalación y Uso

1. Clona el repositorio.
2. Instala las dependencias con `composer install` y `npm install`.
3. Configura el archivo `.env` con tus credenciales.
4. Ejecuta las migraciones con `php artisan migrate`.
5. Inicia el servidor con `php artisan serve`.

---

## Contribución

Las contribuciones son bienvenidas. Por favor, abre un issue o pull request para sugerencias o mejoras.

---