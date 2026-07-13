<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey;

    /**
     * Prefixe du code alphanumerique genere pour la cle primaire de ce
     * modele (ex: 'USR', 'PAR'...). Laisser a null pour les modeles dont
     * la cle primaire est toujours fournie explicitement (specialisations
     * Administrateur/Responsable/Agriculteur dont IdUtil vient de
     * Utilisateur, ou la table d'association `utiliser`) : create()
     * n'en a alors pas besoin, voir la condition dans create().
     */
    protected static ?string $prefix = null;

    /** Alphabet sans caracteres ambigus (0/O, 1/I/L) pour les codes generes. */
    private const CODE_ALPHABET = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';

    protected static function db(): PDO
    {
        return Database::connection();
    }

    public static function all(string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM ' . static::$table;
        if ($orderBy) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        return static::db()->query($sql)->fetchAll();
    }

    public static function find($id): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Insere une ligne et retourne sa cle primaire. Si $data ne contient
     * pas deja la cle primaire (cas des tables avec prefixe : Utilisateur,
     * Cooperative, Culture...), un code est genere avant l'insertion —
     * plus d'AUTO_INCREMENT MySQL a relire via lastInsertId().
     */
    public static function create(array $data): string
    {
        if (!array_key_exists(static::$primaryKey, $data) && static::$prefix !== null) {
            $data = [static::$primaryKey => static::generateCode()] + $data;
        }

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::$table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = static::db()->prepare($sql);
        $stmt->execute(array_values($data));

        return (string) $data[static::$primaryKey];
    }

    /**
     * Genere un code "PREFIXE-XXXXXX" (6 caracteres alphanumeriques) et
     * reboucle dans le rare cas d'une collision avec un code existant.
     */
    protected static function generateCode(): string
    {
        do {
            $suffix = '';
            for ($i = 0; $i < 6; $i++) {
                $suffix .= self::CODE_ALPHABET[random_int(0, strlen(self::CODE_ALPHABET) - 1)];
            }
            $code = static::$prefix . '-' . $suffix;
        } while (static::find($code));

        return $code;
    }

    public static function update($id, array $data): bool
    {
        $assignments = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $sql = 'UPDATE ' . static::$table . " SET $assignments WHERE " . static::$primaryKey . ' = ?';
        $stmt = static::db()->prepare($sql);
        return $stmt->execute([...array_values($data), $id]);
    }

    public static function delete($id): bool
    {
        $stmt = static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?');
        return $stmt->execute([$id]);
    }

    public static function where(string $column, $value, string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM ' . static::$table . " WHERE $column = ?";
        if ($orderBy) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        $stmt = static::db()->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }
}
