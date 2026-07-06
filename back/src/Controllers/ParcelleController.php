<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Agriculteur;
use App\Models\Parcelle;

class ParcelleController extends CrudController
{
    protected static function model(): string
    {
        return Parcelle::class;
    }

    protected static function fillable(): array
    {
        return ['Superficie', 'Localisation', 'EtatParcelle', 'IdZone', 'IdUtil'];
    }

    /**
     * GET /mes-parcelles : parcelles de l'agriculteur connecte uniquement.
     */
    public static function mine(Request $request): void
    {
        $idUtil = (int) $request->user['sub'];
        if (!Agriculteur::findByUtilisateur($idUtil)) {
            Response::error('Aucune fiche agriculteur associee a ce compte', 404);
            return;
        }
        Response::json(Parcelle::byAgriculteur($idUtil));
    }
}
