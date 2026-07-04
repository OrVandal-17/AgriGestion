<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey;

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

    public static function create(array $data): int
    {
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
        return (int) static::db()->lastInsertId();
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
