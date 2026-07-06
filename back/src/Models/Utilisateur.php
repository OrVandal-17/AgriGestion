<?php

namespace App\Models;

class Utilisateur extends Model
{
    protected static string $table = 'utilisateur';
    protected static string $primaryKey = 'IdUtil';

    public static function findByEmail(string $email): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM utilisateur WHERE Email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Determine le role d'un utilisateur en fonction de la table de
     * specialisation dans laquelle il apparait (Administrateur,
     * Responsable ou Agriculteur), heritage IdUtil = PK et FK.
     * Retourne ['role' => ..., 'coop' => int|null] ou null si aucun role.
     */
    public static function determineRole(int $idUtil): ?array
    {
        if (Administrateur::find($idUtil)) {
            return ['role' => 'Administrateur', 'coop' => null];
        }

        $responsable = Responsable::find($idUtil);
        if ($responsable) {
            return [
                'role' => 'Responsable',
                'coop' => $responsable['IdCoop'] !== null ? (int) $responsable['IdCoop'] : null,
            ];
        }

        $agriculteur = Agriculteur::find($idUtil);
        if ($agriculteur) {
            return [
                'role' => 'Agriculteur',
                'coop' => $agriculteur['IdCoop'] !== null ? (int) $agriculteur['IdCoop'] : null,
            ];
        }

        return null;
    }

    /**
     * Attache une specialisation (role) a un compte utilisateur existant.
     */
    public static function attachRole(int $idUtil, string $role, ?int $idCoop = null): void
    {
        switch ($role) {
            case 'Administrateur':
                Administrateur::create(['IdUtil' => $idUtil]);
                break;
            case 'Responsable':
                Responsable::create(['IdUtil' => $idUtil, 'IdCoop' => $idCoop]);
                break;
            case 'Agriculteur':
                Agriculteur::create(['IdUtil' => $idUtil, 'IdCoop' => $idCoop]);
                break;
        }
    }

    /**
     * Retire la specialisation (role) d'un compte utilisateur, utile
     * lors d'un changement de role ou d'une suppression de compte.
     */
    public static function detachRole(int $idUtil, string $role): void
    {
        switch ($role) {
            case 'Administrateur':
                Administrateur::delete($idUtil);
                break;
            case 'Responsable':
                Responsable::delete($idUtil);
                break;
            case 'Agriculteur':
                Agriculteur::delete($idUtil);
                break;
        }
    }
}
