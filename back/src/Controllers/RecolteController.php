<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Parcelle;
use App\Models\Recolte;

class RecolteController
{
    /** GET /recoltes */
    public static function index(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }
        Response::json(Recolte::historiqueAgriculteur((int) $agriculteur['Id_agri']));
    }

    /** POST /recoltes : saisir une recolte */
    public static function store(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }

        $idParcelle = (int) $request->input('Id_parcelle');
        $idCulture = (int) $request->input('Id_culture');
        $date = $request->input('DateRecolte', date('Y-m-d'));
        $rendement = $request->input('Rendement');
        $cout = $request->input('Cout');
        $observation = $request->input('Observation');

        if (!$idParcelle || !$idCulture) {
            Response::error('Id_parcelle et Id_culture sont requis', 422);
            return;
        }
        if (!Parcelle::belongsToAgriculteur($idParcelle, (int) $agriculteur['Id_agri'])) {
            Response::error('Cette parcelle ne vous appartient pas', 403);
            return;
        }

        $id = Recolte::create([
            'DateRecolte' => $date,
            'Rendement' => $rendement,
            'Cout' => $cout,
            'Observation' => $observation,
            'Id_parcelle' => $idParcelle,
            'Id_culture' => $idCulture,
        ]);

        Response::json(Recolte::find($id), 201);
    }

    private static function currentAgriculteur(Request $request): ?array
    {
        $agriculteur = Agriculteur::findByUtilisateur((int) $request->user['sub']);
        if (!$agriculteur) {
            Response::error('Aucune fiche agriculteur associee a ce compte', 404);
            return null;
        }
        return $agriculteur;
    }
}
