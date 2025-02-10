<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Contracts\Auth\Guard;

class DuoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Verifica se o usuário precisa do 2FA
        if (!$user || !$user->requires2FA()) {
            return $next($request);
        }

        // Verifica com a API da Duo Security
        $response = Http::post('https://' . env('DUO_API_HOSTNAME') . '/auth/v2/auth', [
            'username' => $user->email,
            'factor' => 'push',
            'device' => 'auto',
            'integration_key' => env('DUO_INTEGRATION_KEY'),
            'secret_key' => env('DUO_SECRET_KEY'),
        ]);

        $data = $response->json();

        if ($data['stat'] !== 'OK' || $data['response']['result'] !== 'allow') {
            return response()->json(['message' => 'Autenticação DUO falhou'], 403);
        }

        return $next($request);
    }
}
