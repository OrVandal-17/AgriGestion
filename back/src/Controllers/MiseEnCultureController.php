<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\MiseEnCulture;
use App\Models\Parcelle;

class MiseEnCultureController
{
    /** GET /mises-en-culture : historique de l'agriculteur connecte */
    public static function index(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }
        Response::json(MiseEnCulture::historiqueAgriculteur((int) $agriculteur['Id_agri']));
    }

    /** POST /mises-en-culture : saisir une mise en culture (semis) */
    public static function store(Request $request): void
    {
        $agriculteur = self::currentAgriculteur($request);
        if (!$agriculteur) {
            return;
        }

        $idParcelle = (int) $request->input('Id_parcelle');
        $idCulture = (int) $request->input('Id_culture');
        $idSaison = $request->input('Id_saison');
        $date = $request->input('DateMiseEnCulture', date('Y-m-d'));

        if (!$idParcelle || !$idCulture) {
            Response::error('Id_parcelle et Id_culture sont requis', 422);
            return;
        }
        if (!Parcelle::belongsToAgriculteur($idParcelle, (int) $agriculteur['Id_agri'])) {
            Response::error('Cette parcelle ne vous appartient pas', 403);
            return;
        }

        // Cas d'utilisation <<extend>> : alerte de rotation culturale
        $alerte = MiseEnCulture::alerteRotation($idParcelle, $idCulture);

        $id = MiseEnCulture::create([
            'Id_parcelle' => $idParcelle,
            'Id_culture' => $idCulture,
            'Id_saison' => $idSaison ?: null,
            'DateMiseEnCulture' => $date,
        ]);

        Response::json([
            'mise_en_culture' => MiseEnCulture::find($id),
            'alerte_rotation' => $alerte,
            'message' => $alerte
                ? 'Attention : meme culture que la mise en culture precedente sur cette parcelle (rotation non respectee).'
                : null,
        ], 201);
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
