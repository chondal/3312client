<?php

namespace Chondal\TicketSoporte\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Chondal\TicketSoporte\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $tickets = $this->ticketService->obtenerTickets(Auth::user()->email)->json();
        $tiposTicket = $this->ticketService->obtenerTiposTicket()->json();
        return view($this->getView('index'), compact('tickets', 'tiposTicket'));
    }

    public function show($ticketId)
    {
        $ticket = $this->ticketService->obtenerTicket(Auth::user()->email, $ticketId)->json();
        return view($this->getView('show'), compact('ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'tipo_ticket_id' => 'required|integer',
            'prioridad' => 'required|string|in:baja,media,alta,urgente',
            'archivos.*' => 'nullable|file|max:10240'
        ]);

        $archivos = $request->file('archivos', []);
        $response = $this->ticketService->crearTicket($request->all(), $archivos);

        if ($response->successful()) {
            $ticket = $response->json();
            if (!isset($ticket['ticket']['id'])) {
                flash('Error: No se pudo obtener el número de ticket')->error();
                return back();
            }
            flash('Ticket #' . $ticket['ticket']['id'] . ' creado exitosamente')->success();
            return back();
        }

        flash('Error al crear el ticket')->error();
        return back();
    }

    public function responder(Request $request, $ticketId)
    {
        $request->validate([
            'respuesta' => 'required|string',
            'archivos.*' => 'nullable|file|max:10240'
        ]);

        $archivos = $request->file('archivos', []);
        $response = $this->ticketService->responderTicket(Auth::user()->email, $ticketId, $request->respuesta, $archivos);

        if ($response->successful()) {
            flash('Respuesta enviada exitosamente')->success();
            return back();
        }

        return back()->with('error', 'Error al enviar la respuesta');
    }

    public function cerrar($ticketId)
    {
        $response = $this->ticketService->cerrarTicket(Auth::user()->email, $ticketId);

        if ($response->successful()) {
            flash('Ticket cerrado exitosamente')->success();
            return back();
        }

        flash('Error al cerrar el ticket')->error();
        return back();
    }

    protected function getView($name)
    {
        $version = config('3312client.bootstrap', 5);
        return "3312client::bootstrap{$version}." . $name;
    }
}
