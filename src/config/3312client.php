<?php

return [
    'url' => env('SOPORTE_URL', 'https://url.com'),
    'identificador_unico' => env('SOPORTE_IDENTIFICADOR_UNICO', 'XXXX'),
    'token' => env('SOPORTE_TOKEN', 'XXXX'),
    'bootstrap' => 5,
    'layoutpath' => '3312client::bootstrap5.layouts.3312client',
    // Ruta de retorno al sistema principal (puede ser nombre de ruta o URL)
    // Ejemplo: 'dashboard' o 'admin.dashboard' o '/dashboard'
    'back_route' => env('SOPORTE_BACK_ROUTE', null),

    /*
    |--------------------------------------------------------------------------
    | Autenticación
    |--------------------------------------------------------------------------
    |
    | Configuración del guard y modelo autenticable que utiliza el package.
    |
    | 'auth_guard' => null usa el guard por defecto de Laravel (normalmente 'web').
    | Puede cambiarse a 'admin', 'api', o cualquier guard definido en config/auth.php.
    |
    | 'auth_middleware' => El middleware de autenticación a aplicar en las rutas.
    | Ejemplos: 'auth', 'auth:admin', 'auth:api'
    |
    | 'user_fields' => Mapeo de los campos del modelo autenticable.
    | Permite adaptar el package a cualquier modelo (User, Admin, Cliente, etc.)
    | sin importar cómo se llamen sus columnas.
    |
    */
    'auth_guard' => env('SOPORTE_AUTH_GUARD', null),
    'auth_middleware' => env('SOPORTE_AUTH_MIDDLEWARE', 'auth'),
    'user_fields' => [
        'name' => 'name',
        'lastname' => 'lastname',
        'email' => 'email',
    ],
];
