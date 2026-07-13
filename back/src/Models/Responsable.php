<?php

namespace App\Models;

/**
 * Specialisation de Utilisateur representant un responsable de
 * cooperative. IdUtil est a la fois cle primaire et cle etrangere
 * vers utilisateur.IdUtil ; IdCoop rattache le responsable a sa
 * cooperative.
 */
class Responsable extends Model
{
    protected static string $table = 'responsable';
    protected static string $primaryKey = 'IdUtil';

    public static function findByUtilisateur(string $idUtil): ?array
    {
        return static::find($idUtil);
    }
}
