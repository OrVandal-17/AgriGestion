<?php

namespace App\Models;

class Recolte extends Model
{
    protected static string $table = 'recolte';
    protected static string $primaryKey = 'Id_recolte';

    public static function historiqueAgriculteur(int $idAgri): array
    {
        $stmt = static::db()->prepare(
            'SELECT r.*, c.Nom_culture, p.Localisation
             FROM recolte r
             JOIN culture c ON c.Id_culture = r.Id_culture
             JOIN parcelle p ON p.Id_parcelle = r.Id_parcelle
             WHERE p.Id_agri = ?
             ORDER BY r.DateRecolte DESC'
        );
        $stmt->execute([$idAgri]);
        return $stmt->fetchAll();
    }

    /**
     * Bilan de production pour le Responsable de cooperative : agrege
     * les recoltes de toutes les parcelles des agriculteurs de sa coop.
     * Filtres optionnels : saison, culture, periode.
     */
    public static function bilanCooperative(int $idCoop, array $filters = []): array
    {
        $sql = 'SELECT r.Id_recolte, r.DateRecolte, r.Rendement, r.Cout, r.Observation,
                       c.Nom_culture, p.Localisation, p.Id_parcelle,
                       ag.Nom_agri, ag.Prenom_agri, s.Libelle AS Libelle_saison
                FROM recolte r
                JOIN culture c ON c.Id_culture = r.Id_culture
                JOIN parcelle p ON p.Id_parcelle = r.Id_parcelle
                JOIN agriculteur ag ON ag.Id_agri = p.Id_agri
                LEFT JOIN mise_en_culture mec ON mec.Id_parcelle = p.Id_parcelle AND mec.Id_culture = r.Id_culture
                LEFT JOIN saison s ON s.Id_saison = mec.Id_saison
                WHERE ag.Id_coop = ?';
        $params = [$idCoop];

        if (!empty($filters['culture_id'])) {
            $sql .= ' AND r.Id_culture = ?';
            $params[] = $filters['culture_id'];
        }
        if (!empty($filters['saison_id'])) {
            $sql .= ' AND s.Id_saison = ?';
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
