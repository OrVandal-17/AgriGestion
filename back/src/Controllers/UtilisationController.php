<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Exploitation;
use App\Models\Utilisation;

class UtilisationController
{
    /** GET /intrants-utilises */
    public static function index(Request $request): void
    {
        $idUtil = self::currentAgriculteur($request);
        if ($idUtil === null) {
            return;
        }
        Response::json(Utilisation::historiqueAgriculteur($idUtil));
    }

    /** POST /intrants-utilises : saisir un intrant utilise (traitement) */
    public static function store(Request $request): void
    {
        $idUtil = self::currentAgriculteur($request);
        if ($idUtil === null) {
            return;
        }

        $idExploitation = (int) $request->input('IdExploitation');
        $idIntrant = (int) $request->input('IdIntrant');
        $quantite = $request->input('Quantite');
        $date = $request->input('DateUtil', date('Y-m-d'));

        if (!$idExploitation || !$idIntrant) {
            Response::error('IdExploitation et IdIntrant sont requis', 422);
            return;
        }
        if (!Exploitation::belongsToAgriculteur($idExploitation, $idUtil)) {
            Response::error('Cette exploitation ne vous appartient pas', 403);
            return;
        }

        // La table `utiliser` a une cle composite (IdIntrant, IdExploitation,
        // DateUtil) : pas d'identifiant auto-incremente a relire ensuite.
        Utilisation::create([
            'IdIntrant' => $idIntrant,
            'IdExploitation' => $idExploitation,
            'DateUtil' => $date,
            'Quantite' => $quantite,
        ]);

        Response::json([
            'IdIntrant' => $idIntrant,
            'IdExploitation' => $idExploitation,
            'DateUtil' => $date,
            'Quantite' => $quantite,
        ], 201);
    }

    private static function currentAgriculteur(Request $request): ?int
    {
        $idUtil = (int) $request->user['sub'];
        if (!Agriculteur::findByUtilisateur($idUtil)) {
            Response::error('Aucune fiche agriculteur associee a ce compte', 404);
            return null;
        }
        return $idUtil;
    }
}
