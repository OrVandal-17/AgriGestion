<?php

namespace App\Controllers;

use App\Models\Cooperative;

class CooperativeController extends CrudController
{
    protected static function model(): string
    {
        return Cooperative::class;
    }

    protected static function fillable(): array
    {
        return ['Nom_coop', 'Adresse_coop', 'Telephone_coop', 'Email_coop'];
    }
}
