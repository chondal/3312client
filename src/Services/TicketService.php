<?php

namespace Chondal\TicketSoporte\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TicketService
{
    protected string $baseUrl;
    protected string $identificadorUnico;
    protected string $token;

    public function __construct(string $baseUrl, string $identificadorUnico, string $token)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->identificadorUnico = $identificadorUnico;
        $this->token = $token;
    }

    protected function send(callable $callback, string $context): Response
    {
        try {
            $response = $callback();

            if ($response->failed()) {
                Log::error("TicketService {$context} failed", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response;
        } catch (\Throwable $e) {
            Log::error("TicketService {$context} exception", [
                'error' => $e->getMessage(),
            ]);

            return Http::response(['message' => 'Service unavailable'], 500);
        }
    }

    public function crearTicket(array $datos, array $archivos = []): Response
    {
        $multipart = [];

        foreach (array_merge($datos, ['identificador_unico' => $this->identificadorUnico]) as $key => $value) {
            $multipart[] = ['name' => $key, 'contents' => $value];
        }

        foreach ($archivos as $archivo) {
            if ($archivo instanceof UploadedFile && $archivo->isValid()) {
                try {
                    $multipart[] = [
                        'name' => 'archivos[]',
                        'contents' => fopen($archivo->getRealPath(), 'r'),
                        'filename' => $archivo->getClientOriginalName(),
                    ];
                } catch (\Exception $e) {
                    Log::error('Error al adjuntar archivo en creación de ticket', [
                        'archivo' => $archivo->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $this->send(function () use ($multipart) {
            return Http::withToken($this->token)
                ->asMultipart()
                ->post("{$this->baseUrl}/api/tickets", $multipart);
        }, 'crearTicket');
    }

    public function responderTicket(string $email, int $ticketId, string $mensaje, array $archivos = []): Response
    {
        $multipart = [];

        $datos = [
            'email' => $email,
            'identificador_unico' => $this->identificadorUnico,
            'mensaje' => $mensaje,
        ];

        foreach ($datos as $key => $value) {
            $multipart[] = ['name' => $key, 'contents' => $value];
        }

        foreach ($archivos as $archivo) {
            if ($archivo instanceof UploadedFile && $archivo->isValid()) {
                try {
                    $multipart[] = [
                        'name' => 'archivos[]',
                        'contents' => fopen($archivo->getRealPath(), 'r'),
                        'filename' => $archivo->getClientOriginalName(),
                    ];
                } catch (\Exception $e) {
                    Log::error('Error al adjuntar archivo en respuesta de ticket', [
                        'archivo' => $archivo->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $this->send(function () use ($ticketId, $multipart) {
            return Http::withToken($this->token)
                ->asMultipart()
                ->post("{$this->baseUrl}/api/tickets/{$ticketId}/responder", $multipart);
        }, 'responderTicket');
    }

    public function obtenerTickets(string $email, ?int $perPage = 10): Response
    {
        return $this->send(function () use ($email, $perPage) {
            return Http::withToken($this->token)
                ->get("{$this->baseUrl}/api/tickets", [
                    'email' => $email,
                    'identificador_unico' => $this->identificadorUnico,
                    'per_page' => $perPage
                ]);
        }, 'obtenerTickets');
    }

    public function obtenerTicket(string $email, int $ticketId): Response
    {
        return $this->send(function () use ($email, $ticketId) {
            return Http::withToken($this->token)
                ->get("{$this->baseUrl}/api/tickets/{$ticketId}", [
                    'email' => $email,
                    'identificador_unico' => $this->identificadorUnico
                ]);
        }, 'obtenerTicket');
    }

    public function cerrarTicket(string $email, int $ticketId): Response
    {
        return $this->send(function () use ($email, $ticketId) {
            return Http::withToken($this->token)
                ->get("{$this->baseUrl}/api/cerrar-ticket/{$ticketId}", [
                    'email' => $email,
                    'identificador_unico' => $this->identificadorUnico
                ]);
        }, 'cerrarTicket');
    }

    public function obtenerTiposTicket(): Response
    {
        return $this->send(function () {
            return Http::withToken($this->token)
                ->get("{$this->baseUrl}/api/tipos-ticket");
        }, 'obtenerTiposTicket');
    }
}
