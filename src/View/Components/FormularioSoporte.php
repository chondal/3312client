<?php

namespace Chondal\TicketSoporte\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Chondal\TicketSoporte\Services\TicketService;

class FormularioSoporte extends Component
{
    public $tiposTicket;

    public function __construct(TicketService $ticketService)
    {
        $this->tiposTicket = Cache::remember('3312_tipos_ticket', now()->addMonth(), function () use ($ticketService) {
            return $ticketService->obtenerTiposTicket()->json();
        });
    }

    public function render()
    {
        $version = config('3312client.bootstrap', 5);

        return view("3312client::bootstrap{$version}.formulario-soporte", [
            'tiposTicket' => $this->tiposTicket
        ]);
    }
}
