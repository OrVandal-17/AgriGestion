<?php

namespace App\Models;

class Recolte extends Model
{
    protected static string $table = 'recolte';
    protected static string $primaryKey = 'IdRecolte';

    public static function historiqueAgriculteur(int $idUtil): array
    {
        $stmt = static::db()->prepare(
            'SELECT r.*, c.NomCulture, p.Localisation
             FROM recolte r
             JOIN exploitation e ON e.IdExploitation = r.IdExploitation
             JOIN culture c ON c.IdCulture = e.IdCulture
             JOIN parcelle p ON p.IdParcelle = e.IdParcelle
             WHERE p.IdUtil = ?
             ORDER BY r.DateRecolte DESC'
        );
        $stmt->execute([$idUtil]);
        return $stmt->fetchAll();
    }

    /**
     * Bilan de production pour le Responsable de cooperative : agrege
     * les recoltes de toutes les parcelles/exploitations des
     * agriculteurs de sa coop. Filtres optionnels : saison, culture, periode.
     */
    public static function bilanCooperative(int $idCoop, array $filters = []): array
    {
        $sql = 'SELECT r.IdRecolte, r.DateRecolte, r.Rendement, r.Cout, r.Observation,
                       c.NomCulture, p.Localisation, p.IdParcelle,
                       u.Nom, u.Prenom, s.Libelle AS LibelleSaison
                FROM recolte r
                JOIN exploitation e ON e.IdExploitation = r.IdExploitation
                JOIN culture c ON c.IdCulture = e.IdCulture
                JOIN parcelle p ON p.IdParcelle = e.IdParcelle
                JOIN agriculteur ag ON ag.IdUtil = p.IdUtil
                JOIN utilisateur u ON u.IdUtil = ag.IdUtil
                LEFT JOIN saison s ON s.IdSaison = e.IdSaison
                WHERE ag.IdCoop = ?';
        $params = [$idCoop];

        if (!empty($filters['culture_id'])) {
            $sql .= ' AND e.IdCulture = ?';
            $params[] = $filters['culture_id'];
        }
        if (!empty($filters['saison_id'])) {
            $sql .= ' AND e.IdSaison = ?';
            $params[] = $filters['saison_id'];
        }
        if (!empty($filters['date_debut'])) {
            $sql .= ' AND r.DateRecolte >= ?';
            $params[] = $filters['date_debut'];
        }
        if (!empty($filters['date_fin'])) {
            $sql .= ' AND r.DateRecolte <= ?';
            $params[] = $filters['date_fin'];
        }
        $sql .= ' ORDER BY r.DateRecolte DESC';

        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
