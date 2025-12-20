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
];
