<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Parcelle;
use App\Models\Utilisation;

class UtilisationController
{
    /** GET /intrants-utilises */
    public static function index(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }
        Response::json(Utilisation::historiqueAgriculteur((int) $agriculteur['Id_agri']));
    }

    /** POST /intrants-utilises : saisir un intrant utilise (traitement) */
    public static function store(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }

        $idParcelle = (int) $request->input('Id_parcelle');
        $idIntrant = (int) $request->input('Id_intrant');
        $quantite = $request->input('Quantite');
        $date = $request->input('DateUtilisation', date('Y-m-d'));

        if (!$idParcelle || !$idIntrant) {
            Response::error('Id_parcelle et Id_intrant sont requis', 422);
            return;
        }
        if (!Parcelle::belongsToAgriculteur($idParcelle, (int) $agriculteur['Id_agri'])) {
            Response::error('Cette parcelle ne vous appartient pas', 403);
            return;
        }

        $id = Utilisation::create([
            'Id_parcelle' => $idParcelle,
            'Id_intrant' => $idIntrant,
            'DateUtilisation' => $date,
            'Quantite' => $quantite,
        ]);

        Response::json(Utilisation::find($id), 201);
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
