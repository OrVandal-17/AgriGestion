<?php

namespace App\Models;

class Parcelle extends Model
{
    protected static string $table = 'parcelle';
    protected static string $primaryKey = 'IdParcelle';

    public static function byAgriculteur(int $idUtil): array
    {
        return static::where('IdUtil', $idUtil, 'IdParcelle DESC');
    }

    /**
     * Verifie que la parcelle appartient bien a l'agriculteur donne
     * (controle d'acces pour toutes les saisies).
     */
    public static function belongsToAgriculteur(int $idParcelle, int $idUtil): bool
    {
        $stmt = static::db()->prepare('SELECT 1 FROM parcelle WHERE IdParcelle = ? AND IdUtil = ?');
        $stmt->execute([$idParcelle, $idUtil]);
        return (bool) $stmt->fetchColumn();
    }
}
