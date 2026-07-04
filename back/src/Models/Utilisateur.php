<?php

namespace App\Models;

class Utilisateur extends Model
{
    protected static string $table = 'utilisateur';
    protected static string $primaryKey = 'Id_util';

    public static function findByEmail(string $email): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM utilisateur WHERE Email_util = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
