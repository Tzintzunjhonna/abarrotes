<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validación
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Autenticación
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return response()->json(['user' => $user, 'token' => $user->createToken('API Token')->plainTextToken]);
        }

        return response()->json(['message' => 'Credenciales incorrectas.'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión cerrada'])->withCookie(cookie('contreras_corp_session', '', -1));

    }
}
