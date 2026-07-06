<?php

namespace App\Controllers;

use App\Models\ZoneAgroecologique;

class ZoneController extends CrudController
{
    protected static function model(): string
    {
        return ZoneAgroecologique::class;
    }

    protected static function fillable(): array
    {
        return ['NomZone', 'Region', 'Description'];
    }
}
