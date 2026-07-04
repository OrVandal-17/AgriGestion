<?php

namespace App\Models;

class Agriculteur extends Model
{
    protected static string $table = 'agriculteur';
    protected static string $primaryKey = 'Id_agri';

    /**
     * Retrouve la fiche agriculteur liee au compte utilisateur connecte.
     */
    public static function findByUtilisateur(int $idUtil): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM agriculteur WHERE Id_util = ?');
        $stmt->execute([$idUtil]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function byCooperative(int $idCoop): array
    {
        return static::where('Id_coop', $idCoop, 'Nom_agri, Prenom_agri');
    }
}
