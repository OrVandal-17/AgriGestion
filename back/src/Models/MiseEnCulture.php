<?php

namespace App\Models;

class MiseEnCulture extends Model
{
    protected static string $table = 'mise_en_culture';
    protected static string $primaryKey = 'Id_mise_en_culture';

    public static function historiqueParcelle(int $idParcelle): array
    {
        $stmt = static::db()->prepare(
            'SELECT mec.*, c.Nom_culture, s.Libelle AS Libelle_saison
             FROM mise_en_culture mec
             JOIN culture c ON c.Id_culture = mec.Id_culture
             LEFT JOIN saison s ON s.Id_saison = mec.Id_saison
             WHERE mec.Id_parcelle = ?
             ORDER BY mec.DateMiseEnCulture DESC'
        );
        $stmt->execute([$idParcelle]);
        return $stmt->fetchAll();
    }

    public static function historiqueAgriculteur(int $idAgri): array
    {
        $stmt = static::db()->prepare(
            'SELECT mec.*, c.Nom_culture, p.Localisation, s.Libelle AS Libelle_saison
             FROM mise_en_culture mec
             JOIN culture c ON c.Id_culture = mec.Id_culture
             JOIN parcelle p ON p.Id_parcelle = mec.Id_parcelle
             LEFT JOIN saison s ON s.Id_saison = mec.Id_saison
             WHERE p.Id_agri = ?
             ORDER BY mec.DateMiseEnCulture DESC'
        );
        $stmt->execute([$idAgri]);
        return $stmt->fetchAll();
    }

    /**
     * Cas d'utilisation <<extend>> "Recevoir une alerte de rotation" :
     * signale si la meme culture a deja ete plantee sur la parcelle lors
     * de la mise en culture precedente (mauvaise pratique agronomique).
     */
    public static function alerteRotation(int $idParcelle, int $idCulture): bool
    {
        $stmt = static::db()->prepare(
            'SELECT Id_culture FROM mise_en_culture
             WHERE Id_parcelle = ?
             ORDER BY DateMiseEnCulture DESC
             LIMIT 1'
        );
        $stmt->execute([$idParcelle]);
        $derniere = $stmt->fetchColumn();
        return $derniere !== false && (int) $derniere === $idCulture;
    }
}
