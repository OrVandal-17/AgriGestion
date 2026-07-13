<?php

namespace App\Models;

/**
 * Specialisation de Utilisateur representant un agriculteur. IdUtil est
 * a la fois cle primaire de cette table et cle etrangere vers
 * utilisateur.IdUtil ; IdCoop rattache l'agriculteur a sa cooperative.
 * Les informations personnelles (Nom, Prenom, Email...) vivent dans la
 * table utilisateur.
 */
class Agriculteur extends Model
{
    protected static string $table = 'agriculteur';
    protected static string $primaryKey = 'IdUtil';

    /**
     * Retrouve la specialisation agriculteur liee au compte utilisateur
     * connecte (simple recherche par cle primaire, puisque IdUtil est
     * partage entre les deux tables).
     */
    public static function findByUtilisateur(string $idUtil): ?array
    {
        return static::find($idUtil);
    }

    /**
     * Fiche complete d'un agriculteur (jointure utilisateur + agriculteur).
     */
    public static function withProfile(string $idUtil): ?array
    {
        $stmt = static::db()->prepare(
            'SELECT u.IdUtil, u.Nom, u.Prenom, u.Tel, u.Email, u.DateNaissance, u.Sexe, a.IdCoop
             FROM agriculteur a
             JOIN utilisateur u ON u.IdUtil = a.IdUtil
             WHERE a.IdUtil = ?'
        );
        $stmt->execute([$idUtil]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Liste complete des agriculteurs (jointure utilisateur + agriculteur).
     */
    public static function allWithProfile(): array
    {
        return static::db()->query(
            'SELECT u.IdUtil, u.Nom, u.Prenom, u.Tel, u.Email, u.DateNaissance, u.Sexe, a.IdCoop
             FROM agriculteur a
             JOIN utilisateur u ON u.IdUtil = a.IdUtil
             ORDER BY u.Nom, u.Prenom'
        )->fetchAll();
    }

    /**
     * Liste des agriculteurs d'une cooperative donnee, avec leurs
     * informations personnelles.
     */
    public static function byCooperative(string $idCoop): array
    {
        $stmt = static::db()->prepare(
            'SELECT u.IdUtil, u.Nom, u.Prenom, u.Tel, u.Email, u.DateNaissance, u.Sexe, a.IdCoop
             FROM agriculteur a
             JOIN utilisateur u ON u.IdUtil = a.IdUtil
             WHERE a.IdCoop = ?
             ORDER BY u.Nom, u.Prenom'
        );
        $stmt->execute([$idCoop]);
        return $stmt->fetchAll();
    }
}
