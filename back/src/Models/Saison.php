<?php

namespace App\Models;

class Saison extends Model
{
    protected static string $table = 'saison';
    protected static string $primaryKey = 'IdSaison';
    protected static ?string $prefix = 'SAI';
}
