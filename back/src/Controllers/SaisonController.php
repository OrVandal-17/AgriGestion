<?php

namespace App\Controllers;

use App\Models\Saison;

class SaisonController extends CrudController
{
    protected static function model(): string
    {
        return Saison::class;
    }

    protected static function fillable(): array
    {
        return ['Libelle', 'DateDebut', 'DateFin'];
    }
}
