<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Exploitation;
use App\Models\Recolte;

class RecolteController
{
    /** GET /recoltes */
    public static function index(Request $request): void
    {
        $idUtil = self::currentAgriculteur($request);
        if ($idUtil === null) {
            return;
        }
        Response::json(Recolte::historiqueAgriculteur($idUtil));
    }

    /** POST /recoltes : saisir une recolte pour une exploitation */
    public static function store(Request $request): void
    {
        $idUtil = self::currentAgriculteur($request);
        if ($idUtil === null) {
            return;
        }

        $idExploitation = $request->input('IdExploitation');
        $date = $request->input('DateRecolte', date('Y-m-d'));
        $rendement = $request->input('Rendement');
        $cout = $request->input('Cout');
        $observation = $request->input('Observation');

        if (empty($idExploitation)) {
            Response::error('IdExploitation est requis', 422);
            return;
        }
        if (!Exploitation::belongsToAgriculteur($idExploitation, $idUtil)) {
            Response::error('Cette exploitation ne vous appartient pas', 403);
            return;
        }

        $id = Recolte::create([
            'DateRecolte' => $date,
            'Rendement' => $rendement,
            'Cout' => $cout,
            'Observation' => $observation,
            'IdExploitation' => $idExploitation,
        ]);

        Response::json(Recolte::find($id), 201);
    }

    private static function currentAgriculteur(Request $request): ?string
    {
        $idUtil = $request->user['sub'];
        if (!Agriculteur::findByUtilisateur($idUtil)) {
            Response::error('Aucune fiche agriculteur associee a ce compte', 404);
            return null;
        }
        return $idUtil;
    }
}
