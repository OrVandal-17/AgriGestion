<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Recolte;

class RapportController
{
    /**
     * GET /bilan-production?saison_id=&culture_id=&date_debut=&date_fin=
     * <<extend>> Filtrer par saison / Filtrer par culture-periode
     */
    public static function bilanProduction(Request $request): void
    {
        $idCoop = self::currentCoop($request);
        if ($idCoop === null) {
            return;
        }

        $rows = Recolte::bilanCooperative($idCoop, [
            'saison_id' => $request->input('saison_id'),
            'culture_id' => $request->input('culture_id'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
        ]);

        $totalRendement = array_sum(array_column($rows, 'Rendement'));
        $totalCout = array_sum(array_column($rows, 'Cout'));

        Response::json([
            'total_rendement' => $totalRendement,
            'total_cout' => $totalCout,
            'nombre_recoltes' => count($rows),
            'recoltes' => $rows,
        ]);
    }

    /** GET /agriculteurs-cooperative : liste des agriculteurs de la coop du responsable */
    public static function listeAgriculteurs(Request $request): void
    {
        $idCoop = self::currentCoop($request);
        if ($idCoop === null) {
            return;
        }
        Response::json(Agriculteur::byCooperative($idCoop));
    }

    private static function currentCoop(Request $request): ?string
    {
        $idCoop = $request->user['coop'] ?? null;
        if ($idCoop === null) {
            Response::error('Ce compte n\'est rattache a aucune cooperative', 422);
            return null;
        }
        return $idCoop;
    }
}
