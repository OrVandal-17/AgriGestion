<?php

namespace App\Controllers;

use App\Models\Agriculteur;

class AgriculteurController extends CrudController
{
    protected static function model(): string
    {
        return Agriculteur::class;
    }

    protected static function fillable(): array
    {
        return [
            'Nom_agri', 'Prenom_agri', 'Sexe', 'DateNaissance',
            'Telephone', 'Adresse', 'Id_coop', 'Id_util',
        ];
    }
}
