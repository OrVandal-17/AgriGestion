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
        if (!$user || !password_verify($motDePasse, $user['PassHash'])) {
            Response::error('Identifiants invalides', 401);
            return;
        }

        $roleInfo = Utilisateur::determineRole($user['IdUtil']);
        if (!$roleInfo) {
            Response::error('Ce compte n\'a aucun role attribue', 403);
            return;
        }

        $payload = [
            'sub' => $user['IdUtil'],
            'role' => $roleInfo['role'],
            'coop' => $roleInfo['coop'],
        ];
        $token = JWT::encode($payload);

        unset($user['PassHash']);
        $user['Role'] = $roleInfo['role'];
        $user['IdCoop'] = $roleInfo['coop'];

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
        unset($user['PassHash']);

        $roleInfo = Utilisateur::determineRole($user['IdUtil']);
        $user['Role'] = $roleInfo['role'] ?? null;
        $user['IdCoop'] = $roleInfo['coop'] ?? null;

        $agriculteur = null;
        if (($roleInfo['role'] ?? null) === 'Agriculteur') {
            $agriculteur = Agriculteur::findByUtilisateur($user['IdUtil']);
        }

        Response::json(['utilisateur' => $user, 'agriculteur' => $agriculteur]);
    }
}
