<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Utilisateur;

/**
 * Gestion des fiches agriculteurs par l'Administrateur.
 *
 * Un "agriculteur" est desormais un Utilisateur specialise : la table
 * `agriculteur` ne porte que la cle primaire (IdUtil, heritee de
 * Utilisateur) et la cooperative de rattachement (IdCoop). Les
 * informations personnelles (Nom, Prenom, Email...) vivent dans la
 * table `utilisateur`. Ce controleur cree/modifie donc les deux lignes
 * correspondantes plutot que d'etendre le CrudController generique.
 */
class AgriculteurController
{
    private const UTILISATEUR_FIELDS = ['Nom', 'Prenom', 'Tel', 'Email', 'DateNaissance', 'Sexe'];

    public static function index(Request $request): void
    {
        Response::json(Agriculteur::allWithProfile());
    }

    public static function show(Request $request): void
    {
        $row = Agriculteur::withProfile((int) $request->params['id']);
        if (!$row) {
            Response::error('Agriculteur introuvable', 404);
            return;
        }
        Response::json($row);
    }

    /**
     * Corps attendu : Nom, Prenom, Email, MotPasse, IdCoop
     * (+ optionnels : Tel, DateNaissance, Sexe).
     */
    public static function store(Request $request): void
    {
        $body = $request->body;
        $required = ['Nom', 'Prenom', 'Email', 'MotPasse'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                Response::error("Le champ $field est requis", 422);
                return;
            }
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

        $idCoop = (!empty($body['IdCoop'])) ? (int) $body['IdCoop'] : null;
        Utilisateur::attachRole($idUtil, 'Agriculteur', $idCoop);

        Response::json(Agriculteur::withProfile($idUtil), 201);
    }

    public static function update(Request $request): void
    {
        $idUtil = (int) $request->params['id'];
        if (!Agriculteur::find($idUtil)) {
            Response::error('Agriculteur introuvable', 404);
            return;
        }
        $body = $request->body;

        $utilData = array_intersect_key($body, array_flip(self::UTILISATEUR_FIELDS));
        if (!empty($body['MotPasse'])) {
            $utilData['PassHash'] = password_hash($body['MotPasse'], PASSWORD_BCRYPT);
        }
        if (!empty($utilData)) {
            Utilisateur::update($idUtil, $utilData);
        }
        if (array_key_exists('IdCoop', $body)) {
            Agriculteur::update($idUtil, ['IdCoop' => $body['IdCoop'] !== '' ? $body['IdCoop'] : null]);
        }

        Response::json(Agriculteur::withProfile($idUtil));
    }

    /**
     * Retire la specialisation Agriculteur. Le compte Utilisateur est
     * conserve (il pourrait se voir attribuer un autre role).
     */
    public static function destroy(Request $request): void
    {
        $idUtil = (int) $request->params['id'];
        if (!Agriculteur::find($idUtil)) {
            Response::error('Agriculteur introuvable', 404);
            return;
        }
        Agriculteur::delete($idUtil);
        Response::json(['message' => 'Supprime avec succes']);
    }
}
