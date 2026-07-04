<?php

namespace App\Models;

class Parcelle extends Model
{
    protected static string $table = 'parcelle';
    protected static string $primaryKey = 'Id_parcelle';

    public static function byAgriculteur(int $idAgri): array
    {
        return static::where('Id_agri', $idAgri, 'Id_parcelle DESC');
    }

    /**
     * Verifie que la parcelle appartient bien a l'agriculteur donne
     * (controle d'acces pour toutes les saisies).
     */
    public static function belongsToAgriculteur(int $idParcelle, int $idAgri): bool
    {
        $stmt = static::db()->prepare('SELECT 1 FROM parcelle WHERE Id_parcelle = ? AND Id_agri = ?');
        $stmt->execute([$idParcelle, $idAgri]);
        return (bool) $stmt->fetchColumn();
    }
}
