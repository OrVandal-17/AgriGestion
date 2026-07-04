<?php

namespace App\Controllers;

use App\Models\Culture;

class CultureController extends CrudController
{
    protected static function model(): string
    {
        return Culture::class;
    }

    protected static function fillable(): array
    {
        return ['Nom_culture', 'Type_culture', 'DatePlantation', 'DureeCycle'];
    }
}
