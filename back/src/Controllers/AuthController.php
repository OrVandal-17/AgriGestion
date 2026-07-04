<?php

namespace App\Controllers;

use App\Core\JWT;
use App\Core\Request;
use App\Core\Response;
use App\Models\Utilisateur;
use App\Models\Agriculteur;

class AuthController
{
    public static function login(Request $request): void
    {
        $email = trim((string) $request->input('email', ''));
        $motDePasse = (string) $request->input('mot_de_passe', '');

        if ($email === '' || $motDePasse === '') {
            Response::error('Email et mot de passe requis', 422);
            return;
        }

        $user = Utilisateur::findByEmail($email);
        if (!$user || !password_verify($motDePasse, $user['MotPasse_util'])) {
            Response::error('Identifiants invalides', 401);
            return;
        }

        $payload = [
            'sub' => (int) $user['Id_util'],
            'role' => $user['Role_util'],
            'coop' => $user['Id_coop'] !== null ? (int) $user['Id_coop'] : null,
        ];
        $token = JWT::encode($payload);

        unset($user['MotPasse_util']);

        Response::json([
            'token' => $token,
            'utilisateur' => $user,
        ]);
    }

    public static function me(Request $request): void
    {
        $user = Utilisateur::find($request->user['sub']);
        if (!$user) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        unset($user['MotPasse_util']);

        $agriculteur = null;
        if ($user['Role_util'] === 'Agriculteur') {
            $agriculteur = Agriculteur::findByUtilisateur((int) $user['Id_util']);
        }

        Response::json(['utilisateur' => $user, 'agriculteur' => $agriculteur]);
    }
}
