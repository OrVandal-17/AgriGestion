<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Utilisateur;

class UtilisateurController
{
    private const ROLES = ['Administrateur', 'Agriculteur', 'Responsable'];

    public static function index(Request $request): void
    {
        $rows = Utilisateur::all('Nom_util');
        foreach ($rows as &$row) {
            unset($row['MotPasse_util']);
        }
        Response::json($rows);
    }

    public static function show(Request $request): void
    {
        $row = Utilisateur::find($request->params['id']);
        if (!$row) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        unset($row['MotPasse_util']);
        Response::json($row);
    }

    public static function store(Request $request): void
    {
        $body = $request->body;
        $required = ['Nom_util', 'Prenom_util', 'Email_util', 'MotPasse_util', 'Role_util'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                Response::error("Le champ $field est requis", 422);
                return;
            }
        }
        if (!in_array($body['Role_util'], self::ROLES, true)) {
            Response::error('Role invalide', 422);
            return;
        }
        if (Utilisateur::findByEmail($body['Email_util'])) {
            Response::error('Cet email est deja utilise', 409);
            return;
        }

        $id = Utilisateur::create([
            'Nom_util' => $body['Nom_util'],
            'Prenom_util' => $body['Prenom_util'],
            'Tel_util' => $body['Tel_util'] ?? null,
            'Email_util' => $body['Email_util'],
            'MotPasse_util' => password_hash($body['MotPasse_util'], PASSWORD_BCRYPT),
            'Role_util' => $body['Role_util'],
            'Id_coop' => $body['Id_coop'] ?? null,
        ]);

        $row = Utilisateur::find($id);
        unset($row['MotPasse_util']);
        Response::json($row, 201);
    }

    public static function update(Request $request): void
    {
        $id = $request->params['id'];
        if (!Utilisateur::find($id)) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        $body = $request->body;
        $data = array_intersect_key($body, array_flip([
            'Nom_util', 'Prenom_util', 'Tel_util', 'Email_util', 'Role_util', 'Id_coop',
        ]));
        if (!empty($body['MotPasse_util'])) {
            $data['MotPasse_util'] = password_hash($body['MotPasse_util'], PASSWORD_BCRYPT);
        }
        if (isset($data['Role_util']) && !in_array($data['Role_util'], self::ROLES, true)) {
            Response::error('Role invalide', 422);
            return;
        }
        if (empty($data)) {
            Response::error('Aucune donnee valide fournie', 422);
            return;
        }
        Utilisateur::update($id, $data);
        $row = Utilisateur::find($id);
        unset($row['MotPasse_util']);
        Response::json($row);
    }

    public static function destroy(Request $request): void
    {
        $id = $request->params['id'];
        if (!Utilisateur::find($id)) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        Utilisateur::delete($id);
        Response::json(['message' => 'Supprime avec succes']);
    }
}
