<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Responsable;
use App\Models\Utilisateur;

class UtilisateurController
{
    private const ROLES = ['Administrateur', 'Agriculteur', 'Responsable'];

    public static function index(Request $request): void
    {
        $rows = Utilisateur::all('Nom');
        foreach ($rows as &$row) {
            unset($row['PassHash']);
            $roleInfo = Utilisateur::determineRole($row['IdUtil']);
            $row['Role'] = $roleInfo['role'] ?? null;
            $row['IdCoop'] = $roleInfo['coop'] ?? null;
        }
        unset($row);
        Response::json($rows);
    }

    public static function show(Request $request): void
    {
        $row = Utilisateur::find($request->params['id']);
        if (!$row) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        unset($row['PassHash']);
        $roleInfo = Utilisateur::determineRole($row['IdUtil']);
        $row['Role'] = $roleInfo['role'] ?? null;
        $row['IdCoop'] = $roleInfo['coop'] ?? null;
        Response::json($row);
    }

    /**
     * Cree un compte utilisateur puis attache sa specialisation
     * (Administrateur / Responsable / Agriculteur). IdCoop n'est
     * pertinent que pour Agriculteur et Responsable.
     */
    public static function store(Request $request): void
    {
        $body = $request->body;
        $required = ['Nom', 'Prenom', 'Email', 'MotPasse', 'Role'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                Response::error("Le champ $field est requis", 422);
                return;
            }
        }
        if (!in_array($body['Role'], self::ROLES, true)) {
            Response::error('Role invalide', 422);
            return;
        }
        if (Utilisateur::findByEmail($body['Email'])) {
            Response::error('Cet email est deja utilise', 409);
            return;
        }

        $idUtil = Utilisateur::create([
            'Nom' => $body['Nom'],
            'Prenom' => $body['Prenom'],
            'Tel' => $body['Tel'] ?? null,
            'Email' => $body['Email'],
            'DateNaissance' => $body['DateNaissance'] ?? null,
            'Sexe' => $body['Sexe'] ?? null,
            'PassHash' => password_hash($body['MotPasse'], PASSWORD_BCRYPT),
        ]);

        $idCoop = !empty($body['IdCoop']) ? $body['IdCoop'] : null;
        Utilisateur::attachRole($idUtil, $body['Role'], $idCoop);

        $row = Utilisateur::find($idUtil);
        unset($row['PassHash']);
        $row['Role'] = $body['Role'];
        $row['IdCoop'] = $idCoop;
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
            'Nom', 'Prenom', 'Tel', 'Email', 'DateNaissance', 'Sexe',
        ]));
        if (!empty($body['MotPasse'])) {
            $data['PassHash'] = password_hash($body['MotPasse'], PASSWORD_BCRYPT);
        }
        if (!empty($data)) {
            Utilisateur::update($id, $data);
        }

        if (!empty($body['Role'])) {
            if (!in_array($body['Role'], self::ROLES, true)) {
                Response::error('Role invalide', 422);
                return;
            }
            $current = Utilisateur::determineRole($id);
            if ($current && $current['role'] !== $body['Role']) {
                Utilisateur::detachRole($id, $current['role']);
            }
            $idCoop = !empty($body['IdCoop']) ? $body['IdCoop'] : null;
            Utilisateur::attachRole($id, $body['Role'], $idCoop);
        } elseif (array_key_exists('IdCoop', $body)) {
            $current = Utilisateur::determineRole($id);
            $idCoop = $body['IdCoop'] !== '' ? $body['IdCoop'] : null;
            if ($current && $current['role'] === 'Agriculteur') {
                Agriculteur::update($id, ['IdCoop' => $idCoop]);
            } elseif ($current && $current['role'] === 'Responsable') {
                Responsable::update($id, ['IdCoop' => $idCoop]);
            }
        }

        $row = Utilisateur::find($id);
        unset($row['PassHash']);
        $roleInfo = Utilisateur::determineRole($id);
        $row['Role'] = $roleInfo['role'] ?? null;
        $row['IdCoop'] = $roleInfo['coop'] ?? null;
        Response::json($row);
    }

    public static function destroy(Request $request): void
    {
        $id = $request->params['id'];
        if (!Utilisateur::find($id)) {
            Response::error('Utilisateur introuvable', 404);
            return;
        }
        $current = Utilisateur::determineRole($id);
        if ($current) {
            Utilisateur::detachRole($id, $current['role']);
        }
        Utilisateur::delete($id);
        Response::json(['message' => 'Supprime avec succes']);
    }
}
