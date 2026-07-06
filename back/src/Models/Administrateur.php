<?php

namespace App\Models;

/**
 * Specialisation de Utilisateur : IdUtil est a la fois cle primaire de
 * cette table et cle etrangere vers utilisateur.IdUtil.
 */
class Administrateur extends Model
{
    protected static string $table = 'administrateur';
    protected static string $primaryKey = 'IdUtil';
}
