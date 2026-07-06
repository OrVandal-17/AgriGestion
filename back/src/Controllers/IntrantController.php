<?php

namespace App\Controllers;

use App\Models\Intrant;

class IntrantController extends CrudController
{
    protected static function model(): string
    {
        return Intrant::class;
    }

    protected static function fillable(): array
    {
        return ['NomIntrant', 'TypeIntrant', 'Unite', 'PrixUnitaire'];
    }
}
