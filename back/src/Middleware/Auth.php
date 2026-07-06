<?php

namespace App\Middleware;

use App\Core\JWT;
use App\Core\Request;

class Auth
{
    /**
     * Verifie le token Bearer et retourne le payload utilisateur
     * (sub = IdUtil, role, coop = IdCoop) ou null si absent/invalide.
     */
    public static function check(Request $request): ?array
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }
        return JWT::decode($token);
    }
}
