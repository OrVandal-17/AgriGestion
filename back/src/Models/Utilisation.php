<?php

namespace App\Models;

/**
 * Modele pour la table d'association `utiliser` (Intrant <-> Exploitation).
 * Cette table n'a pas de cle simple auto-incrementee : sa cle primaire
 * est composite (IdIntrant, IdExploitation, DateUtil). Les methodes
 * generiques find()/update()/delete() de Model ne sont donc pas
 * utilisees pour cette classe ; seules create() (insertion) et les
 * requetes dediees ci-dessous sont pertinentes.
 */
class Utilisation extends Model
{
    protected static string $table = 'utiliser';
    protected static string $primaryKey = 'IdExploitation';

    public static function historiqueAgriculteur(int $idUtil): array
    {
        $stmt = static::db()->prepare(
            'SELECT u.*, i.NomIntrant, i.Unite, p.Localisation
             FROM utiliser u
             JOIN intrant i ON i.IdIntrant = u.IdIntrant
             JOIN exploitation e ON e.IdExploitation = u.IdExploitation
             JOIN parcelle p ON p.IdParcelle = e.IdParcelle
             WHERE p.IdUtil = ?
             ORDER BY u.DateUtil DESC'
        );
        $stmt->execute([$idUtil]);
        return $stmt->fetchAll();
    }
}
