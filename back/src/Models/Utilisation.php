<?php

namespace App\Models;

class Utilisation extends Model
{
    protected static string $table = 'utiliser';
    protected static string $primaryKey = 'Id_utilisation';

    public static function historiqueAgriculteur(int $idAgri): array
    {
        $stmt = static::db()->prepare(
            'SELECT u.*, i.Nom_intrant, i.Unite, p.Localisation
             FROM utiliser u
             JOIN intrant i ON i.Id_intrant = u.Id_intrant
             JOIN parcelle p ON p.Id_parcelle = u.Id_parcelle
             WHERE p.Id_agri = ?
             ORDER BY u.DateUtilisation DESC'
        );
        $stmt->execute([$idAgri]);
        return $stmt->fetchAll();
    }
}
