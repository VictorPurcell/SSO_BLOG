<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Auth\Guard;


class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $user = Auth::user();

    // Verificação de 2FA com DUO
    if ($user->requires2FA()) {
        return response()->json(['message' => '2FA necessário'], 403);
    }

    $token = $user->createToken('AuthToken')->accessToken;

    return response()->json(['token' => $token], 200);
}

}
