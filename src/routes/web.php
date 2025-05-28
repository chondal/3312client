<?php

use Illuminate\Support\Facades\Route;
use Chondal\TicketSoporte\Controllers\TicketController;

Route::middleware(['web', 'auth'])->prefix('soporte')->name('soporte.')->group(function () {
    Route::get('/tickets/listado', [TicketController::class, 'index'])->name('index');
    Route::get('/ticket/{ticket}', [TicketController::class, 'show'])->name('show');
    Route::post('/crear', [TicketController::class, 'store'])->name('store');
    Route::post('/responder/{ticket}', [TicketController::class, 'responder'])->name('responder');
    Route::post('/cerrar/{ticket}', [TicketController::class, 'cerrar'])->name('cerrar');
});
