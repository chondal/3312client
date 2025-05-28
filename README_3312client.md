# 📦 3312 Client - Laravel Package

Un paquete Laravel para la gestión de tickets de soporte, adaptable a Bootstrap 4 o 5 para uso exclusivo de dvs360, con vistas publicables y soporte para editor de texto enriquecido.

---

## 🚀 Instalación

Para instalar el paquete 3312client usando Composer, sigue estos pasos:

1. Instalar vía Composer
   
   ```
   composer require chondal/3312client
   ```
2. Requisitos del Sistema
   
   - PHP 8.0 o superior
   - Laravel 9.x o 10.x
El paquete se registrará automáticamente en tu aplicación Laravel a través del service provider Chondal\TicketSoporte\Client3312ServiceProvider .

No se requiere ninguna configuración adicional para comenzar a usar el paquete, ya que Laravel detectará automáticamente el service provider gracias a la configuración del extra.laravel.providers en el composer.json.

---

## ⚙️ Publicación de recursos

Publicar la configuración y vistas:

```bash
php artisan vendor:publish --provider="Chondal\TicketSoporte\Client3312ServiceProvider" --tag=config
php artisan vendor:publish --provider="Chondal\TicketSoporte\Client3312ServiceProvider" --tag=views
```

---

## 🔧 Configuración

En `config/3312client.php` definí:

```php
return [
    'url' => 'https://tu-api-de-soporte.com',
    'identificador_unico' => 'TU-ID-UNICO',
    'token' => 'TU-TOKEN-DE-AUTENTICACION',
    'bootstrap' => 5, // o 4
    'layoutpath' => 'layouts.app', // Ruta del layout base a extender
];
```

---

## 🧩 Uso

1. Las rutas estarán bajo `/soporte`, protegidas por `web` y `auth`.
2. Vistas disponibles: `index`, `show`, `modal-soporte` y `formulario-soporte`.
3. El layout usado es el configurado en `layoutpath`.
4. El formulario incluye Quill.js como editor de texto enriquecido, ya incluido por CDN.
5. si desea incluir el modal de soporte en cualquier vista, puede usar:
```blade
<x-formulario-soporte />
```

---

## 📄 Componentes disponibles

```blade
<x-formulario-soporte />
```

---

## 🛠️ Servicio

El `TicketService` se configura automáticamente desde el archivo de configuración y permite:

- Crear, responder y cerrar tickets
- Ver listado y detalles
- Obtener tipos de ticket

---

¡Listo para usar! 🎉